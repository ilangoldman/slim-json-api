<?php
namespace DBO\Investimento;
use \DBO\DBO;
use Price;

class InvestimentoDBO extends DBO {
    private $price;

    private $criado;
    private $modificado;

    private $investidor;
    private $emprestimo;

    private $taxa;
    private $valor; 
    private $status; 
    private $prazo; 
    private $valor_parcela;
    private $saldo_devedor;

    private $contrato;
    private $comprovante;

    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("investimento");
        $this->setType("investimento");

        $this->price = new Price();
    }

    private function formatStatus($code) {
        $status = array(
            0 => "Lista de Espera",
            1 => "Oferta Aceita",
            2 => "Aguardando Transferencias",
            3 => "Transferencia Realizada",
            4 => "Recebendo Parcelas",
            5 => "Atraso",
            6 => "Inadimplente"
        );
        return $status[$code];
    }

    public function allowAccess($userId,$type,$id,$method) {
        if ($type != "investidor")
            return false;

        return parent::allowAccess($userId,$type,$id,$method);
    }

    //setters
    public function setInvestidor($investidor) {
        $this->investidor = $investidor;
    }

    public function setEmprestimo($emprestimo) {
        $this->emprestimo = $emprestimo;
    }

    // helper
    
    protected function addCol($info) {
        $this->investidor = $info['investidor'];
        $this->emprestimo = $info['emprestimo'];

        $this->valor = filter_var($info['valor'],FILTER_SANITIZE_NUMBER_FLOAT);
        $this->status = 0;

        $this->getInfoEmprestimo();

        $this->contrato = filter_var($info['contrato'],FILTER_SANITIZE_STRING);
        $this->comprovante = filter_var($info['comprovante'],FILTER_SANITIZE_STRING);
    }

    protected function readCol($info) {
        $this->investidor = $info['investidor'];
        $this->emprestimo = $info['emprestimo'];

        $this->taxa = $info['taxa'];
        $this->valor = $info['valor'];
        $this->prazo = $info['prazo'];
        $this->valor_parcela = $info['valor_parcela'];
        $this->status = $info['status'];

        $this->saldo_devedor = $info['saldo_devedor'];

        $this->contrato = $info['contrato'];        
        $this->comprovante = $info['comprovante'];
    }

    // getters

    protected function getCol() {
        return array(
            "investidor" => $this->investidor,
            "emprestimo" => $this->emprestimo,

            "taxa" => $this->taxa,
            "valor" => $this->valor,
            "status" => $this->status, 
            "prazo" => $this->prazo, 
            "valor_parcela" => $this->valor_parcela,
            "contrato" => $this->contrato,
            "comprovante" => $this->comprovante
        );
    }

    protected function getSqlCol() {
        $cols = $this->getCol();
        $cols["contrato"] = '"'.$this->contrato.'"';
        $cols["comprovante"] = '"'.$this->comprovante.'"';
        return $cols;
    }

    
    public function getRelationships() {
        $sql = "SELECT investidor, emprestimo".
            " FROM ".$this->table_name.
            " WHERE ".$this->table_name." = ".$this->id;
        // var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            foreach ($row as $k => $v) {
                // var_export($k."|".$v);                
                $dbo = $this->controller->{$k}();
                $response[$dbo->getType()] = array(
                        "data" => array(
                            "type" => $dbo->getType(),
                            "id" => $v
                    )
                );
            }
        }

        $fk = ["parcela"];
        // $response = array();
        foreach($fk as $v) {
            $response[$v] = $this->getTablesFK($v);
        }
        
        // var_export($response);
        return $response;
    }

    public function getAttributes() {
        $attrib = $this->read($this->id);
        unset($attrib['investidor']);
        unset($attrib['emprestimo']);        
        return $attrib;
    }

    private function getInfoEmprestimo() {
        $dbo = $this->controller->emprestimo();
        $emprestimoInfo = $dbo->getInfoEmprestimoInvestir($this->emprestimo);

        $this->prazo = $emprestimoInfo['prazo'];
        $this->taxa = $this->price->calcularTaxa($emprestimoInfo['rating']) * 1.30;
        $this->valor_parcela = $this->price->calcularParcela($this->valor,$this->prazo,$this->taxa);
    }



    // CREATE



    public function create($info) {
        $this->addCol($info);

        $dbo = $this->controller->emprestimo();
        $dbo->setId($this->emprestimo);      
        if ($dbo->addInvestimento($this->valor)) {
            $this->status = 1;
        }

        return parent::create($info);
    }



    // READ
    public function readAll() {
        $sql = "SELECT ".$this->table_name.",".$this->getColKeys().
            " FROM ".$this->table_name.
            " WHERE investidor = ".$this->investidor;
        // var_export($sql);
        $stmt = $this->db->query($sql);
        $result = array();
        while ($row = $stmt->fetch()) {
            array_push($result,$row);
        }

        return $result;
    }


    // UPDATE
    public function updateStatus($code, $valor = null) {
        $strValor = (isset($valor)) ? ', valor = '.$valor : '';  
        $sql = "UPDATE ".$this->table_name.
            " SET status = ".$code.$strValor.
            " WHERE ".$this->table_name." = ".$this->id;
        // var_export($sql);
        $stmt = $this->db->exec($sql);       
    }

    public function updateAll($id, $info) {
        $this->read($id);

        $dbo = $this->controller->emprestimo();
        $dbo->addTransferencia($this->valor);
        
        $info['status'] = 3;
        return parent::updateAll($id,$info);
    }




    // DELETE
    public function delete($id) {
        $this->read($this->id);
        // var_export($this->emprestimo);
        $dbo = $this->controller->emprestimo();
        $dbo->setId($this->emprestimo);

        $statusEmprestimo = $dbo->getStatus();
        $dbo->removeInvestimento($this->valor);
        if ($statusEmprestimo != $dbo->getStatus()) {
            $dbo->setStatus($statusEmprestimo);
            $this->updateInvestimentoListaDeEspera($statusEmprestimo);
        }

        return parent::delete($id);
    }


    // Business Logic

    public function getListaDeEspera($id, $valorRestante) {
        $dbo = $this->controller->emprestimo();
        $dbo->setId($id);
        
        $sql = "SELECT valor,".$this->table_name.
               " FROM ".$this->table_name.
               " WHERE emprestimo = ".$id.
               " AND status = 0".
               " ORDER BY criado, investimento";
        // var_export($sql);
        $stmt = $this->db->query($sql);
        while ($row = $stmt->fetch()) {
            extract($row);
            $this->setId($investimento);
            if ($valor < $valorRestante) {
                if ($dbo->addInvestimento($valor)) {
                    $this->updateStatus(1);
                    $valorRestante -= $valor;
                }
            } else {
                if ($dbo->addInvestimento($valorRestante)) {
                    $this->updateStatus(1, $valorRestante);
                    break;
                }
            }
        }
    }

    public function updateInvestimentoListaDeEspera($status) {
        $sql = "UPDATE ".$this->table_name.
               " SET status = ".$status.
               " WHERE status = 1 AND emprestimo = ".$this->emprestimo;
        $stmt = $this->db->exec($sql);
        return ($stmt > 0);
    }

    public function emprestimoFinanciado($id) {
         $sql = "UPDATE ".$this->table_name.
            " SET status = 2".
            " WHERE emprestimo = ".$id.
            " AND status = 1";
        $stmt = $this->db->exec($sql);
        return ($stmt > 0); 
    }

    

    // public function valorArrecadado() {
    //     $sql = "UPDATE ".$this->table_name.
    //         " SET status = 2".
    //         " WHERE ".$this->table_name." = ".$this->id.
    //         " AND status = 0";
    //     $stmt = $this->db->exec($sql);        
    // }


    public function readTemInvestimentos($investidor) {
         $sql = "SELECT count(".$this->table_name.") as ".$this->table_name. 
            " FROM ".$this->table_name.
            " WHERE investidor = ".$investidor;

        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            extract($row);
        }
        return !($investimento > 0);
    }

    

    


 



}