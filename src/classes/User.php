<?php

class User extends DBO {

    
    public function getUser() {
        $sql = "SELECT e.empid, empresa.nome, e.valor, e.taxa, e.prazo, e.status, e.parcelas, e.valorParcela FROM " . $this->table_name . " AS e LEFT JOIN empresa ON empresa.eid = e.empresa_eid ORDER BY e.criado DESC";
        
        $stmt = $this->db->query($sql);

        $data = array();
        $data["type"] = "emprestimos";
        $data["id"] = "1";
        $data["attributes"] = array();
        while($row = $stmt->fetch()) {
            extract($row);
            $i  = array(
                "id" => $empid,
                "nome" => $nome,
                "valor" => $valor,
                "taxa" => $taxa,
                "prazo" => $prazo,
                "status" => $status,
                "parcelas" => $parcelas,
                "valorParcela" => $valorParcela
            );
            array_push($data["attributes"], $i);
        }
    
        return $response;
    }

}