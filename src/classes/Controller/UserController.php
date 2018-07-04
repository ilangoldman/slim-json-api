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
        $uid = $args['uid'];
        $body = $request->getParsedBody();
        if (!isset($body['data']['type'])) return $response->withStatus(400);
        $this->view->set($body);

        // Set the user
        $this->user->setTipoByKey($this->view->getData()->getType());
        $this->user->setUID($uid);

        $this->db->beginTransaction();
        try {
            if ($this->createUser() == null) {
                $this->db->rollBack();
                return $response->withStatus(403);
            }
            $url = $request->getUri()->getPath(); // . "/" . $uid;
            $response = $response->withStatus(201)->withHeader('location', $url);
            $this->logger->addInfo("Sucesso: Novo User ".$uid."|".$type." - ". $data['id']);
            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Novo User ".$uid."|".$type.": ".$e->getMessage());
            $response = $response->withStatus(400);
            $this->db->rollBack();
        } 
        return $response;
    }

    public function detalheUser($request, $response, $args) {
        $uid = $args['uid'];
        if ($this->user->readByFK('uid',$uid) == null) return $response->withStatus(403);
        $relations = $this->user->getRelations();
        
        $model = $this->models->{$this->user->getTipo()}();
        $model->readByFK('user',$this->user->getId());
        $this->read($model);

        foreach($relations as $r) {
            $rModel = $this->models->{$r}();
            $rModel->readByFK('user',$this->user->getId());
            if ($rModel->getId() == null) continue;

            $item = $this->view->newItem();
            $item->setId($rModel->getId());
            $item->setType($rModel->getType());
            $this->view->getData()->addRelationships($item->get());
            $item->setAttributes($rModel->get());
            $this->view->addIncluded($item);
        }

        $response = $response->withJSON($this->view->get());
        return $response;
    }
    
    public function atualizar($request, $response, $args) {
        // TODO
        return $response;
    }

    public function deletar($request, $response, $args) {
        // TODO
        return $response;
    }
    

    // Amigo Routes
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


    // Destinatario Routes
    public function adcNovo($request, $response, $args) {
        // TODO
        return $response;
    }

    public function todosDest($request, $response, $args) {
        // TODO
        return $response;
    }
    
    public function detalhesDest($request, $response, $args) {
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