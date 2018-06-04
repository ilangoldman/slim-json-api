<?php
namespace DBO;

class AmigoDBO extends DBO {
    private $criado;

    private $user; 
    private $convidado;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("amigo");
    }
    
    // helpers

    protected function addCol($info) {
        $this->user = $info['user'];
        $this->convidado = $info['convidado'];
    }

    protected function getCol() {
        return array(
            "user" => $this->user, 
            "convidado" => $this->convidado
        );
    }

    protected function getSqlCol() {
        return array(
            "user" => $this->user, 
            "convidado" => $this->convidado     
        );
    }

    // CREATE

    // READ
  

    // UPDATE


}