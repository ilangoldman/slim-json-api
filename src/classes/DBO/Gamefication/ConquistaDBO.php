<?php
namespace DBO\Gamefication;
use \DBO\DBO;

class ConquistaDBO extends DBO {
    private $criado;

    private $titulo; 
    private $descricao;
    private $pontos;    
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("conquista");
    }
    
    // helpers

    protected function setCol($info) {
        $this->titulo = filter_var($info['titulo'], FILTER_SANITIZE_STRING) ?? null;
        $this->descricao = filter_var($info['descricao'], FILTER_SANITIZE_STRING) ?? null;
        $this->pontos = filter_var($info['pontos'], FILTER_SANITIZE_NUMBER_INT) ?? null;        
    }

    protected function getCol() {
        return array(
            "titulo" => $this->titulo, 
            "descricao" => $this->descricao,
            "pontos" => $this->pontos
        );
    }

    protected function getSqlCol() {
        return array(
            "titulo" => '"'.$this->titulo.'"', 
            "descricao" => '"'.$this->descricao.'"',
            "pontos" => $this->pontos            
        );
    }


}