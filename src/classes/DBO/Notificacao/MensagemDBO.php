<?php
namespace DBO\Notificacao;
use \DBO\DBO;

class MensagemDBO extends DBO {
    private $criado;
    private $modificado;

    public $titulo; 
    public $descricao; 
    public $data;
    public $categoria;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("mensagem");
        $this->setType("mensagem");
        $this->relations(["notificacao"]);
    }

}

// [DEPRECATED]

    // // helpers

    // protected function addCol($info) {
    //     $this->titulo = filter_var($info['titulo'],FILTER_SANITIZE_STRING); 
    //     $this->descricao = filter_var($info['descricao'],FILTER_SANITIZE_STRING); 
    //     $this->data = $this->formatDate(filter_var($info['data']),FILTER_SANITIZE_STRING);
    //     $this->categoria = filter_var($info['categoria'],FILTER_SANITIZE_NUMBER_INT);
    // }

    // protected function getCol() {
    //     return array(
    //         "titulo" => $this->titulo, 
    //         "descricao" => $this->descricao, 
    //         "data" => $this->data, 
    //         "categoria" => $this->categoria
    //     );
    // }

    // protected function getSqlCol() {
    //     $cols = $this->getCol();
    //     $cols["titulo"] = '"'.$this->titulo.'"';
    //     $cols["descricao"] = '"'.$this->descricao.'"';
    //     $cols["data"] = '"'.$this->data.'"';
    //     return $cols;
    // }