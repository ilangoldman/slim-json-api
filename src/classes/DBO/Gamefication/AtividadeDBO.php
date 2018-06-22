<?php
namespace DBO\Gamefication;

use \DBO\DBO;

class AtividadeDBO extends DBO {
    private $titulo; 
    private $descricao;
    private $pontos;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("atividade");
        $this->setType("atividade");
    }
    
    // helpers

    protected function setCol($info) {
        $this->pontos = $info['pontos'] ?? null;
        $this->titulo = filter_var($info['titulo'], FILTER_SANITIZE_STRING) ?? null;
        $this->descricao = filter_var($info['descricao'], FILTER_SANITIZE_STRING) ?? null;
    }

    protected function getCol() {
        return array(
            "pontos" => $this->pontos,
            "titulo" => $this->titulo, 
            "descricao" => $this->descricao
        );
    }

    protected function getSqlCol() {
        $cols = $this->getCol();
        $cols["titulo"] = '"'.$this->titulo.'"'; 
        $cols["descricao"] = '"'.$this->descricao.'"';
        return $cols;
    }

    public function allowAccess($userId,$userType,$itemId,$method) {
        if ($userType != "admin" && $method != "get")
            return false;
        return true;
    }

}