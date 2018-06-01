<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
// TODO

// implementar nos routes a criacao do array ??
// pelo menos a parte externa e deixar dentro das classes para formar o especifico








// POST (CREATE) ROUTES


$app->post('/empresa/{id}/emprestimo', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    
    $this->logger->addInfo("empresa " . $id . " emprestimo Post");

    $dbo = new Empresa($this->db);
    $emprestimo = $dbo->createEmprestimo($id);

    // TODO

    $jsonResponse = $response->withJSON($emprestimo);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});

$app->post('/empresa', function (Request $request, Response $response, array $args) {
    
    $this->logger->addInfo("empresa " . $id . " Post");

    $dbo = new Empresa($this->db);
    $emprestimo = $dbo->createEmpresa($id);

    // TODO
    $data = array(
        "data" => $emprestimo
    );

    $jsonResponse = $response->withJSON($data);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});










// Notificacoes

$app->get('/empresa/{id}/notificacoes', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $this->logger->addInfo("empresa " . $id . " Request");

    $dbo = new Empresa($this->db);
    $notificacoes = $dbo->getNotificacoes($id);

    // TODO

    $jsonResponse = $response->withJSON($notificacoes);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});

// Parcelas

$app->get('/empresa/{id}/parcelas', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $periodo = $args['empid'];

    $this->logger->addInfo("empresa " . $id . " / Parcelas Request");

    $dbo = new Empresa($this->db);
    $parcelas = $dbo->getParcelas($id);

    // TODO

    $jsonResponse = $response->withJSON($parcelas);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});

// Parcelas do Emprestimo

$app->get('/empresa/{id}/emprestimo/{empid}/parcelas', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $periodo = $args['empid'];

    $this->logger->addInfo("empresa " . $id . " / Parcelas " . $empid . " Request");

    $dbo = new Empresa($this->db);
    $parcelas = $dbo->getParcelasEmprestimo($id, $empid);

    // TODO

    $jsonResponse = $response->withJSON($parcelas);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});

// Emprestimos

$app->get('/empresa/{id}/emprestimo/{empid}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $id = $args['empid'];
    
    $this->logger->addInfo("empresa " . $id . " / emprestimos " . $empid . " Request");

    $dbo = new Empresa($this->db);
    $emprestimo = $dbo->getEmprestimoById($id, $empid);

    // TODO

    $jsonResponse = $response->withJSON($emprestimo);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});


$app->get('/empresa/{id}/emprestimo', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $this->logger->addInfo("empresa " . $id . " emprestimos Request");

    $dbo = new Empresa($this->db);
    $emprestimos = $dbo->getEmprestimo($id);

    // TODO

    $jsonResponse = $response->withJSON($emprestimos);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});


// Perfil

$app->get('/empresa/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $this->logger->addInfo("empresa " . $id . " Request");

    $dbo = new Empresa($this->db);
    $empresa = $dbo->getEmpresaById($id);

    $jsonResponse = $response->withJSON($empresa);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});

// Todas as empresas

$app->get('/empresa', function (Request $request, Response $response, array $args) {
    $this->logger->addInfo("empresa Request");
    
    $dbo = new Empresa($this->db);
    $empresaes = $dbo->getEmpresas();

    $jsonResponse = $response->withJSON($empresaes);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});








// PUT (UPDATE) ROUTES


$app->put('/empresa/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    
    $this->logger->addInfo("Empresa " . $id . " Investimento Update");

    $dbo = new Empresa($this->db);
    $empresa = $dbo->updateEmpresa($id);

    // TODO



    $jsonResponse = $response->withJSON($data);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});








// DELETE ROUTES

$app->delete('/empresa/{id}/emprestimo/{empid}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $invid = $args['invid'];
    
    $this->logger->addInfo("Empresa " . $id . " Emprestimo " . $invid . " Delete");

    $dbo = new Empresa($this->db);
    $emprestimo = $dbo->deleteEmprestimo($id);

    // TODO

    $jsonResponse = $response->withJSON($data);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});


$app->delete('/empresa/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $invid = $args['invid'];
    
    $this->logger->addInfo("Empresa " . $id . " Delete");

    $dbo = new Empresa($this->db);
    $empresa = $dbo->deleteEmpresa($id);

    // TODO

    $jsonResponse = $response->withJSON($data);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});
