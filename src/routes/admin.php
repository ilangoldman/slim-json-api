<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Service\DBOController as DBOController;

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
