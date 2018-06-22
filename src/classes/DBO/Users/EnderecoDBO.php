<?php
namespace DBO\Users;
use \DBO\DBO;

class EnderecoDBO extends DBO {
    private $criado;
    private $modificado;

    public $user;

    public $cep; 
    public $tipo; 
    public $logradouro; 
    public $numero; 
    public $complemento; 
    public $bairro; 
    public $cidade; 
    public $estado; 
    public $pais;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("endereco");
        $this->setType("endereco");
        $this->setFK(["user"]);
    }
    
   
}






// [DEPRECATED]

 // helpers

    // protected function setCol($info) {
    //     $this->user = $info['user'];

    //     $this->cep = filter_var($info['cep'],FILTER_SANITIZE_NUMBER_INT);
    //     $this->tipo = filter_var($info['tipo'],FILTER_SANITIZE_STRING);
    //     $this->logradouro = filter_var($info['logradouro'],FILTER_SANITIZE_STRING);
    //     $this->numero = filter_var($info['numero'],FILTER_SANITIZE_NUMBER_INT);
    //     $this->complemento = filter_var($info['complemento'],FILTER_SANITIZE_STRING);
    //     $this->bairro = filter_var($info['bairro'],FILTER_SANITIZE_STRING);
    //     $this->cidade = filter_var($info['cidade'],FILTER_SANITIZE_STRING);
    //     $this->estado = filter_var($info['estado'],FILTER_SANITIZE_STRING);
    //     $this->pais =  filter_var($info['pais'],FILTER_SANITIZE_STRING);
    // }

    // protected function getCol() {
    //     return array(
    //         "user" => $this->user,
    //         "cep" => $this->cep,
    //         "tipo" => $this->tipo,
    //         "logradouro" => $this->logradouro, 
    //         "numero" => $this->numero, 
    //         "complemento" => $this->complemento, 
    //         "bairro" => $this->bairro, 
    //         "cidade" => $this->cidade,
    //         "estado" => $this->estado,
    //         "pais" => $this->pais
    //     );
    // }

    // protected function getSqlCol() {
    //     $cols = $this->getCol();
    //     $cols["user"] = $this->user;
    //     $cols["tipo"] = '"'.$this->tipo.'"';
    //     $cols["logradouro"] = '"'.$this->logradouro.'"';
    //     $cols["complemento"] = '"'.$this->complemento.'"'; 
    //     $cols["bairro"] = '"'.$this->bairro.'"'; 
    //     $cols["cidade"] = '"'.$this->cidade.'"';
    //     $cols["estado"] = '"'.$this->estado.'"';
    //     $cols["pais"] = '"'.$this->pais.'"';
    //     return $cols;
    // }

    // // public function getAttributes() {
    // //     $cols = $this->read($this->id);
    // //     // unset($cols["user"]);
    // //     return $this->removeFK($cols);
    // // }
