<?php
namespace Service;

use \Service\DBOController as DBOController;

use \DBO\EnderecoDBO as Endereco;
use \DBO\ContaBancariaDBO as ContaBancaria;
use \DBO\InvestidorDBO as Investidor;
use \DBO\InvestimentoDBO as Investimento;


class InvestidorService {
    private $db;
    private $controller;

    public function __construct($db) {
        $this->db = $db;
        $this->controller = new DBOController($this->db);        
    }

    public function validarUser($userId, $id) {
        return $this->controller->validarUser($userId,$id,'investidor');
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

            $response = $this->getInvestidorById($investidor_id);
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

    public function getIncludes($relationships) {
        return $this->controller->getIncludes($relationships);
    }

    // recebe o id do investidor
    // return as infos:
    //    - perfil do investidor
    //    - resumo da carteira
    public function getInvestidorById($id) {
        $dbo = new Investidor($this->db);
        return $this->controller->getJSONAPI($dbo,$id);
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
            $response = $this->getInvestidorById($id);
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

}