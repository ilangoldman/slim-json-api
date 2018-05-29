<?php
class Investimento {

    // Connection instance
    private $connection;

    // table name
    private $table_name = "investimento";

    // table columns
    public $id;
    public $name;
    public $createdAt; 
    public $updatedAt;

    public function __construct($connection){
        $this->connection = $connection;
    }

    //C
    public function create(){
    }
    //R
    public function read(){
        $query = "SELECT i.id, i.name, i.createdAt, i.updatedAt FROM " . $this->table_name . " i ORDER BY i.createdAt DESC";
        
        $stmt = $this->connection->prepare($query);

        $stmt->execute();

        return $stmt;
    }
    //U
    public function update(){}
    //D
    public function delete(){}
}