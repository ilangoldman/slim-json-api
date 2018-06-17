<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Service\DBOController as DBOController;
//use \Service\Security as Security;
//use \Service\EmpresaService as EmpresaService;



$app->post('/emprestimo/{id}/parcela', function (Request $request, Response $response, array $args) {
    $id = $args['id'];

    $uid = $request->getHeader('user-id')[0];
    $userId = $request->getHeader('id')[0];
    $userType = $request->getHeader('user-type')[0];
    $requestData = $request->getParsedBody();

    $method = $request->getMethod();

    $controller = new DBOController($this->db);
    
    if (!$controller->allowEmpresaOnly($userType) &&
        !$controller->validarUser($uid,$userId,$userType) &&
        !$controller->validarUserAccess($userId,$userType,$id,"parcela",$method)){
            return $response->withStatus(401);
        }

    // $dbo = $controller->{$type}();

    $this->db->beginTransaction();
    try {

        $requestData['data']['attributes']['emprestimo'] = $id;

        $dbo = $controller->parcela();
        $pid = $dbo->create($requestData['data']['attributes']);
        
        $url = $request->getUri()->getPath() . "/" . $pid;
        $jsonResponse = $response->withStatus(201)->withHeader('location', $url);
        
        $this->logger->addInfo("Sucesso: Cadastro Parcela ".$uid." - ". $pid);

        $this->db->commit();
    } catch(PDOException $e) {
        $this->logger->addInfo("ERRO: Cadastro Generico ".$uid.": ".$e->getMessage());
        
        $jsonResponse = $response->withStatus(400);
        $this->db->rollBack();
    }
        
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});


// TODO
$app->get('/emprestimo/{id}/parcela', function (Request $request, Response $response, array $args) {
    $id = $args['id'];

    $uid = $request->getHeader('user-id')[0];
    $userId = $request->getHeader('id')[0];
    $userType = $request->getHeader('user-type')[0];

    $controller = new DBOController($this->db);

    if ($controller->allowEmpresaOnly($userType) &&
        $controller->validarUser($uid,$userId,$userType) &&
        $controller->validarUserAccess($userId,$userType,$id,"parcela",$method)) {

        $dbo = $controller->parcela();
        $data = $controller->getAllJSONAPI($dbo);
        $responseData = array( "data" => $data );
        $jsonResponse = $response->withJSON($responseData);

        $this->logger->addInfo("Sucesso: Read parcela ".$uid."|".$userId." - ". $id);
    } else {
        $jsonResponse = $response->withStatus(401);

        $this->logger->addInfo("ERRO: Read parcela ".$uid."|".$userId." - ".$id);
    }

    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});






















// // POST (CREATE) ROUTES

// $app->post('/empresa', function (Request $request, Response $response, array $args) {
//     $uid = $request->getHeader('user-id')[0];
//     $requestData = $request->getParsedBody();

//     $service = new EmpresaService($this->db);
//     $empresa = $service->createEmpresa($uid,$requestData);
//     if ($empresa == null) {
//         $this->logger->addInfo("ERRO: Cadastro do Empresa ".$uid);
//         $jsonResponse = $response->withStatus(401);
//     } else {
//         $this->logger->addInfo("Sucesso: Cadastro Empresa ".$uid." - ". $empresa['id']);
//         $responseData = array( "data" => $empresa );
//         $jsonResponse = $response->withJSON($responseData);
//     }
//     return $jsonResponse
//         ->withHeader('Content-Type', 'application/vnd.api+json')
//         ->withHeader('Access-Control-Allow-Origin', '*');
// });









// // Perfil

// $app->get('/empresa/{id}', function (Request $request, Response $response, array $args) {
//     $id = $args['id'];
//     $uid = $request->getHeader('user-id')[0];

//     $service = new EmpresaService($this->db);

//     if ($service->validarUser($uid,$id)) {
//         $empresaData = $service->getEmpresaById($id);
//         $empresaInclude = $service->getIncludes($empresaData['relationships']);

//         $this->logger->addInfo("Sucesso: Read Empresa ".$uid." - ". $id);
//         $responseData = array( 
//             "data" => $empresaData,
//             "include" => $empresaInclude
//         );
//         $jsonResponse = $response->withJSON($responseData);
//     } else {
//         $this->logger->addInfo("ERRO: Read Empresa ".$uid." - ".$id);
//         $jsonResponse = $response->withStatus(401);
//     }

//     return $jsonResponse
//         ->withHeader('Content-Type', 'application/vnd.api+json')
//         ->withHeader('Access-Control-Allow-Origin', '*');  
// });







// // PUT (UPDATE) ROUTES


// $app->put('/empresa/{id}', function (Request $request, Response $response, array $args) {
//     $id = $args['id'];
//     $uid = $request->getHeader('user-id')[0];
//     $request_data = $request->getParsedBody();

//     $service = new EmpresaService($this->db);

//     if ($service->validarUser($uid,$id)) {
//         $empresa = $service->updateEmpresa($id,$request_data);

//         $this->logger->addInfo("Sucesso: Update Empresa ".$uid." - ". $id);
//         $responseData = array( "data" => $empresa );
//         $jsonResponse = $response->withJSON($responseData);
//     } else {
//         $this->logger->addInfo("ERRO: Update Empresa ".$uid." - ".$id);
//         $jsonResponse = $response->withStatus(401);
//     }
//     return $jsonResponse
//         ->withHeader('Content-Type', 'application/vnd.api+json')
//         ->withHeader('Access-Control-Allow-Origin', '*');
// });








// // DELETE ROUTES

// $app->delete('/empresa/{id}', function (Request $request, Response $response, array $args) {
//     $id = $args['id'];
//     $uid = $request->getHeader('user-id')[0];
    
//     $service = new EmpresaService($this->db);

//     if ($service->validarUser($uid,$id) && $service->validarInvestimentos($id)) {
//         $empresa = $service->deleteEmpresa($id);

//         $this->logger->addInfo("Sucesso: Deletar Empresa ".$uid." - ". $id);
//         $responseData = array( "data" => $empresa );
//         $jsonResponse = $response->withJSON($responseData);
//     } else {
//         $this->logger->addInfo("ERRO: Deletar Empresa ".$uid." - ".$id);
//         $jsonResponse = $response->withStatus(401);
//     }
//     return $jsonResponse
//         ->withHeader('Content-Type', 'application/vnd.api+json')
//         ->withHeader('Access-Control-Allow-Origin', '*');
// });
