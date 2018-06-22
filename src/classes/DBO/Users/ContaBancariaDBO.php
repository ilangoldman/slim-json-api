<?php
namespace DBO\Users;
use \DBO\DBO;

class ContaBancariaDBO extends DBO {
    private $criado;
    private $modificado;

    public $user;

    public $titular; 
    public $banco; 
    public $tipo; 
    public $agencia; 
    public $conta;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("conta_bancaria");
        $this->setType("conta_bancaria");
        $this->setFK(["user"]);    
    }

}



// [DEPRECATED]


//   // helper
    
//     protected function setCol($info) {
//         $this->user = $info['user'] ?? null;

//         $this->titular = filter_var($info['titular'],FILTER_SANITIZE_STRING);
//         $this->banco = filter_var($info['banco'],FILTER_SANITIZE_NUMBER_INT);
//         $this->tipo = filter_var($info['tipo'],FILTER_SANITIZE_NUMBER_INT);
//         $this->agencia = filter_var($info['agencia'],FILTER_SANITIZE_NUMBER_INT);
//         $this->conta = filter_var($info['conta'],FILTER_SANITIZE_NUMBER_INT);
//     }

//     protected function getCol() {
//         return array(
//             "user" => $this->user,
            
//             "titular" => $this->titular,
//             "banco" => $this->banco,
//             "tipo" => $this->tipo, 
//             "agencia" => $this->agencia, 
//             "conta" => $this->conta
//         );
//     }

//     protected function getSqlCol() {
//         $cols = $this->getCol();
//         $cols["user"] = $this->user;
//         $cols["titular"] = '"'.$this->titular.'"';
//         return $cols;
//     }

//     // public function getAttributes() {
//     //     $cols = $this->read($this->id);
//     //     unset($cols["user"]);
//     //     return $cols;
//     // }