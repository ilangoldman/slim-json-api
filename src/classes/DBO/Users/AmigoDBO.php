<?php
namespace DBO\Users;
use \DBO\DBO;

class AmigoDBO extends DBO {
    private $criado;

    // quem convidou
    public $user;

    public $nome;
    public $email;
    public $status; 

    // user do amigo
    public $convidado;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("amigo");
        $this->setType("amigo");
        $this->setFK(["user"]);
        $this->setRelations(["convidado"]);
    }
    
}


// [DEPRECATED]

    // // helpers

    // protected function setCol($info) {
    //     $this->user = $info['user'];

    //     $this->nome = $info['nome'];        
    //     $this->email = $info['email'];                
    //     $this->status = $info['status'];
    //     $this->convidado = $info['convidado'] ?? null;
    // }

    // protected function getCol() {
    //     return array(
    //         "user" => $this->user,

    //         "nome" => $this->nome,
    //         "email" => $this->email,
    //         "status" => $this->status,            
    //         "convidado" => $this->convidado
    //     );
    // }

    // protected function getSqlCol() {
    //     $cols = $this->getCol();
    //     $cols["nome"] = '"'.$this->nome.'"';
    //     $cols["email"] = '"'.$this->email.'"';
    //     $cols["convidado"] = $cols['convidado'] ?? 'null';  
    //     return $cols;
    // }

    // public function getRelationships() {
    //     $sql =  "SELECT convidado".
    //             " FROM ".$this->table_name.
    //             " WHERE ".$this->table_name." = ".$this->id;
    //     // var_export($sql);
    //     $stmt = $this->db->query($sql);
    //     if ($row = $stmt->fetch()) {
    //         foreach ($row as $k => $v) {
    //             // var_export($k."|".$v);                
    //             $dbo = $this->controller->{$k}();
    //             $response[$dbo->getType()] = array(
    //                 "data" => array(
    //                     "type" => $dbo->getType(),
    //                     "id" => $v
    //                 )
    //             );
    //         }
    //     }
    // }

    // public function create($info) {
    //     $info['status'] = 0;
    //     return parent::create($info);
    // }

    // public function updateAll($id,$info) {
    //     $info['status'] = 1;
    //     return parent::updateAll($id,$info);
    // }
