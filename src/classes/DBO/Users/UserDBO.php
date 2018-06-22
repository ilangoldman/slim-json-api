<?php
namespace DBO\Users;
use \DBO\DBO;

class UserDBO extends DBO {
    private $criado;
    private $userTipo;

    public $uid;
    public $tipo;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("user");
        $this->setType("user");
        $this->setRelations(["endereco","conta_bancaria","notificacao","amigo"]);

        $this->userTipo = array(
            0 => 'admin',
            1 => 'empresa',
            2 => 'investidor'
        );
    }

    // OVERRIDE
    // public function get() {
    //     $cols = parent::get();
    //     $cols['tipo'] = $this->tipoId2Str($cols['tipo']);
    //     return $cols;
    // }

    // public function read() {
    //     $sql =  "SELECT ".$this->table_name.",".$this->getKeys().
    //             " FROM ".$this->table_name.
    //             " WHERE uid = ".$this->uid;
    //     // var_export($sql);
    //     $stmt = $this->db->query($sql);
    //     if ($row = $stmt->fetch()) {
    //         $this->set($row);
    //     }
    //     return $this->get();
    // }

    // getter and setter
    public function getTipo() {
        return $this->tipoId2Str($this->tipo);
    }
    public function setTipoByKey($tipo) {
        $this->tipo = $this->tipoStr2Id($tipo);
    }
    public function setTipoById($tipo) {
        $this->tipo = $tipo;
    }

    public function tipoId2Str($tipo) {
        return $this->userTipo[$tipo];
    }
    private function tipoStr2Id($tipo) {
        return array_search($tipo, $this->userTipo);
    }

    public function getUID() {
        return $this->uid;
    }
    public function setUID($uid) {
        $this->uid = $uid;
    }

}

    // [DEPRECATED]



    // helpers

    // protected function setCol($info) {
    //     $this->uid = $info['uid'];
    //     $this->tipo = $info['tipo'];
    // }

    // protected function getCol() {
    //     return array(
    //         "uid" => $this->uid,
    //         "tipo" => $this->tipo
    //     );
    // }

    // protected function getSqlCol() {
    //     $cols = $this->getCol();
    //     $cols["uid"] = '"'.$this->uid.'"';
    //     $cols["tipo"] = '"'.$this->formatTipoByKey($cols["tipo"]).'"';
    //     return $cols;
    // }


    // public function getRelationships() {
    //     $fk = ["endereco","conta_bancaria","notificacao","amigo"];
    //     $r = array();
    //     foreach($fk as $v) {
    //         $tableFK = $this->getTablesFK($v);
    //         if ($tableFK == NULL) continue;
    //         $r[$v] = $tableFK;
    //     }
    //     return $r;
    // }

    // public function empresa() {
    //     $tableFK = $this->getTablesFK('empresa');
    //     return $tableFK['data'][0]['id'];
    // }

    // public function investidor() {
    //     $tableFK = $this->getTablesFK('investidor');
    //     // var_export($tableFK);
    //     return $tableFK['data'][0]['id'];
    // }
