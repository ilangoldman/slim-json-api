<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Service\InvestidorService as InvestidorService;

// POST (CREATE) ROUTES

$app->post('/investidor', function (Request $request, Response $response, array $args) {
    $uid = $request->getHeader('user-id')[0];
    $requestData = $request->getParsedBody();

    $service = new InvestidorService($this->db);
    $investidor = $service->createInvestidor($uid,$requestData);
    if ($investidor == null) {
        $this->logger->addInfo("ERRO: Cadastro do Investidor ".$uid);
        $jsonResponse = $response->withStatus(401);
    } else {
        $this->logger->addInfo("Sucesso: Cadastro Investidor ".$uid." - ". $investidor['id']);
        $responseData = array( "data" => $investidor );
        $jsonResponse = $response->withJSON($responseData);
    }
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});










// GET (READ) ROUTES


// Perfil

$app->get('/investidor/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $uid = $request->getHeader('user-id')[0];

    $service = new InvestidorService($this->db);

    if ($service->validarUser($uid,$id)) {
        $investidorData = $service->getInvestidorById($id);
        $investidorInclude = $service->getIncludes($investidorData['relationships']);

        $this->logger->addInfo("Sucesso: Read Investidor ".$uid." - ". $id);
        $responseData = array( 
            "data" => $investidorData,
            "include" => $investidorInclude
        );
        $jsonResponse = $response->withJSON($responseData);
    } else {
        $this->logger->addInfo("ERRO: Read Investidor ".$uid." - ".$id);
        $jsonResponse = $response->withStatus(401);
    }
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});




// PUT (UPDATE) ROUTES


$app->put('/investidor/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $uid = $request->getHeader('user-id')[0];
    $request_data = $request->getParsedBody();

    $service = new InvestidorService($this->db);

    if ($service->validarUser($uid,$id)) {
        $investidor = $service->updateInvestidor($id,$request_data);

        $this->logger->addInfo("Sucesso: Update Investidor ".$uid." - ". $id);
        $responseData = array( "data" => $investidor );
        $jsonResponse = $response->withJSON($responseData);
    } else {
        $this->logger->addInfo("ERRO: Update Investidor ".$uid." - ".$id);
        $jsonResponse = $response->withStatus(401);
    }
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});








// DELETE ROUTES

$app->delete('/investidor/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $uid = $request->getHeader('user-id')[0];
    
    $service = new InvestidorService($this->db);

    if ($service->validarUser($uid,$id) && $service->validarInvestimentos($id)) {
        $investidor = $service->deleteInvestidor($id);

        $this->logger->addInfo("Sucesso: Deletar Investidor ".$uid." - ". $id);
        $responseData = array( "data" => $investidor );
        $jsonResponse = $response->withJSON($responseData);
    } else {
        $this->logger->addInfo("ERRO: Deletar Investidor ".$uid." - ".$id);
        $jsonResponse = $response->withStatus(401);
    }
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});


