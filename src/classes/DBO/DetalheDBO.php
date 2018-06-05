<?php
namespace DBO;

class DetalheDBO extends DBO {
    private $criado;
    private $modificado;

    private $emprestimo;

    private $tipo; 
    private $info;    
    private $descricao;
    private $valor;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("detalhe");
    }
    
    // helpers

    protected function addCol($info) {
        $this->emprestimo = $info['emprestimo'];

        $this->tipo = filter_var($info['tipo'], FILTER_SANITIZE_NUMBER_INT);
        $this->descricao = filter_var($info['descricao'], FILTER_SANITIZE_STRING); 
        $this->info = filter_var($info['info'], FILTER_SANITIZE_STRING);         
        $this->valor = filter_var($info['valor'], FILTER_SANITIZE_NUMBER_FLOAT);
    }

    protected function getCol() {
        return array(
            "emprestimo" => $this->emprestimo,

            "tipo" => $this->tipo,
            "descricao" => $this->descricao,
            "info" => $this->info,              
            "valor" => $this->valor
        );
    }

    protected function getSqlCol() {
        return array(
            "emprestimo" => $this->emprestimo,

            "tipo" => $this->tipo, 
            "descricao" => '"'.$this->descricao.'"',
            "info" => '"'.$this->info.'"',              
            "valor" => $this->valor
        );
    }

    public function getAttributes() {
        $cols = $this->read($this->id);
        $this->setType($cols['tipo']);
        unset($cols['tipo']);
        return $cols;
    }

    // CREATE

    // READ
  

    // UPDATE


}