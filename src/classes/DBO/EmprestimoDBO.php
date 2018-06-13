<?php
namespace DBO;

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

    // Pensar como fazer esses detalhes
    private $detalhe;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("emprestimo");
        $this->setType("emprestimo");

        $this->price = new Price();
    }

    // helper
    
    protected function addCol($info) {
        $this->empresa = $info['empresa'];
        
        $this->avalista = filter_var($info['avalista'],FILTER_SANITIZE_STRING);
        $this->valor = filter_var($info['valor'],FILTER_SANITIZE_NUMBER_INT);
        $this->prazo = filter_var($info['prazo'],FILTER_SANITIZE_NUMBER_INT); 
        $this->motivo = filter_var($info['motivo'],FILTER_SANITIZE_STRING);
        $this->faturamento = filter_var($info['faturamento'],FILTER_SANITIZE_NUMBER_INT);


        $this->rating = (isset($info['rating'])) ?
            $info['rating'] :
            $this->price->calcularRating($info);
        $this->taxa = (isset($info['taxa'])) ?
            $info['taxa'] :
            $this->price->calcularTaxa($this->rating) * 1.23;
        $this->valor_parcela = (isset($info['valor_parcela'])) ?
            $info['valor_parcela'] :
            $this->price->calcularParcela($this->valor,$this->prazo,$this->taxa);
        
        $this->status = (isset($info['status'])) ?
            $info['status'] : -1;

        $this->saldo_devedor = $info['saldo_devedor'];
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
        return array(
            "empresa" => $this->empresa,

            "avalista" => '"'.$this->avalista.'"',
            "valor" => $this->valor, 
            "taxa" => $this->taxa,
            "prazo" => $this->prazo, 
            "valor_parcela" => $this->valor_parcela,
            "status" => $this->status, 
            "motivo" => '"'.$this->motivo.'"',
            "faturamento" => $this->faturamento,
            "rating" => $this->rating
        );
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

    public function aprovarEmprestimo() {
        $sql = "UPDATE ".$this->table_name.
               " SET status = 0, saldo_devedor = valor".
               " WHERE ".$this->table_name." = ".$this->id;
        $stmt = $this->db->exec($sql);
        return ($stmt > 0);
    }

    public function addInvestimento($investido) {
        $sql = "UPDATE ".$this->table_name.
               " SET saldo_devedor = IF(saldo_devedor - ".$investido." = 0, valor,saldo_devedor - ".$investido.")".
               ", status = IF(saldo_devedor = valor, 1,0)".
               " WHERE ".$this->table_name." = ".$this->id.
               " AND status = 0 AND saldo_devedor != 0";
        // var_export($sql);
        $stmt = $this->db->exec($sql);
        // if ($stmt == 0) $this->empresaFinanciada();
        return ($stmt > 0);
    }

    public function removeInvestimento($investido) {
        $sql = "UPDATE ".$this->table_name.
               " SET saldo_devedor = saldo_devedor + ".$investido.
               " WHERE ".$this->table_name." = ".$this->id.
               " AND status = 0";
        $stmt = $this->db->exec($sql);
        if ($stmt == 0) {
            $this->empresaNaoFinanciada();
            $stmt = $this->db->exec($sql);
            $dbo = $this->controller->investimento();
            $dbo->getListaDeEspera($this->id, $investido);
        }
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

    public function allowAccess($userId,$type,$id,$method) {
        if ($type != "empresa" && $method != "get")
            return false;

        $sql = "SELECT ".$type." FROM ".$this->table_name.
               " WHERE ".$this->table_name." = ".$id;
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            return ($row[$type] == $userId);
        }
        return false;        
    }
    

    private function getStatus($code) {
        $status = array(
            -1 => "Aguardando Aprovação",
            0 => "Disponível para Investimento",
            1 => "Emprestimo Completo",
            2 => "Aguardando Transferencias",
            3 => "Financiado",
            4 => "Pagando Parcelas",
            5 => "Atraso",
            6 => "Inadimplente"
        );
        return $status[$code];
    }
}