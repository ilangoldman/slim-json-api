<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
// TODO

// implementar nos routes a criacao do array ??
// pelo menos a parte externa e deixar dentro das classes para formar o especifico



// POST (CREATE) ROUTES


$app->post('/investidor/{id}/investimento', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    
    $this->logger->addInfo("Investidor " . $id . " Investimento Post");

    $dbo = new Investidor($this->db);
    $investimento = $dbo->createInvestimento($id);

    // TODO

    $jsonResponse = $response->withJSON($investimento);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});

$app->post('/investidor', function (Request $request, Response $response, array $args) {

    $uid = $request->getHeader('user-id')[0];
    // var_export($uid);
    
    $request_data = $request->getParsedBody();
    // $request_data = $request->getBody()->getContents();
    // var_export($request_data);
    // $r = json_decode($request_data);
    // var_export($r);

    // $investidor_data = [];
    // $investidor_data['title'] = filter_var($request_data['title'], FILTER_SANITIZE_STRING);
    // $investidor_data['description'] = filter_var($request_data['description'], FILTER_SANITIZE_STRING);


    $this->logger->addInfo("Investidor " . $uid . " Post");

    $dbo = new Investidor($this->db);
    $investidor = $dbo->createInvestidor($uid,$request_data);

    // TODO
    // $data = array(
    //     "data" => $investimento
    // );

    // $jsonResponse = $response->withJSON($data);
    return $response //$jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});










// GET (READ) ROUTES

// Notificacoes

$app->get('/investidor/{id}/notificacoes', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $this->logger->addInfo("Investidor " . $id . " Request");

    $dbo = new Investidor($this->db);
    $notificacoes = $dbo->getNotificacoes($id);

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

    $dbo = new Investidor($this->db);
    $investimentos = $dbo->getInvestimentos($id);

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

    $dbo = new Investidor($this->db);
    $investimentos = $dbo->getInvestimentoById($id,$invid);

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

    $dbo = new Investidor($this->db);
    $parcelas = $dbo->getMovimentacaoFiltro($id, $periodo);

    // TODO

    $jsonResponse = $response->withJSON($parcelas);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});


$app->get('/investidor/{id}/movimentacao', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $this->logger->addInfo("Investidor " . $id . " / Parcelas Request");

    $dbo = new Investidor($this->db);
    $parcelas = $dbo->getMovimentacao($id);

    // TODO

    $jsonResponse = $response->withJSON($parcelas);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});

// Perfil

$app->get('/investidor/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $this->logger->addInfo("Investidor " . $id . " Request");
    $dbo = new Investidor($this->db);
    $investidor = $dbo->getInvestidorById($id);

    $jsonResponse = $response->withJSON($investidor);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});

$app->get('/investidor', function (Request $request, Response $response, array $args) {
    $this->logger->addInfo("Investidor Request");
    $dbo = new Investidor($this->db);
    $investidores = $dbo->getInvestidor();

    $jsonResponse = $response->withJSON($investidores);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});









// PUT (UPDATE) ROUTES


$app->put('/investidor/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    
    $this->logger->addInfo("Investidor " . $id . " Investimento Update");

    $dbo = new Investidor($this->db);
    $investimento = $dbo->updateInvestidor($id);

    // TODO

    $jsonResponse = $response->withJSON($investimento);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});








// DELETE ROUTES

$app->delete('/investidor/{id}/investimento/{invid}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $invid = $args['invid'];
    
    $this->logger->addInfo("Investidor " . $id . " Investimento " . $invid . " Delete");

    $dbo = new Investidor($this->db);
    $investimento = $dbo->deleteInvestimento($id);

    // TODO

    $jsonResponse = $response->withJSON($investimento);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});


$app->delete('/investidor/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $invid = $args['invid'];
    
    $this->logger->addInfo("Investidor " . $id . " Delete");

    $dbo = new Investidor($this->db);
    $investidor = $dbo->deleteInvestidor($id);

    // TODO

    $jsonResponse = $response->withJSON($investidor);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});


