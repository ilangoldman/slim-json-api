<?php
namespace DBO;

class BeneficioDBO extends DBO {
    private $criado;

    private $conquista;

    private $titulo; 
    private $descricao;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("beneficio");
    }
    
    // helpers

    protected function addCol($info) {
        $this->conquista = $info['conquista'];

        $this->titulo = filter_var($info['titulo'], FILTER_SANITIZE_STRING);
        $this->descricao = filter_var($info['descricao'], FILTER_SANITIZE_STRING);
    }

    protected function getCol() {
        return array(
            "conquista" => $this->conquista,

            "titulo" => $this->titulo, 
            "descricao" => $this->descricao
        );
    }

    protected function getSqlCol() {
        return array(
            "conquista" => $this->conquista,

            "titulo" => '"'.$this->titulo.'"', 
            "descricao" => '"'.$this->descricao.'"'
        );
    }

    // CREATE

    // READ
  

    // UPDATE


}