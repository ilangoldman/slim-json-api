<?php
namespace DBO\Gamefication;
use \DBO\DBO;

class PontuacaoDBO extends DBO {
    private $criado;

    private $nivel; 
    private $pontos;    
    private $titulo; 
    private $simbolo;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("pontuacao");
        $this->setType("pontuacao");
    }
    
    // helpers

    protected function addCol($info) {
        $this->nivel = filter_var($info['nivel'], FILTER_SANITIZE_NUMBER_INT) ?? null;
        $this->pontos = filter_var($info['pontos'], FILTER_SANITIZE_NUMBER_INT) ?? null;
        $this->titulo = filter_var($info['titulo'], FILTER_SANITIZE_STRING) ?? null;
        $this->simbolo = filter_var($info['simbolo'], FILTER_SANITIZE_STRING) ?? null;
    }

    protected function getCol() {
        return array(
            "nivel" => $this->nivel,
            "pontos" => $this->pontos,
            "titulo" => $this->titulo, 
            "simbolo" => $this->simbolo
        );
    }

    protected function getSqlCol() {
        return array(
            "nivel" => $this->nivel,
            "pontos" => $this->pontos,
            "titulo" => '"'.$this->titulo.'"', 
            "simbolo" => '"'.$this->simbolo.'"'
        );
    }

    // CREATE

    // READ
  

    // UPDATE


}