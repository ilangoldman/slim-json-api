<?php
namespace DBO;

class AmigoDBO extends DBO {
    private $criado;

    private $investidor;
    private $empresa;

    private $nome;
    private $email;
    private $status; 
    private $user;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("amigo");
    }
    
    // helpers

    protected function addCol($info) {
        $this->investidor = $info['investidor'];
        $this->empresa = $info['empresa'];

        $this->nome = $info['nome'];        
        $this->email = $info['email'];                
        $this->status = $info['status'];
        $this->user = $info['user'];
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
        return array(
            "investidor" => $this->investidor,
            "empresa" => $this->empresa,

            "nome" => '"'.$this->nome.'"',
            "email" => '"'.$this->email.'"',
            "status" => $this->status,            
            "user" => $this->user    
        );
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

    // CREATE

    // READ
  

    // UPDATE


}