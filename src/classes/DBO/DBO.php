<?php
namespace DBO;

abstract class DBO {
    protected $db;
    protected $table_name;
    protected $id;

    public function __construct($db) {
        $this->db = $db;
    }

    // helper functions

    abstract protected function addCol($info);
    abstract protected function getCol();
    abstract protected function getSqlCol();    

    protected function getColKeys() {
        $arrayKeys = array_keys($this->getSqlCol());
        return implode(",",$arrayKeys);
    }

    protected function addId($id) {
        $this->id = $id;
    }

    protected function getId() {
        return $this->id;
    }

    protected function setTableName($tn) {
        $this->table_name = $tn;
    }

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

    // CREATE
    // needs to be implented on child class
    public function create($info) {
        $this->addCol($info);
        
        $values = implode(",",$this->getSqlCol());
        $sql = "INSERT INTO ".$this->table_name.
        " (".$this->getColKeys().")".
        " values (".$values.');';
        // var_export($sql);
        $stmt = $this->db->exec($sql);
        return $this->readId();
    }

    // READ
    public function read($id) {
        $this->addId($id);

        $sql = "SELECT ".$this->getColKeys().
            " FROM ".$this->table_name.
            " WHERE ".$this->table_name." = ".$this->id;

        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            $this->addCol($row);
        }
        
        return $this->getCol();
    }

    public function readId() {
        $sql = "select @@IDENTITY as id;";
        $stmt = $this->db->query($sql);
        if ($id = $stmt->fetch()) {
            $this->addId($id["id"]);
        }
        return $this->getId();
    }

    // UPDATE
    public function updateAll($id,$info) {
        $this->addCol($info);

        $setArray = array();
        // $array = $this->getCol();
        foreach($this->getSqlCol() as $k => $v) {
            if (str_replace('"','',$v) == '') continue;
            array_push($setArray, $k." = ".$v);
        }
       
        $set = implode(",",$setArray);
        return $this->update($id,$set);
    }

    protected function update($id,$set) {
        $this->addId($id);
        $sql = "UPDATE ".$this->table_name.
               " SET ".$set.
               " WHERE ".$this->table_name." = ".$id.";";
        // var_export($sql);
        $stmt = $this->db->exec($sql);
        return ($stmt > 0);
    }

    // DELETE
    public function delete($id) {
        $this->addId($id);
        $sql = "DELETE FROM ".$this->table_name.
               " WHERE ".$this->table_name." = ".$id.";";
        $stmt = $this->db->exec($sql);

        return ($stmt > 0);
    }
}