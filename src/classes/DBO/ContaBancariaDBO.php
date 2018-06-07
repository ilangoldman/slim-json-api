<?php
namespace DBO;

class ContaBancariaDBO extends DBO {
    private $criado;
    private $modificado;

    private $empresa;
    private $investidor;

    private $titular; 
    private $banco; 
    private $tipo; 
    private $agencia; 
    private $conta;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("conta_bancaria");
        $this->setType("conta_bancaria");        
    }

    // helper
    
    protected function addCol($info) {
        $this->empresa = isset($info['empresa']) ? $info['empresa']:NULL;
        $this->investidor = isset($info['investidor']) ? $info['investidor']:NULL;

        $this->titular = filter_var($info['titular'],FILTER_SANITIZE_STRING);
        $this->banco = filter_var($info['banco'],FILTER_SANITIZE_NUMBER_INT);
        $this->tipo = filter_var($info['tipo'],FILTER_SANITIZE_NUMBER_INT);
        $this->agencia = filter_var($info['agencia'],FILTER_SANITIZE_NUMBER_INT);
        $this->conta = filter_var($info['conta'],FILTER_SANITIZE_NUMBER_INT);
    }

    protected function getCol() {
        return array(
            "empresa" => $this->empresa,
            "investidor" => $this->investidor,
            
            "titular" => $this->titular,
            "banco" => $this->banco,
            "tipo" => $this->tipo, 
            "agencia" => $this->agencia, 
            "conta" => $this->conta
        );
    }

    protected function getSqlCol() {
        return array(
            "empresa" => ($this->empresa) ? $this->empresa:'NULL',
            "investidor" => ($this->investidor) ? $this->investidor:'NULL',

            "titular" => '"'.$this->titular.'"',
            "banco" => $this->banco,
            "tipo" => $this->tipo, 
            "agencia" => $this->agencia, 
            "conta" => $this->conta
        );
    }

    public function getAttributes() {
        $cols = $this->read($this->id);
        // foreach ($cols as $k => $c) {
        //     if ($c == NULL) unset($cols[$k]);
        // }
        $pkArray = ["investidor","empresa"];
        foreach ($pkArray as $pk) {
            unset($cols[$pk]);
        }
        return $cols;
    }

    // CREATE


    // READ


    // UPDATE
    

}

    