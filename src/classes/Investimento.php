<?php
class Investimento extends DBO {
    // table name
    private $table_name = "investimento";

    // CREATE

    public function createInvestimento($uid, $oferta, $empid) {

    }

    // READ
    
    public function getConfirmarInvestir($empid) {

    }

    public function getInvestimentos($uid) {
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
    
        $response = array(
            "data" => $data
        );

        return $response;
    }

    //U
    public function updateInvestimento($id) {

    }

    //D
    public function deleteInvestimento($id) {

    }
}