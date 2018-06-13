<?php
namespace DBO;

use \Service\DBOController as DBOController;

abstract class DBO {
    protected $db;
    protected $controller;
    
    protected $table_name;
    protected $id;
    protected $type;
    protected $fk;    

    public function __construct($db) {
        $this->db = $db;
        $this->controller = new DBOController($this->db);
    }

    public function allowAccess($userId,$type,$id,$method) {
        $sql = "SELECT ".$type." FROM ".$this->table_name.
               " WHERE ".$this->table_name." = ".$id;
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            return ($row[$type] == $userId);
        }
        return false;
    }

    // API functions

    protected function setType($type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function getAttributes() {
        return $this->read($this->id);
    }

    public function getRelationships() {
        return null;
    }

    protected function getTablesFK($fk) {
        $sql = "SELECT ".$fk.
            " FROM ".$fk.
            " WHERE ".$this->table_name." = ".$this->id;
        
        // var_export($sql);
        $stmt = $this->db->query($sql);
        $data = array();
        while ($row = $stmt->fetch()) {
            $dbo = $this->controller->{$fk}();
            foreach ($row as $v) {
                // var_export($row);              
                $data[] = array(
                    "type" => $dbo->getType(),
                    "id" => $v
                );
            }

            $response = array(
                "data" => $data
            );
        }

        // var_export($response);
        return $response;
    }


    // helper functions

    abstract protected function addCol($info);
    protected function readCol($info) {
        $this->addCol($info);
    }

    abstract protected function getCol();
    abstract protected function getSqlCol();

    protected function getSqlColKeys() {
        $arrayKeys = array_keys($this->getSqlCol());
        return implode(",",$arrayKeys);
    }

    protected function getColKeys() {
        $arrayKeys = array_keys($this->getCol());
        return implode(",",$arrayKeys);
    }

    protected function removeFK($cols) {
        // var_export($cols);
        // var_export($this->fk);
        
        // if (!isset($this->fk)) return $cols;
        

        foreach($this->fk as $fk) {
            if (array_key_exists($fk,$cols)) unset($cols[$fk]);
        }
        return $cols;
    }

    protected function addFK($fkArray) {
        $this->fk = $fkArray;
    }

    protected function getFK() {
        return $this->fk;
    }

    public function setId($id) {
        $this->addId($id);
    }

    protected function addId($id) {
        $this->id = $id;
    }

    protected function getId() {
        return $this->id;
    }

    protected function getTableName() {
        return $this->table_name;
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
        " (".$this->getSqlColKeys().")".
        " values (".$values.');';
        var_export($sql);
        $stmt = $this->db->exec($sql);
        return $this->readId();
    }

    // READ
    public function read($id) {
        $this->addId($id);

        $sql = "SELECT ".$this->getColKeys().
            " FROM ".$this->table_name.
            " WHERE ".$this->table_name." = ".$this->id;
        // var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            $this->readCol($row);
            // $this->addCol($row);
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
        $updateCols = $this->removeFK($this->getSqlCol());
        // var_export($updateCols);
        foreach($updateCols as $k => $v) {
            // var_export($k."=".$v);
            // var_export(empty($v));
            // var_export(str_replace('"','',$v) == '');
            if (str_replace('"','',$v) == '') continue;
            array_push($setArray, $k." = ".$v);
        }
        // var_export($sql);
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