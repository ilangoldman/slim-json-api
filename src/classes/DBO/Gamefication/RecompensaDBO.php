<?php
namespace DBO\Gamefication;
use \DBO\DBO;

class RecompensaDBO extends DBO {
    private $pontuacao;

    private $titulo; 
    private $descricao;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("recompensa");
        $this->setType("recompensa");
    }
    
    // helpers

    protected function addCol($info) {
        $this->pontuacao = $info['pontuacao'] ?? null;

        $this->titulo = filter_var($info['titulo'], FILTER_SANITIZE_STRING) ?? null;
        $this->descricao = filter_var($info['descricao'], FILTER_SANITIZE_STRING) ?? null;
    }

    protected function getCol() {
        return array(
            "pontuacao" => $this->pontuacao,

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

    public function allowAccess($userId,$type,$id,$method) {
        if ($type != "admin" && $method != "get")
            return false;
        return true;
    }

}