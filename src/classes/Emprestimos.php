<?php
class Emprestimos extends DBO {
    // table name
    private $table_name = "emprestimo";

    //C
    public function createEmprestimo($eid, $dados){

    }

    // READ STATEMENTS
    public function getEmprestimos(){
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

    public function getDetalhesEmprestimoById($id){
        $sql = "SELECT e.empid, empresa.nome, e.valor, e.taxa, e.prazo, e.status, e.parcelas, e.valorParcela, ie.descricao, ie.motivo FROM " . $this->table_name . " AS e LEFT JOIN empresa ON empresa.eid = e.empresa_eid LEFT JOIN infoemprestimo as ie ON ie.emprestimos_empid = e.empid WHERE e.empid = " . $id." ORDER BY e.criado DESC";
        
        $stmt = $this->db->query($sql);


        // tirar isso de dentro da classe e colocar nos routes
        // aqui monta os dados especificos
        $data = array();
        $data["type"] = "emprestimos";
        $data["id"] = $id;
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
                "valorParcela" => $valorParcela,
                "descricao" => $descricao,
                "motivo" => $motivo
            );
            array_push($data["attributes"], $i);
        }
    
        return $data;
    }

    //U
    public function updateEmprestimo($id) {

    }

    //D
    public function deleteEmprestimo($id) {

    }
}