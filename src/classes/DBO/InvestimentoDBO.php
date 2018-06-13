<?php
namespace DBO;

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
        return array(
            "investidor" => $this->investidor,
            "emprestimo" => $this->emprestimo,

            "taxa" => $this->taxa,
            "valor" => $this->valor,
            "status" => $this->status, 
            "prazo" => $this->prazo, 
            "valor_parcela" => $this->valor_parcela,
            "contrato" => '"'.$this->contrato.'"',
            "comprovante" => '"'.$this->comprovante.'"'
        );
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

    public function create($info) {
        $this->addCol($info);

        $dbo = $this->controller->emprestimo();
        $dbo->setId($this->emprestimo);
        // var_export($this->status);        
        if ($dbo->addInvestimento($this->valor)) {
            // var_export($this->status);
            $this->status = 1;
        }

        // var_export($this->status);

        $values = implode(",",$this->getSqlCol());
        $sql = "INSERT INTO ".$this->table_name.
        " (".$this->getSqlColKeys().")".
        " values (".$values.');';
        // var_export($sql);
        $stmt = $this->db->exec($sql);
        return $this->readId();
    }

    public function getListaDeEspera($id, $valorRestante) {
        $dbo = $this->controller->emprestimo();
        $dbo->setId($id);
        
        $sql = "SELECT valor,".$this->table_name.
               " FROM ".$this->table_name.
               " WHERE emprestimo = ".$id.
               " AND status != 1".
               " ORDER BY criado, investimento";
        // var_export($sql);
        $stmt = $this->db->query($sql);
        while ($row = $stmt->fetch()) {
            // TODO
            // Como analisar quem pegar?
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

    public function updateStatus($code, $valor = null) {
        $strValor = (isset($valor)) ? ', valor = '.$valor : '';  
        $sql = "UPDATE ".$this->table_name.
            " SET status = 1".$strValor.
            " WHERE ".$this->table_name." = ".$this->id.
            " AND status = 0";
        // var_export($sql);
        $stmt = $this->db->exec($sql);       
    }

    public function valorArrecadado() {
        $sql = "UPDATE ".$this->table_name.
            " SET status = 2".
            " WHERE ".$this->table_name." = ".$this->id.
            " AND status = 0";
        $stmt = $this->db->exec($sql);        
    }

    public function emprestimoFinanciado() {
         $sql = "UPDATE ".$this->table_name.
            " SET status = 3".
            " WHERE ".$this->table_name." = ".$this->id.
            " AND status = 1";
        $stmt = $this->db->exec($sql);        
    }

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

    private function getStatus($code) {
        $status = array(
            0 => "Lista de Espera",
            1 => "Oferta Aceita",
            2 => "Aguardando Transferencias",
            3 => "Financiado",
            4 => "Recebendo Parcelas",
            5 => "Atraso",
            6 => "Inadimplente"
        );
        return $status[$code];
    }

    public function allowAccess($userId,$type,$id,$method) {
        if ($type != "investidor")
            return false;

        $sql = "SELECT ".$type." FROM ".$this->table_name.
               " WHERE ".$this->table_name." = ".$id;
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            return ($row[$type] == $userId);
        }
        return false;        
    }


    public function delete($id) {
        $this->addId($id);
        $this->read($this->id);
        // var_export($this->emprestimo);
        $dbo = $this->controller->emprestimo();
        $dbo->setId($this->emprestimo);
        $dbo->removeInvestimento($this->valor);

        $sql = "DELETE FROM ".$this->table_name.
               " WHERE ".$this->table_name." = ".$id.";";
        $stmt = $this->db->exec($sql);

        return ($stmt > 0);
    }
}