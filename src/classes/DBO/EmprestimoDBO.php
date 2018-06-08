<?php
namespace DBO;

use Price;

class EmprestimoDBO extends DBO {
    private $price;

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
            filter_var($info['rating'],FILTER_SANITIZE_NUMBER_FLOAT) :
            $this->price->calcularRating();
        $this->taxa = (isset($info['taxa'])) ?
            filter_var($info['taxa'],FILTER_SANITIZE_NUMBER_FLOAT) :
            $this->price->calcularTaxa($this->rating);
        $this->valor_parcela = (isset($info['valor_parcela'])) ?
            filter_var($info['valor_parcela'],FILTER_SANITIZE_NUMBER_FLOAT) :
            $this->price->calcularParcela($this->valor,$this->prazo,$this->taxa);
        
        $this->status = (isset($info['status'])) ?
            filter_var($info['status'],FILTER_SANITIZE_NUMBER_FLOAT) : -1;
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
            "rating" => $this->rating
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


       public function getRelationships() {
        $sql = "SELECT empresa".
            " FROM ".$this->table_name.
            " WHERE ".$this->table_name." = ".$this->id;
        var_export($sql);
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

        $response = $this->getDetalhes($response);

        $fk = ["parcela"];
        // $response = array();
        foreach($fk as $v) {
            $response[$v] = $this->getTablesFK($v);
        }
        
        // var_export($response);
        return $response;
    }

    protected function getDetalhes($respose) {
        $sql = "SELECT detalhe, tipo".
            " FROM detalhe".
            " WHERE ".$this->table_name." = ".$this->id.
            " ORDER BY tipo, valor, descricao";
        
        var_export($sql);
        $stmt = $this->db->query($sql);
        $data = array();
        $lastTipo = '';
        while ($row = $stmt->fetch()) {
            $dbo = $this->controller->detalhe();
            extract($row);

            if ($lastTipo != $tipo) {
                $response[$lastTipo] = array(
                    "data" => $data
                );
                $lastTipo = $tipo;
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


    // CREATE


    // READ


    // UPDATE
    
}