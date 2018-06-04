<?php
namespace Service;

use \DBO\EnderecoDBO as Endereco;
use \DBO\ContaBancariaDBO as ContaBancaria;
use \DBO\InvestidorDBO as Investidor;
use \DBO\InvestimentoDBO as Investimento;


class InvestidorService {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function validarUser($userId, $id) {
        $investidor = new Investidor($this->db);
        $user = $investidor->readUserId($id);

        return ($user == $userId);
    }

    public function validarInvestimentos($id) {
        $investidor = new Investimento($this->db);
        return  $investidor->readTemInvestimentos($id);
    }

    // CREATE

    // cria um novo investidor
    // retorna o id do investidor
    public function createInvestidor($uid, $data) {

        $this->db->beginTransaction();
        try {

            $endereco = new Endereco($this->db);
            $endereco_id = $endereco->create($data['endereco']);

            $conta_bancaria = new ContaBancaria($this->db);
            $conta_bancaria_id = $conta_bancaria->create($data['conta_bancaria']);

            $data['endereco'] = $endereco_id;
            $data['conta_bancaria'] = $conta_bancaria_id;
            $data['user'] = $uid;

            $investidor = new Investidor($this->db);
            $investidor_id = $investidor->create($data);

            $this->db->commit();

            $response = array(
                "type" => "investidor",
                "id" => $investidor_id
            );
        } catch(Exception $e) {
            $this->db->rollBack();
            $response = null;
        } catch(PDOException $e) {
            $this->db->rollBack();
            $response = null;
        }
        return $response;        
    }

    // recebe o id do investidor
    // cria um novo investimento para o investidor
    public function createInvestimento($id) {


        // TODO !!


        $this->db->beginTransaction();
        try {
            $investimento = new Investimento($this->db);
            $investimento_id = $investimento->create($data);

            
            $data['endereco'] = $endereco_id;
            $data['conta_bancaria'] = $conta_bancaria_id;
            $data['user'] = $uid;

            $this->db->commit();

            $response = array(
                "type" => "investidor",
                "id" => $investidor_id
            );
        } catch(Exception $e) {
            $this->db->rollBack();
            $response = null;
        } catch(PDOException $e) {
            $this->db->rollBack();
            $response = null;
        }
        return $response;    
    }

    // READ

    // return todos os investidores da plataforma
    public function getInvestidor() {
        
    }

    // recebe o id do investidor
    // return as infos:
    //    - perfil do investidor
    //    - resumo da carteira
    public function getInvestidorById($id) {
        $investidor = new Investidor($this->db);
        $data = $investidor->read($id);
        
        $endereco = new Endereco($this->db);
        $data['endereco'] = $endereco->read($data['endereco']);
        
        $conta_bancaria = new ContaBancaria($this->db);            
        $data['conta_bancaria'] = $conta_bancaria->read($data['conta_bancaria']);

        $response = array(
            "type" => "investidor",
            "id" => $id,
            "attributes" => $data
        );

        return $response;  
    }

    // recebe id do investidor
    // return:
    //  - todas as parcelas recebidas de investimentos feitos pelo investidor
    //  - Investimento??
    public function getMovimentacao($id) {
        
    }

    
    // recebe id do investidor e periodo a ser filtrado
    // return:
    //  - todas as parcelas desse periodo recebidas de investimentos feitos pelo investidor
    //  - todo o investimento realizado
    public function getMovimentacaoFiltro($id, $periodo) {
        
    }

    // recebe id do investidor e id do investimento
    // return:
    //  - info do investimento
    //  - parcelas do investimento
    public function getInvestimentoById($id,$invid) {

    }

    // recebe id do investidor
    // return:
    //  - todos os investimentos do investidor
    public function getInvestimentos($id) {

    }

    // recebe id do investidor
    // return:
    //  - todos as notificacoes do investidor
    public function getNotificacoes($id) {

    }

    // UPDATE

    // recebe o id do investidor
    // atualiza as infos do investidor
    public function updateInvestidor($id,$data) {
        $this->db->beginTransaction();
        try {
            $investidor = new Investidor($this->db);
            $fk = $investidor->readFK($id);

            $endereco = new Endereco($this->db);
            $eUpd = $endereco->updateAll($fk['endereco'],$data['endereco']);
            
            $conta_bancaria = new ContaBancaria($this->db);            
            $cbUpd = $conta_bancaria->updateAll($fk['conta_bancaria'],$data['conta_bancaria']);

            unset($data['endereco']);
            unset($data['conta_bancaria']);            
            
            $iUpd = $investidor->updateAll($id,$data);
            
            $this->db->commit();
            $response = array(
                "type" => "investidor",
                "id" => $id,
                "attributes" => array (
                    "user" => $fk['user']
                )
            );
        } catch(Exception $e) {
            $this->db->rollBack();
            $response = null;
        }
        return $response;        
    }

    // DELETE

    // recebe o id do investidor
    // deleta o investidor dependendo dos status dos seus investimentos
    public function deleteInvestidor($id) {
        $this->db->beginTransaction();
        try {
            $investidor = new Investidor($this->db);
            $fk = $investidor->readFK($id);
            $endereco = new Endereco($this->db);
            $eDel = $endereco->delete($fk['endereco']);
            $conta_bancaria = new ContaBancaria($this->db);            
            $cbDel = $conta_bancaria->delete($fk['conta_bancaria']);
            $iDel = $investidor->delete($id);
            $this->db->commit();
            $response = array(
                "type" => "investidor",
                "id" => $id,
                "attributes" => array (
                    "user" => $fk['user']
                )
            );
        } catch(Exception $e) {
            $this->db->rollBack();
            $response = null;
        }
        return $response;        
    }

    // recebe o id do investidor e o id do investimento
    // delete o investimento feito pelo investidor (depende do status)
    public function deleteInvestimento($id, $invid) {
        
    }
}