<?php
namespace Controller;

// Controllers
use \Controller\Controller;
use \Controller\GameficationController;
// use \Controller\NotificationController;

use \View\ResourceObject;


class UserController extends Controller {
    
    // Routes Implementation

    // User Basic Routes
    public function novo($request, $response, $args) {
        // TODO
        $response = $response->withStatus(201); 
        return $response;
    }
    // public function novo($request, $response, $args) {
    //     $uid = $args['uid'];
    //     $body = $request->getParsedBody();
    //     if (!isset($body['data']['type'])) return $response->withStatus(400);
    //     $this->view->set($body);

    //     // Set the user
    //     $this->user->setTipoByKey($this->view->getData()->getType());
    //     $this->user->setUID($uid);

    //     $this->db->beginTransaction();
    //     try {
    //         if ($this->createUser() == null) {
    //             $this->db->rollBack();
    //             return $response->withStatus(403);
    //         }
    //         $url = $request->getUri()->getPath(); // . "/" . $uid;
    //         $response = $response->withStatus(201)->withHeader('location', $url);
    //         $this->logger->addInfo("Sucesso: Novo User ".$uid."|".$type." - ". $data['id']);
    //         $this->db->commit();
    //     } catch(PDOException $e) {
    //         $this->logger->addInfo("ERRO: Novo User ".$uid."|".$type.": ".$e->getMessage());
    //         $response = $response->withStatus(400);
    //         $this->db->rollBack();
    //     } 
    //     return $response;
    // }

    public function detalheUser($request, $response, $args) {
        // TODO
        $data = $this->view->getData();
        $data->setType('empresa');
        $data->setId('1');
        
        $mock = array(
            'email' => 'teste@teste.com',
            'nome' => 'Ilan',
            'sobrenome' => 'Goldman',
            'razao_social' => 'UpCash Ltda.',
            'nome_fantasia' => 'UpCash',
            'cnpj' => '2875981729',
            'telefone_comercial' => '21587489',
            'fundacao' => '12/10/1997',
            'descricao' => 'Adiantamento de Recebiveis',
            'motivo' => 'Fluxo de Caixa',
            'pagina_web' => 'upcash.com.br'
        );
        $data->setAttributes($mock);

        // endereco
        $item = $this->view->newItem();
        $item->setId('1');
        $item->setType('endereco');
        $mock = array(
            'cep' => '01230111',
            'tipo' => 'rua',
            'logradouro' => 'Albuquerque Lins',
            'numero' => '848',
            'complemento' => '101',
            'bairro' => 'Santa Cecília',
            'cidade' => 'São Paulo',
            'estado' => 'SP'
        );
        $item->setAttributes($mock);
        $this->view->addIncluded($item);

        $response = $response->withJSON($this->view->get());
        return $response;
    }
    // public function detalheUser($request, $response, $args) {
    //     $uid = $args['uid'];
    //     if ($this->user->readByFK('uid',$uid) == null) return $response->withStatus(403);
    //     $relations = $this->user->getRelations();
        
    //     $model = $this->models->{$this->user->getTipo()}();
    //     $model->readByFK('user',$this->user->getId());
    //     $this->read($model);

    //     foreach($relations as $r) {
    //         $rModel = $this->models->{$r}();
    //         $rModel->readByFK('user',$this->user->getId());
    //         if ($rModel->getId() == null) continue;

    //         $item = $this->view->newItem();
    //         $item->setId($rModel->getId());
    //         $item->setType($rModel->getType());
    //         $this->view->getData()->addRelationships($item->get());
    //         $item->setAttributes($rModel->get());
    //         $this->view->addIncluded($item);
    //     }

    //     $response = $response->withJSON($this->view->get());
    //     return $response;
    // }
    
    public function atualizar($request, $response, $args) {
        // TODO
        $response = $response->withStatus(202);
        return $response;
    }

    public function deletar($request, $response, $args) {
        // TODO
        $response = $response->withStatus(204);
        return $response;
    }

     // Sacado Routes
    public function adcNovo($request, $response, $args) {
        // TODO
        $response = $response->withStatus(201);
        return $response;
    }

    public function todosSacado($request, $response, $args) {
        // TODO
        
        $item = $this->view->newItem();
        $item->setType('sacado');
        $item->setId('1');
        
        $mock = array(
            'nome' => 'Sacado 1 Ltda.',
            'nome_fantasia' => 'Sacado 1',
            'cnpj' => '2875981729',
            'email' => 'sacado@teste.com',
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('sacado');
        $item->setId('2');
        
        $mock = array(
            'nome' => 'Sacado 2 Ltda.',
            'nome_fantasia' => 'Sacado 2',
            'cnpj' => '2875981729',
            'email' => 'sacado@teste.com',
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('sacado');
        $item->setId('3');
        
        $mock = array(
            'nome' => 'Sacado 3 Ltda.',
            'nome_fantasia' => 'Sacado 3',
            'cnpj' => '2875981729',
            'email' => 'sacado@teste.com',
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $response = $response->withJSON($this->view->get());
        return $response;
    }
    
    public function detalhesSacado($request, $response, $args) {
        // TODO
        $data = $this->view->getData();
        $data->setType('sacado');
        $data->setId('1');
        
        $mock = array(
            'nome' => 'Sacado 1 Ltda.',
            'nome_fantasia' => 'Sacado 1',
            'cnpj' => '2875981729',
            'email' => 'sacado@teste.com',
        );
        $data->setAttributes($mock);

        // endereco
        $item = $this->view->newItem();
        $item->setId('2');
        $item->setType('endereco');
        $mock = array(
            'cep' => '01230111',
            'tipo' => 'rua',
            'logradouro' => 'Albuquerque Lins',
            'numero' => '848',
            'complemento' => '101',
            'bairro' => 'Santa Cecília',
            'cidade' => 'São Paulo',
            'estado' => 'SP'
        );
        $item->setAttributes($mock);
        $this->view->addIncluded($item);
        
        $response = $response->withJSON($this->view->get());
        return $response;
    }
    

    // Amigo Routes [depois]
    public function convidarAmigo($request, $response, $args) {
        // TODO
        return $response;
    }

    public function convites($request, $response, $args) {
        // TODO
        return $response;
    }
    
    public function detalheConvite($request, $response, $args) {
        // TODO
        return $response;
    }
    
    public function atualizarConvite($request, $response, $args) {
        // TODO
        return $response;
    }

    public function reenviarConvite($request, $response, $args) {
        // TODO
        return $response;
    }


    // Business Logic

    public function createUser() {
        $userId = $this->user->create();
        if ($userId == null) return null;
        $fk['user'] = $userId;
        if ($this->create($this->view->getData(),$fk) == null) return null;
        foreach($this->view->getIncluded() as $i) {
            $this->create($i,$fk);
        }
        return true;
    }

}