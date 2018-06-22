<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Service\DBOController as DBOController;
use \Service\Security as Security;

// POST (CREATE) ROUTES

$app->put('/emprestimo/{id}/aprovado', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $uid = $request->getHeader('user-id')[0];

    $controller = new DBOController($this->db);

    if ($controller->validarAdmin($uid)) {
        $dbo = $controller->emprestimo();
        $this->db->beginTransaction();
        try {
            $dbo->setId($id);
            $dbo->aprovarEmprestimo();
            
            
            $this->logger->addInfo("Sucesso: Emprestimo Aprovado ".$uid." - ". $id);

            $jsonResponse = $response->withStatus(202);
            
            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Emprestimo Aprovado ".$uid." - ". $id.": ".$e->getMessage());
            
            $jsonResponse = $response->withStatus(400);
            $this->db->rollBack();
        }
    } else {
        $this->logger->addInfo("ERRO: NOT ADMIN");
        $jsonResponse = $response->withStatus(401);
    }
        
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});



$app->put('/emprestimo/{id}/transferencia', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $uid = $request->getHeader('user-id')[0];

    $controller = new DBOController($this->db);

    if ($controller->validarAdmin($uid)) {
        $dbo = $controller->emprestimo();
        $this->db->beginTransaction();
        try {
            $dbo->setId($id);
            $dbo->iniciarTransferencias();
            
            
            $this->logger->addInfo("Sucesso: Transferencia Aprovado ".$uid." - ". $id);

            $jsonResponse = $response->withStatus(202);
            
            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Transferencia Aprovado ".$uid." - ". $id.": ".$e->getMessage());
            
            $jsonResponse = $response->withStatus(400);
            $this->db->rollBack();
        }
    } else {
        $this->logger->addInfo("ERRO: NOT ADMIN");
        $jsonResponse = $response->withStatus(401);
    }
        
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});



$app->post('/notificacao/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $uid = $request->getHeader('user-id')[0];
    $requestData = $request->getParsedBody();

    $controller = new DBOController($this->db);

    if ($controller->validarAdmin($uid)) {
        $dbo = $controller->notificacao();
        $this->db->beginTransaction();
        try {
            $dbo->setId($id);
            $users = $requestData['data'];

            foreach($users as $u) {
                var_export($u);            
                // $u['attributes']['notificacao'] = $id;
                $dbo->create($u);
            }

            
            $this->logger->addInfo("Sucesso: Notificacao criada ".$uid." - ". $id);

            $jsonResponse = $response->withStatus(202);
            
            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Notificacao criada ".$uid." - ". $id.": ".$e->getMessage());
            
            $jsonResponse = $response->withStatus(400);
            $this->db->rollBack();
        }
    } else {
        $this->logger->addInfo("ERRO: NOT ADMIN");
        $jsonResponse = $response->withStatus(401);
    }
        
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});

$app->delete('/notificacao/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $uid = $request->getHeader('user-id')[0];
    $requestData = $request->getParsedBody();

    $controller = new DBOController($this->db);

    if ($controller->validarAdmin($uid)) {
        $dbo = $controller->notificacao();
        $this->db->beginTransaction();
        try {
            $dbo->setId($id);
            $users = $requestData['data'];
    
            foreach($users as $u) {
                var_export($u);
                $u['notificacao'] = $id;
                // $u['attributes']['notificacao'] = $id;
                $dbo->delete($u);
            }

            
            $this->logger->addInfo("Sucesso: Notificacao deletada ".$uid." - ". $id);

            $jsonResponse = $response->withStatus(200);
            
            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Notificacao deletada ".$uid." - ". $id.": ".$e->getMessage());
            
            $jsonResponse = $response->withStatus(400);
            $this->db->rollBack();
        }
    } else {
        $this->logger->addInfo("ERRO: NOT ADMIN");
        $jsonResponse = $response->withStatus(401);
    }
        
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});