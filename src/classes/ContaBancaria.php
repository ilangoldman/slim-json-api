<?php
class ContaBancaria extends DBO {
    private $table_name = "conta_bancaria";
    
    // CREATE

    public function createContaBancaria($info) {
        $titular = '"'.filter_var($info['titular'],FILTER_SANITIZE_STRING).'"';
        $banco = filter_var($info['banco'],FILTER_SANITIZE_NUMBER_INT);
        $tipo = filter_var($info['tipo'],FILTER_SANITIZE_NUMBER_INT);
        $agencia = filter_var($info['agencia'],FILTER_SANITIZE_NUMBER_INT);
        $conta = filter_var($info['conta'],FILTER_SANITIZE_NUMBER_INT);
                
        $sql = "INSERT INTO ".$this->table_name.
               " (titular,banco,tipo,agencia,conta) values (".
               $titular.",".$banco.",".$tipo.",".$agencia.",".$conta.");";
        $stmt = $this->db->exec($sql);

        $sql = "SELECT ".$this->table_name." FROM ".$this->table_name.
               " WHERE titular = ".$titular.
               " AND banco = ".$banco.
               " AND tipo = ".$tipo.
               " AND agencia = ".$agencia.
               " AND conta = ".$conta;
        $stmt = $this->db->query($sql);
        
        if ($row = $stmt->fetch()) {
            extract($row);
        }

        return $conta_bancaria;
    }

}
    