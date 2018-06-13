<?php
namespace DBO;

use \DBO\EmprestimoDBO;

class OportunidadeDBO extends EmprestimoDBO {

    public function __construct($db) {
        parent::__construct($db);
    }

    // CREATE
    public function create($info) { return null; }

    // READ
    public function read($id) {
        $this->addId($id);

        $sql = "SELECT empresa.nome_fantasia as nome, ".$this->getColKeys().
            " FROM ".$this->table_name.
            " LEFT JOIN empresa using (empresa)".
            " WHERE ".$this->table_name." = ".$this->id.
            " AND status = 0";
        // var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            $this->readCol($row);
        }
        
        return $this->getCol();
    }

    public function getAttributes() {
        $attrib = $this->read($this->id);
        // var_export($attrib);
        unset($attrib['empresa']);
        
        return $attrib;
    }

    public function fixPrice(&$attrib) {
        $attrib['taxa'] = filter_var($this->price->calcularTaxa($attrib['rating']) * 1.30,FILTER_SANITIZE_STRING);
        $attrib['valor_parcela'] = $this->price->calcularParcela($attrib['valor'],$attrib['prazo'],$attrib['taxa']);
    }

    public function readAll() {
        $sql = "SELECT empresa.nome_fantasia as nome,".$this->table_name.", ".$this->getColKeys().
            " FROM ".$this->table_name.
            " LEFT JOIN empresa using (empresa)".
            " WHERE status = 0";
        // var_export($sql);
        $stmt = $this->db->query($sql);
        $result = array();
        while ($row = $stmt->fetch()) {
            // $this->readCol($row);
            $this->fixPrice($row);

            array_push($result,$row);
        }
        
        return $result;
    }

    // UPDATE
    public function update($id, $set) { return null; }

    public function delete($id) { return null; }


    public function allowAccess($userId,$type,$id,$method) {
        return ($type == "investidor");      
    }

}