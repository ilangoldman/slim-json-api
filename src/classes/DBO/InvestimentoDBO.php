<?php
namespace DBO;

class InvestimentoDBO extends DBO {
    private $criado;
    private $modificado;

    private $investidor;
    private $emprestimo;

    private $taxa;
    private $valor; 
    private $status; 
    private $prazo; 
    private $valor_parcela;
    private $contrato;
    private $comprovante;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("investimento");
    }

    // helper
    
    protected function addCol($info) {
        $this->investidor = $info['investidor'];
        $this->emprestimo = $info['emprestimo'];

        $this->taxa = filter_var($info['taxa'],FILTER_SANITIZE_NUMBER_INT);
        $this->valor = filter_var($info['valor'],FILTER_SANITIZE_NUMBER_INT);
        $this->status = filter_var($info['status'],FILTER_SANITIZE_NUMBER_INT);
        $this->prazo = filter_var($info['prazo'],FILTER_SANITIZE_NUMBER_INT);
        $this->valor_parcela = filter_var($info['valor_parcela'],FILTER_SANITIZE_NUMBER_INT);
        

        $this->contrato = filter_var($info['contrato'],FILTER_SANITIZE_STRING);        
        $this->comprovante = filter_var($info['comprovante'],FILTER_SANITIZE_STRING);
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
        $sql = "SELECT investidor".
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

        $fk = ["emprestimo", "parcela"];
        // $response = array();
        foreach($fk as $v) {
            $response[$v] = $this->getTablesFK($v);
        }
        
        // var_export($response);
        return $response;
    }

    // CREATE


    // READ
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

    // UPDATE


}