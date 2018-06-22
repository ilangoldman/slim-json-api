<?php
namespace Service;

use \Service\DBOController as DBOController;

use \DBO\EnderecoDBO as Endereco;
use \DBO\ContaBancariaDBO as ContaBancaria;
use \DBO\EmpresaDBO as Empresa;
use \DBO\EmprestimoDBO as Emprestimo;


class EmpresaService {
    private $db;
    private $controller;

    public function __construct($db) {
        $this->db = $db;
        $this->controller = new DBOController($this->db);        
    }
    
    public function validarUser($userId, $id) {
        return $this->controller->validarUser($userId,$id,'empresa');
    }

    public function validarEmprestimo($id) {
        $emprestimo = new Emprestimo($this->db);
        return  $emprestimo->readTemEmprestimo($id);
    }

    
    // CREATE

    // cria uma nova empresa
    // retorna o id da empresa
    public function createEmpresa($uid, $data) {

        $this->db->beginTransaction();
        try {
            $endereco = new Endereco($this->db);
            $endereco_id = $endereco->create($data['endereco']);

            $conta_bancaria = new ContaBancaria($this->db);
            $conta_bancaria_id = $conta_bancaria->create($data['conta_bancaria']);

            $data['endereco'] = $endereco_id;
            $data['conta_bancaria'] = $conta_bancaria_id;
            $data['user'] = $uid;

            $empresa = new Empresa($this->db);
            $empresa_id = $empresa->create($data);

            $this->db->commit();

            $response = $this->getEmpresaById($empresa_id);

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

    public function getEmpresaById($id) {
        $dbo = new Empresa($this->db);
        return $this->controller->getJSONAPI($dbo,$id);
    }

    // UPDATE

    // recebe o id do investidor
    // atualiza as infos do investidor
    public function updateEmpresa($id,$data) {
        $this->db->beginTransaction();
        try {
            $empresa = new Empresa($this->db);
            $fk = $empresa->readFK($id);

            if (isset($data['endereco'])) {
                $endereco = new Endereco($this->db);
                $eUpd = $endereco->updateAll($fk['endereco'],$data['endereco']);
                unset($data['endereco']);
            }

            if (isset($data['conta_bancaria'])) {
                $conta_bancaria = new ContaBancaria($this->db); 
                $cbUpd = $conta_bancaria->updateAll($fk['conta_bancaria'],$data['conta_bancaria']);
                unset($data['conta_bancaria']);
            }
            
            $iUpd = $empresa->updateAll($id,$data);
            
            $this->db->commit();
            $response = $this->getEmpresaById($id);
        } catch(Exception $e) {
            $this->db->rollBack();
            $response = null;
        }
        return $response;        
    }

    // DELETE

    // recebe o id do investidor
    // deleta o investidor dependendo dos status dos seus investimentos
    public function deleteEmpresa($id) {
        $this->db->beginTransaction();
        try {
            $empresa = new Empresa($this->db);
            $fk = $empresa->readFK($id);

            $endereco = new Endereco($this->db);
            $eDel = $endereco->delete($fk['endereco']);

            $conta_bancaria = new ContaBancaria($this->db);            
            $cbDel = $conta_bancaria->delete($fk['conta_bancaria']);

            $iDel = $empresa->delete($id);

            $this->db->commit();
            $response = array(
                "type" => "empresa",
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