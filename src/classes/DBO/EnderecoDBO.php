<?php
namespace DBO;

class EnderecoDBO extends DBO {
    private $criado;
    private $modificado;

    private $cep; 
    private $tipo; 
    private $logradouro; 
    private $numero; 
    private $complemento; 
    private $bairro; 
    private $cidade; 
    private $estado; 
    private $pais;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("endereco");
    }
    
    // helpers

    protected function addCol($info) {
        $this->cep = filter_var($info['cep'],FILTER_SANITIZE_NUMBER_INT);
        $this->tipo = filter_var($info['tipo'],FILTER_SANITIZE_STRING);
        $this->logradouro = filter_var($info['logradouro'],FILTER_SANITIZE_STRING);
        $this->numero = filter_var($info['numero'],FILTER_SANITIZE_NUMBER_INT);
        $this->complemento = filter_var($info['complemento'],FILTER_SANITIZE_STRING);
        $this->bairro = filter_var($info['bairro'],FILTER_SANITIZE_STRING);
        $this->cidade = filter_var($info['cidade'],FILTER_SANITIZE_STRING);
        $this->estado = filter_var($info['estado'],FILTER_SANITIZE_STRING);
        $this->pais =  filter_var($info['pais'],FILTER_SANITIZE_STRING);
    }

    protected function getCol() {
        return array(
            "cep" => $this->cep,
            "tipo" => $this->tipo,
            "logradouro" => $this->logradouro, 
            "numero" => $this->numero, 
            "complemento" => $this->complemento, 
            "bairro" => $this->bairro, 
            "cidade" => $this->cidade,
            "estado" => $this->estado,
            "pais" => $this->pais
        );
    }

    protected function getSqlCol() {
        return array(
            "cep" => $this->cep,
            "tipo" => '"'.$this->tipo.'"',
            "logradouro" => '"'.$this->logradouro.'"', 
            "numero" => $this->numero, 
            "complemento" => '"'.$this->complemento.'"', 
            "bairro" => '"'.$this->bairro.'"', 
            "cidade" => '"'.$this->cidade.'"',
            "estado" => '"'.$this->estado.'"',
            "pais" => '"'.$this->pais.'"'
        );
    }

    // CREATE

    // READ
  

    // UPDATE


}