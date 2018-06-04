<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Service\InvestidorService as InvestidorService;
// TODO

// implementar nos routes a criacao do array ??
// pelo menos a parte externa e deixar dentro das classes para formar o especifico



// POST (CREATE) ROUTES

$app->post('/investidor/{id}/investimento', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    
    $this->logger->addInfo("Investidor " . $id . " Investimento Post");

    $service = new InvestidorService($this->db);
    $investimento = $service->createInvestimento($id);

    // TODO
    

    $jsonResponse = $response->withJSON($investimento);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});

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

// Notificacoes

$app->get('/investidor/{id}/notificacoes', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $this->logger->addInfo("Investidor " . $id . " Request");

    $service = new InvestidorService($this->db);
    $notificacoes = $service->getNotificacoes($id);

    // TODO

    $jsonResponse = $response->withJSON($notificacoes);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});


// Empresas Investidas

$app->get('/investidor/{id}/investimento', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $this->logger->addInfo("Investidor " . $id . " Request");

    $service = new InvestidorService($this->db);
    $investimentos = $service->getInvestimentos($id);

    // TODO

    $jsonResponse = $response->withJSON($investimentos);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});

$app->get('/investidor/{id}/investimento/{invid}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $invid = $args['invid'];
    $this->logger->addInfo("Investidor " . $id . " Investiment " . $invid . " Request");

    $service = new InvestidorService($this->db);
    $investimentos = $service->getInvestimentoById($id,$invid);

    // TODO

    $jsonResponse = $response->withJSON($investimentos);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});

// Movimentacao

$app->get('/investidor/{id}/movimentacao/{periodo}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $periodo = $args['periodo'];

    $this->logger->addInfo("Investidor " . $id . " / Movimentacao " . $periodo . " Request");

    $service = new InvestidorService($this->db);
    $parcelas = $service->getMovimentacaoFiltro($id, $periodo);

    // TODO

    $jsonResponse = $response->withJSON($parcelas);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});


$app->get('/investidor/{id}/movimentacao', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $this->logger->addInfo("Investidor " . $id . " / Parcelas Request");

    $service = new InvestidorService($this->db);
    $parcelas = $service->getMovimentacao($id);

    // TODO

    $jsonResponse = $response->withJSON($parcelas);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});

// Perfil

$app->get('/investidor/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $uid = $request->getHeader('user-id')[0];

    $service = new InvestidorService($this->db);

    if ($service->validarUser($uid,$id)) {
        $investidor = $service->getInvestidorById($id);

        $this->logger->addInfo("Sucesso: Read Investidor ".$uid." - ". $id);
        $responseData = array( "data" => $investidor );
        $jsonResponse = $response->withJSON($responseData);
    } else {
        $this->logger->addInfo("ERRO: Read Investidor ".$uid." - ".$id);
        $jsonResponse = $response->withStatus(401);
    }
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});

$app->get('/investidor', function (Request $request, Response $response, array $args) {
    $this->logger->addInfo("Investidor Request");
    $service = new InvestidorService($this->db);
    $investidores = $service->getInvestidor();

    // TODO

    $jsonResponse = $response->withJSON($investidores);
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

$app->delete('/investidor/{id}/investimento/{invid}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $invid = $args['invid'];
    
    $this->logger->addInfo("Investidor " . $id . " Investimento " . $invid . " Delete");

    $service = new InvestidorService($this->db);
    $investimento = $service->deleteInvestimento($id);

    // TODO

    $jsonResponse = $response->withJSON($investimento);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});


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


