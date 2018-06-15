<?php
namespace DBO\Users;
use \DBO\DBO;

class AmigoDBO extends DBO {
    private $criado;

    // quem convidou
    private $investidor;
    private $empresa;

    private $nome;
    private $email;
    private $status; 

    // user do amigo
    private $user;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("amigo");
        $this->setType("amigo");
    }
    
    // helpers

    protected function addCol($info) {
        $this->investidor = $info['investidor'];
        $this->empresa = $info['empresa'];

        $this->nome = $info['nome'];        
        $this->email = $info['email'];                
        $this->status = $info['status'];
        $this->user = $info['user'] ?? null;
    }

    protected function getCol() {
        return array(
            "investidor" => $this->investidor,
            "empresa" => $this->empresa,

            "nome" => $this->nome,
            "email" => $this->email,
            "status" => $this->status,            
            "user" => $this->user
        );
    }

    protected function getSqlCol() {
        $cols = $this->getCol();
        $cols["nome"] = '"'.$this->nome.'"';
        $cols["email"] = '"'.$this->email.'"';
        $cols["user"] = $cols['user'] ?? 'null';  
        return $cols;
    }

    public function getRelationships() {
        $sql = "SELECT investidor, empresa".
            " FROM ".$this->table_name.
            " WHERE ".$this->table_name." = ".$this->id;
        // var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            foreach ($row as $k => $v) {
                // var_export($k."|".$v);                
                $dbo = $this->controller->{$k}();
                $response[$dbo->getType()] = array(
                        "data" => array(
                            "type" => $dbo->getType(),
                            "id" => $v
                    )
                );
            }
        }
    }

    public function create($info) {
        $info['status'] = 0;
        return parent::create($info);
    }

    public function updateAll($id,$info) {
        $info['status'] = 1;
        return parent::updateAll($id,$info);
    }

}