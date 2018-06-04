<?php
namespace DBO;

class Notificacao extends DBO {
    private $criado;
    private $modificado;

    private $titulo; 
    private $descricao; 
    private $data; 
    private $status; 
    private $categoria;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("notificacoes");
    }
    
    // helpers

    protected function addCol($info) {
        $this->titulo = filter_var($info['titulo'],FILTER_SANITIZE_STRING); 
        $this->descricao = filter_var($info['descricao'],FILTER_SANITIZE_STRING); 
        $this->data = $this->formatDate(filter_var($info['data']),FILTER_SANITIZE_STRING); 
        $this->status = filter_var($info['status'],FILTER_SANITIZE_NUMBER_INT); 
        $this->categoria = filter_var($info['categoria'],FILTER_SANITIZE_NUMBER_INT);
    }

    protected function getCol() {
        return array(
            "titulo" => $this->titulo, 
            "descricao" => $this->descricao, 
            "data" => $this->data, 
            "status" => $this->status, 
            "categoria" => $this->categoria
        );
    }

    protected function getSqlCol() {
        return array(
            "titulo" => '"'.$this->titulo.'"', 
            "descricao" => '"'.$this->descricao.'"', 
            "data" => '"'.$this->data.'"', 
            "status" => $this->status, 
            "categoria" => $this->categoria
        );
    }

    // CREATE

    // READ
  

    // UPDATE



}