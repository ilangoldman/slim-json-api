<?php
class Endereco extends DBO {
    private $table_name = "endereco";
    
    // CREATE

    public function createEndereco($info) {
        $cep = filter_var($info['cep'],FILTER_SANITIZE_NUMBER_INT);
        $tipo = '"'.filter_var($info['tipo'],FILTER_SANITIZE_STRING).'"';
        $logradouro = '"'.filter_var($info['logradouro'],FILTER_SANITIZE_STRING).'"';
        $numero = filter_var($info['numero'],FILTER_SANITIZE_NUMBER_INT);
        $complemento = '"'.filter_var($info['complemento'],FILTER_SANITIZE_STRING).'"';
        $bairro = '"'.filter_var($info['bairro'],FILTER_SANITIZE_STRING).'"';
        $cidade = '"'.filter_var($info['cidade'],FILTER_SANITIZE_STRING).'"';
        $estado = '"'.filter_var($info['estado'],FILTER_SANITIZE_STRING).'"';
        $pais =  '"'.filter_var($info['pais'],FILTER_SANITIZE_STRING).'"';
                
        $sql = "INSERT INTO ".$this->table_name.
               " (cep,tipo,logradouro,numero,complemento,bairro,cidade,estado,pais) values (".
               $cep.",".$tipo.",".$logradouro.",".$numero.",".$complemento.",".$bairro.",".$cidade.",".$estado.",".$pais.");";
        $stmt = $this->db->exec($sql);

        $sql = "SELECT ".$this->table_name." FROM ".$this->table_name.
               " WHERE cep = ".$cep.
               " AND tipo = ".$tipo.
               " AND logradouro = ".$logradouro.
               " AND numero = ".$numero.
               " AND complemento = ".$complemento.
               " AND bairro = ".$bairro.
               " AND cidade = ".$cidade.
               " AND estado = ".$estado.
               " AND pais = ".$pais;
        $stmt = $this->db->query($sql);
        
        if ($row = $stmt->fetch()) {
            extract($row);
        }

        return $endereco;
    }

}
    