<?php
namespace DBO;

abstract class DBO {
    protected $db;

    protected $table_name;
    protected $id;
    protected $type;
    protected $fk;
    protected $relations; 

    public function __construct($db) {
        $this->db = $db;
    }

    // Security Access
    public function allowAccess($userId, $userType, $itemId, $method) {
        $sql =  "SELECT user".
                " FROM ".$this->table_name.
                " WHERE ".$this->table_name." = ".$itemId;
        // var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            return ($row['user'] == $userId);
        }
        return false;
    }

    // CRUD Operations

    // CREATE
    public function create() {
        $keys = $this->getKeys();
        $values = implode(",",$this->getSQL());
        $sql =  "INSERT INTO ".$this->table_name.
                " (".$keys.") values (".$values.');';
        // var_export($sql);
        $stmt = $this->db->exec($sql);
        return $this->readId();
    }

    // READ
    public function read() {
        $sql =  "SELECT ".$this->getKeys().
                " FROM ".$this->table_name.
                " WHERE ".$this->table_name." = '".$this->id."';";
        // var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            $this->set($row);
        }
        return $this->get();
    }
    
    public function readByFK($k,$v) {
        $sql =  "SELECT ".$this->table_name.','.$this->getKeys().
                " FROM ".$this->table_name.
                " WHERE ".$k." = '".$v."';";
        // var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            $this->setId($row[$this->table_name]);
            $this->set($row);
        }
        return $this->get();
    }

    public function readId() {
        $sql = "select @@IDENTITY as id;";
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            $this->id = $row["id"];
        }
        return $this->id;
    }

    // UPDATE
    public function update() {
        $setArray = array();
        $updateCols = $this->removeFK($this->get());
        foreach($updateCols as $k => $v) {
            array_push($setArray, $k." = ".$v);
        }
        $set = implode(",",$setArray);
        return $this->updateSQL($set);
    }

    protected function updateSQL($set) {
        $sql = "UPDATE ".$this->table_name.
               " SET ".$set.
               " WHERE ".$this->table_name." = '".$this->id."';";
        // var_export($sql);
        $stmt = $this->db->exec($sql);
        return ($stmt > 0);
    }

    // DELETE
    public function delete() {
        $sql = "DELETE FROM ".$this->table_name.
               " WHERE ".$this->table_name." = '".$this->id."';";
        $stmt = $this->db->exec($sql);
        // var_export($sql);
        return ($stmt > 0);
    }

    // Relational DB Operations

    // retorna todas as variaveis publicas do objeto
    public function get($sql = false) {
        $r = new \ReflectionClass($this);
        $props = $r->getProperties(\ReflectionProperty::IS_PUBLIC);
        
        $cols = array();
        foreach ($props as $p) {
            $k = $p->getName();
            if ($sql) $cols[$k] = $this->$k;
            else if (isset($this->$k)) $cols[$k] = $this->$k;
        }

        return $this->removeFK($cols);
    }
    public function set($info) {
        foreach($info as $k => $v) {
            $this->$k = $v;
        }
    }

    public function getFKId () {
        $fk = implode(",",$this->fk);
        $sql = "SELECT ".$fk.
            " FROM ".$this->table_name.
            " WHERE ".$this->table_name." = ".$this->id;
        var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            foreach ($row as $k => $v) {
                $data[$k] = $v;
            }
        }
        return $data;
    }

    protected function getSQL() {
        $cols = array();
        foreach($this->get() as $k => $v) {
            $cols[$k] = '"'.$v.'"';
        }
        return $cols;
    }
    
    protected function getKeys() {
        $keys = array_keys($this->get(true));
        return implode(",",$keys);
    }

    protected function removeFK($cols) {
        if ($this->fk == null) return $cols;
        foreach($this->fk as $fk) {
            if (array_key_exists($fk,$cols)) unset($cols[$fk]);
        }
        return $cols;
    }
    
    // Getters and Setters
    protected function getTableName() {
        return $this->table_name;
    }
    protected function setTableName($tn) {
        $this->table_name = $tn;
    }

    public function getType() {
        return $this->type;
    }
    protected function setType($type) {
        $this->type = $type;
    }

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    protected function getFK() {
        return $this->fk;
    }
    protected function setFK($fk) {
        $this->fk = $fk;
    }

    public function getRelations() {
        return $this->relations;
    }
    public function setRelations($relations) {
        $this->relations = $relations;
    }

    // Helper Functions 

    // Format data
    public function formatDate($date) {
        if ($date) {
            $format = explode("/", $date);
            if (sizeof($format) < 2) {
                $format = explode("-", $date);                
                $newDate = $format[2]."/".$format[1]."/".$format[0];
            } else {
                $newDate = $format[2]."-".$format[1]."-".$format[0];
            }
        } else {
            $newDate = $date;
        }
        return $newDate;
    }
     
}



//  BACKUP

    // protected function getTablesFK($fk) {
    //     $sql =  "SELECT ".$fk.
    //             " FROM ".$fk.
    //             " WHERE ".$this->table_name." = ".$this->id;
    //     // var_export($sql);
    //     $stmt = $this->db->query($sql);
    //     $data = array();
    //     while ($row = $stmt->fetch()) {
    //         $dbo = $this->controller->{$fk}();
    //         foreach ($row as $v) {
    //             // var_export($row);              
    //             $data[] = array(
    //                 "type" => $dbo->getType(),
    //                 "id" => $v
    //             );
    //         }
    //         $response = array(
    //             "data" => $data
    //         );
    //     }
    //     return $response;
    // }
