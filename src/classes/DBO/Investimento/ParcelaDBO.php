<?php
namespace DBO\Investimento;
use \DBO\DBO;
use Price;

class ParcelaDBO extends DBO {
    private $price;

    private $criado;

    private $emprestimo;
    private $investimento;
    private $parcela_empresa;
    
    private $numero;
    private $valor; 
    private $principal; 
    private $rendimentos; 
    private $multa;
    private $ir;

    private $comprovante;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("parcela");
        $this->setType("parcela");

        $this->price = new Price();
    }

    // helper
    
    protected function addCol($info) {
        $this->emprestimo = $info['emprestimo'];
        $this->investimento = $info['investimento'];
        $this->parcela_empresa = $info['parcela_empresa'];
        $this->comprovante = filter_var($info['comprovante'], FILTER_SANITIZE_STRING);
        // TODO
    }

    protected function readCol() {
        $this->emprestimo = $info['emprestimo'];

        $this->investimento = $info['investimento'];
        $this->parcela_empresa = $info['parcela_empresa'];

        $this->comprovante = $info['comprovante'];
        
        $this->numero = $info['numero']; 
        $this->valor = $info['valor']; 
        $this->principal = $info['principal']; 
        $this->rendimentos = $info['rendimentos']; 
        $this->multa = $info['multa'];
        $this->ir = $info['ir'];

    }

    protected function getCol() {
        return array(
            "emprestimo" => $this->emprestimo,
            "investimento" => $this->investimento,
            "parcela_empresa" => $this->parcela_empresa,
            
            "numero" => $this->numero,
            "valor" => $this->valor, 
            "principal" => $this->principal, 
            "rendimentos" => $this->rendimentos, 
            "multa" => $this->multa,
            "ir" => $this->ir,

            "comprovante" => $this->comprovante
        );
    }

    protected function getSqlCol() {
        $cols = $this->getCol();
        $cols["comprovante"] = '"'.$this->comprovante.'"';
        $cols["investimento"] = $cols["investimento"] ?? null;
        $cols["parcela_empresa"] = $cols["parcela_empresa"] ?? null;
        return $cols;
    }

    // CREATE
    public function create($info) {
        $this->addCol($info);
        
        $values = implode(",",$this->getSqlCol());
        $sql = "INSERT INTO ".$this->table_name.
        " (".$this->getSqlColKeys().")".
        " values (".$values.');';
        var_export($sql);
        $stmt = $this->db->exec($sql);

        // TODO

        // $parcelaInvestimento = new Parcela($this->db);
        // $parcelaInvestimento->addParcelaInvestimento($emprestimo);

        return $this->readId();
    }

    public function addParcelaInvestimento() {
        // $sql = "SELECT"


        $this->addCol($info);
        
        $values = implode(",",$this->getSqlCol());
        $sql = "INSERT INTO ".$this->table_name.
        " (".$this->getSqlColKeys().")".
        " values (".$values.');';
        var_export($sql);
        $stmt = $this->db->exec($sql);

        $parcelaInvestimento = new Parcela($this->db);
        $parcelaInvestimento->addParcelasInvestimentos($emprestimo);

        return $this->readId();
    }

    // READ


    // UPDATE
    


}