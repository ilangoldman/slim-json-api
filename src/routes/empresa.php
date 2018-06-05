<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Service\EmpresaService as EmpresaService;

// POST (CREATE) ROUTES

$app->post('/empresa', function (Request $request, Response $response, array $args) {
    $uid = $request->getHeader('user-id')[0];
    $requestData = $request->getParsedBody();

    $service = new EmpresaService($this->db);
    $empresa = $service->createEmpresa($uid,$requestData);
    if ($empresa == null) {
        $this->logger->addInfo("ERRO: Cadastro do Empresa ".$uid);
        $jsonResponse = $response->withStatus(401);
    } else {
        $this->logger->addInfo("Sucesso: Cadastro Empresa ".$uid." - ". $empresa['id']);
        $responseData = array( "data" => $empresa );
        $jsonResponse = $response->withJSON($responseData);
    }
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});









// Perfil

$app->get('/empresa/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $uid = $request->getHeader('user-id')[0];

    $service = new EmpresaService($this->db);

    if ($service->validarUser($uid,$id)) {
        $empresaData = $service->getEmpresaById($id);
        $empresaInclude = $service->getIncludes($empresaData['relationships']);

        $this->logger->addInfo("Sucesso: Read Empresa ".$uid." - ". $id);
        $responseData = array( 
            "data" => $empresaData,
            "include" => $empresaInclude
        );
        $jsonResponse = $response->withJSON($responseData);
    } else {
        $this->logger->addInfo("ERRO: Read Empresa ".$uid." - ".$id);
        $jsonResponse = $response->withStatus(401);
    }

    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});







// PUT (UPDATE) ROUTES


$app->put('/empresa/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $uid = $request->getHeader('user-id')[0];
    $request_data = $request->getParsedBody();

    $service = new EmpresaService($this->db);

    if ($service->validarUser($uid,$id)) {
        $empresa = $service->updateEmpresa($id,$request_data);

        $this->logger->addInfo("Sucesso: Update Empresa ".$uid." - ". $id);
        $responseData = array( "data" => $empresa );
        $jsonResponse = $response->withJSON($responseData);
    } else {
        $this->logger->addInfo("ERRO: Update Empresa ".$uid." - ".$id);
        $jsonResponse = $response->withStatus(401);
    }
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});








// DELETE ROUTES

$app->delete('/empresa/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $uid = $request->getHeader('user-id')[0];
    
    $service = new EmpresaService($this->db);

    if ($service->validarUser($uid,$id) && $service->validarInvestimentos($id)) {
        $empresa = $service->deleteEmpresa($id);

        $this->logger->addInfo("Sucesso: Deletar Empresa ".$uid." - ". $id);
        $responseData = array( "data" => $empresa );
        $jsonResponse = $response->withJSON($responseData);
    } else {
        $this->logger->addInfo("ERRO: Deletar Empresa ".$uid." - ".$id);
        $jsonResponse = $response->withStatus(401);
    }
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});
