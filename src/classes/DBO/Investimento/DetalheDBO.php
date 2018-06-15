<?php
namespace DBO\Investimento;
use \DBO\DBO;

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
        $this->setType("detalhe");
    }
    
    // helpers

    protected function addCol($info) {
        $this->emprestimo = $info['emprestimo'] ?? null;

        $this->tipo = filter_var($info['tipo'], FILTER_SANITIZE_STRING) ?? null;
        $this->descricao = filter_var($info['descricao'], FILTER_SANITIZE_STRING) ?? null; 
        $this->info = filter_var($info['info'], FILTER_SANITIZE_STRING) ?? null;  
        $this->valor = isset($info['valor']) ? 
            filter_var($info['valor'], FILTER_SANITIZE_NUMBER_FLOAT) : null;
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
        $cols = $this->getCol();
        $cols["tipo"] = '"'.$this->tipo.'"';
        $cols["descricao"] = '"'.$this->descricao.'"';
        $cols["info"] = '"'.$this->info.'"';
        $cols["valor"] = $this->valor ?? "null";
        return $cols;
    }

    public function getAttributes() {
        $cols = $this->read($this->id);
        unset($cols['emprestimo']);
        return $cols;
    }

    // CREATE

    // READ
  

    // UPDATE


}