<?php
namespace DBO\Investimento;
use \DBO\DBO;
use Price;

class EmprestimoDBO extends DBO {
    protected $price;

    private $criado;
    private $modificado;

    private $empresa;

    private $avalista;
    private $valor; 
    private $taxa;
    private $prazo; 
    private $valor_parcela;
    private $status; 
    private $motivo;
    private $faturamento;
    private $rating;

    private $saldo_devedor;

    // TODO
    // Pensar como fazer esses detalhes
    private $detalhe;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("emprestimo");
        $this->setType("emprestimo");

        $this->price = new Price();
    }

    private function formatStatus($code) {
        $status = array(
           -2 => "Deletado",
           -1 => "Aguardando Aprovação",
            0 => "Disponível para Investimento",
            1 => "Emprestimo Completo",
            2 => "Aguardando Transferencias",
            3 => "Financiado",
            4 => "Pagando Parcelas",
            5 => "Atraso",
            6 => "Inadimplente",
            7 => "Finalizado"
        );
        return $status[$code];
    }

    public function allowAccess($userId,$type,$id,$method) {
        if ($type != "empresa" && $method != "get")
            return false;
        return parent::allowAccess($userId,$type,$id,$method);        
    }

    // helper
    
    protected function addCol($info) {
        $this->empresa = $info['empresa'] ?? null;
        
        $this->avalista = filter_var($info['avalista'],FILTER_SANITIZE_STRING) ?? null;
        $this->valor = filter_var($info['valor'],FILTER_SANITIZE_NUMBER_INT) ?? null;
        $this->prazo = filter_var($info['prazo'],FILTER_SANITIZE_NUMBER_INT) ?? null; 
        $this->motivo = filter_var($info['motivo'],FILTER_SANITIZE_STRING) ?? null;
        $this->faturamento = filter_var($info['faturamento'],FILTER_SANITIZE_NUMBER_INT) ?? null;


        $this->rating = $info['rating'] ??
            $this->price->calcularRating($info);
        $this->taxa = $info['taxa'] ??
            $this->price->calcularTaxa($this->rating) * 1.23;
        $this->valor_parcela = $info['valor_parcela'] ??
            $this->price->calcularParcela($this->valor,$this->prazo,$this->taxa);
        $this->status = $info['status'] ?? -1;

        $this->saldo_devedor = $info['saldo_devedor'] ?? null;
    }

    protected function readCol($info) {
        $this->empresa = $info["empresa"] ?? null;
        $this->avalista = $info["avalista"] ?? null;
        $this->valor = $info["valor"] ?? null; 
        $this->taxa = $info["taxa"] ?? null;
        $this->prazo = $info["prazo"] ?? null; 
        $this->valor_parcela = $info["valor_parcela"] ?? null;
        $this->status = $info["status"] ?? null; 
        $this->motivo = $info["motivo"] ?? null;
        $this->faturamento = $info["faturamento"] ?? null;
        $this->rating = $info["rating"] ?? null;
        $this->saldo_devedor = $info["saldo_devedor"] ?? null;
    }

    protected function getCol() {
        return array(
            "empresa" => $this->empresa,

            "avalista" => $this->avalista,
            "valor" => $this->valor, 
            "taxa" => $this->taxa,
            "prazo" => $this->prazo, 
            "valor_parcela" => $this->valor_parcela,
            "status" => $this->status, 
            "motivo" => $this->motivo,
            "faturamento" => $this->faturamento,
            "rating" => $this->rating,
            "saldo_devedor" => $this->saldo_devedor
        );
    }

    protected function getSqlCol() {
        $cols = $this->getCol();
        $cols["avalista"] = '"'.$this->avalista.'"';
        $cols["motivo"] = '"'.$this->motivo.'"';
        $cols["saldo_devedor"] = $cols['saldo_devedor'] ?? "null";
        return $cols;
    }

    public function getAttributes() {
        $attrib = $this->read($this->id);
        unset($attrib['empresa']);        
        return $attrib;
    }

    public function getRelationships() {
        $response = array();
        $response = $this->getDetalhes($response);

        $sql = "SELECT empresa".
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
        
        // $fk = ["parcela"];
        // // $response = array();
        // foreach($fk as $v) {
        //     $response[$v] = $this->getTablesFK($v);
        // }
        
        // var_export($response);
        return $response;
    }

    protected function getDetalhes($respose) {
        $sql = "SELECT detalhe, tipo".
            " FROM detalhe".
            " WHERE ".$this->table_name." = ".$this->id.
            " ORDER BY tipo, valor DESC, descricao";
        
        // var_export($sql);
        $stmt = $this->db->query($sql);
        $lastTipo = '';
        $dbo = $this->controller->detalhe();
        // var_export($dbo);
        while ($row = $stmt->fetch()) {
            extract($row);

            if ($lastTipo != $tipo) {
                if($lastTipo != '') {
                    $response[$lastTipo] = array(
                        "data" => $data
                    );
                }
                $lastTipo = $tipo;
                $data = array();
            }

            $data[] = array(
                "type" => $dbo->getType(),
                "id" => $detalhe
            );
        }

        $response[$lastTipo] = array(
            "data" => $data
        );

        // var_export($response);
        return $response;
    }


    // READ
    public function read($id) {
        $this->setId($id);

        $sql =  "SELECT ".$this->getColKeys().
                " FROM ".$this->table_name.
                " WHERE ".$this->table_name." = ".$this->id.
                " AND status > -2";
        // var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            $this->readCol($row);
        } else {
            return null;
        }
        
        return $this->getCol();
    }

    // DELETE
    public function delete($id) {
        $this->read($id);
        if ($this->status > -1) return false;

        $dbo = $this->controller->investimento();
        $dbo->deleteInvestimentos($id);

        $this->setId($id);
        $sql = "UPDATE ".$this->table_name.
               " SET status = -2".
               " WHERE ".$this->table_name." = ".$id.
               " AND status < 0;";
        // var_export($sql);

        $stmt = $this->db->exec($sql);
        // var_export($stmt);
        return ($stmt > 0);
    }

    public function temEmprestimoAtivo($empresa) {
        $sql = "SELECT count(".$this->table_name.") as ".$this->table_name. 
            " FROM ".$this->table_name.
            " WHERE empresa = ".$empresa.
            " AND status not in (-1,7)";
        $emprestimo = 0;
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            extract($row);
        }
        return ($emprestimo > 0);
    }

    // Business Logic Functions
    
    
    public function aprovarEmprestimo() {
        $sql = "UPDATE ".$this->table_name.
               " SET status = 0, saldo_devedor = valor".
               " WHERE ".$this->table_name." = ".$this->id;
        $stmt = $this->db->exec($sql);
        return ($stmt > 0);
    }

    public function iniciarTransferencias() {
        $sql = "UPDATE ".$this->table_name.
               " SET status = 2, saldo_devedor = valor".
               " WHERE ".$this->table_name." = ".$this->id;
        $stmt = $this->db->exec($sql);
        if ($stmt == 0) return false;
        
        $dbo = $this->controller->investimento();
        return $dbo->emprestimoFinanciado($this->id);        
    }






    private function empresaFinanciada() {
        $sql = "UPDATE ".$this->table_name.
            " SET saldo_devedor = valor".
            " WHERE ".$this->table_name." = ".$this->id.
            " AND saldo_devedor = 0";
        $stmt = $this->db->exec($sql);    
    }

    private function empresaNaoFinanciada() {
        $sql = "UPDATE ".$this->table_name.
            " SET status = 0, saldo_devedor = 0".
            " WHERE ".$this->table_name." = ".$this->id;
        // var_export($sql);
        $stmt = $this->db->exec($sql);        
    }



    

    public function addInvestimento($investido) {
        $sql = "UPDATE ".$this->table_name.
               " SET saldo_devedor = IF(saldo_devedor - ".$investido." = 0, valor,saldo_devedor - ".$investido.")".
               ", status = IF(saldo_devedor = valor, 1,0)".
               " WHERE ".$this->table_name." = ".$this->id.
               " AND status = 0 AND saldo_devedor != 0";
        // var_export($sql);
        $stmt = $this->db->exec($sql);
        // var_export ($stmt);
        // if ($stmt == 0) $this->empresaFinanciada();
        return ($stmt > 0);
    }

    public function removeInvestimento($investido) {
        $this->read($this->id);
        $sql =  "UPDATE ".$this->table_name.
                " SET saldo_devedor = saldo_devedor + ".$investido.
                " WHERE ".$this->table_name." = ".$this->id.
                " AND status in (0,2)";
        // var_export($sql);

        if ($this->status == 2) {
            $saldo = $this->saldo_devedor;
            $this->empresaNaoFinanciada();
            $stmt = $this->db->exec($sql);
            $dbo = $this->controller->investimento();
            $dbo->getListaDeEspera($this->id, $investido);
             $sql = "UPDATE ".$this->table_name.
                    " SET saldo_devedor = ".$saldo.
                    " WHERE ".$this->table_name." = ".$this->id;
            $this->db->exec($sql);
        } else {
            $stmt = $this->db->exec($sql);
            if ($stmt == 0) {
                $this->empresaNaoFinanciada();
                $stmt = $this->db->exec($sql);
                $dbo = $this->controller->investimento();
                $dbo->getListaDeEspera($this->id, $investido);
            } 
        }

        return ($stmt > 0);
    }


    public function addTransferencia($investido) {
        $sql = "UPDATE ".$this->table_name.
               " SET saldo_devedor = IF(saldo_devedor - ".$investido." = 0, valor,saldo_devedor - ".$investido.")".
               ", status = IF(saldo_devedor = valor, 3,2)".
               " WHERE ".$this->table_name." = ".$this->id.
               " AND status = 2 AND saldo_devedor != 0";
        // var_export($sql);
        $stmt = $this->db->exec($sql);
        // if ($stmt == 0) $this->empresaFinanciada();
        return ($stmt > 0);
    }


    



    public function getInfoEmprestimoInvestir($id) {
        $sql = "SELECT valor, prazo, rating FROM ".$this->table_name.
               " WHERE ".$this->table_name." = ".$id;

        $info = null;
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            $info = $row;
        }
        return $info;
    }


    public function getStatus() {
        $sql = "SELECT status FROM ".$this->table_name.
               " WHERE ".$this->table_name." = ".$this->id;
        // var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            extract($row);
        }
        return $status ?? null;
    }
    
    public function setStatus($status) {
        $sql = "UPDATE ".$this->table_name.
               " SET status = ".$status.
               " WHERE ".$this->table_name." = ".$this->id;
        $stmt = $this->db->exec($sql);
        return ($stmt > 0);
    }



}