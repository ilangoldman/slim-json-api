<?php
namespace DBO;

class ContaBancariaDBO extends DBO {
    private $criado;
    private $modificado;

    private $titular; 
    private $banco; 
    private $tipo; 
    private $agencia; 
    private $conta;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("conta_bancaria");
    }

    // helper
    
    protected function addCol($info) {
        $this->titular = filter_var($info['titular'],FILTER_SANITIZE_STRING);
        $this->banco = filter_var($info['banco'],FILTER_SANITIZE_NUMBER_INT);
        $this->tipo = filter_var($info['tipo'],FILTER_SANITIZE_NUMBER_INT);
        $this->agencia = filter_var($info['agencia'],FILTER_SANITIZE_NUMBER_INT);
        $this->conta = filter_var($info['conta'],FILTER_SANITIZE_NUMBER_INT);
    }

    protected function getCol() {
        return array(
            "titular" => $this->titular,
            "banco" => $this->banco,
            "tipo" => $this->tipo, 
            "agencia" => $this->agencia, 
            "conta" => $this->conta
        );
    }

    protected function getSqlCol() {
        return array(
            "titular" => '"'.$this->titular.'"',
            "banco" => $this->banco,
            "tipo" => $this->tipo, 
            "agencia" => $this->agencia, 
            "conta" => $this->conta
        );
    }

    // CREATE


    // READ


    // UPDATE
    

}

    