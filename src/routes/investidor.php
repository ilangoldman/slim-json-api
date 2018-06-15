<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Service\DBOController as DBOController;



// GET OPORTUNIDADES


$app->get('/oportunidade', function (Request $request, Response $response, array $args) {

    $uid = $request->getHeader('user-id')[0];
    $userId = $request->getHeader('id')[0];
    $userType = $request->getHeader('user-type')[0];

    $controller = new DBOController($this->db);

    if ($controller->validarUser($uid,$userId,$userType)) {

        $dbo = $controller->oportunidade();
        $data = $controller->getAllJSONAPI($dbo);
        $responseData = array( "data" => $data );
        $jsonResponse = $response->withJSON($responseData);

        $this->logger->addInfo("Sucesso: Read oportunidade ".$uid."|".$userId);
    } else {
        $jsonResponse = $response->withStatus(401);

        $this->logger->addInfo("ERRO: Read oportunidade ".$uid."|".$userId);
    }

    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});


$app->get('/investimento', function (Request $request, Response $response, array $args) {

    $uid = $request->getHeader('user-id')[0];
    $userId = $request->getHeader('id')[0];
    $userType = $request->getHeader('user-type')[0];

    $controller = new DBOController($this->db);

    if ($controller->allowInvestidorOnly($userType) &&
        $controller->validarUser($uid,$userId,$userType)) {

        $dbo = $controller->investimento();
        $dbo->setInvestidor($userId);
        $data = $controller->getAllJSONAPI($dbo);
        $responseData = array( "data" => $data );
        $jsonResponse = $response->withJSON($responseData);

        $this->logger->addInfo("Sucesso: Read all investimento ".$uid."|".$userId);
    } else {
        $jsonResponse = $response->withStatus(401);
        $this->logger->addInfo("ERRO: Read all investimento ".$uid."|".$userId);
    }

    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});























































// // POST (CREATE) ROUTES

// $app->post('/investidor', function (Request $request, Response $response, array $args) {
//     $uid = $request->getHeader('user-id')[0];
//     $requestData = $request->getParsedBody();

//     $service = new InvestidorService($this->db);
//     $investidor = $service->createInvestidor($uid,$requestData);
//     if ($investidor == null) {
//         $this->logger->addInfo("ERRO: Cadastro do Investidor ".$uid);
//         $jsonResponse = $response->withStatus(401);
//     } else {
//         $this->logger->addInfo("Sucesso: Cadastro Investidor ".$uid." - ". $investidor['id']);
//         $responseData = array( "data" => $investidor );
//         $jsonResponse = $response->withJSON($responseData);
//     }
//     return $jsonResponse
//         ->withHeader('Content-Type', 'application/vnd.api+json')
//         ->withHeader('Access-Control-Allow-Origin', '*');
// });










// // GET (READ) ROUTES


// // Perfil

// $app->get('/investidor/{id}', function (Request $request, Response $response, array $args) {
//     $id = $args['id'];
//     $uid = $request->getHeader('user-id')[0];

//     $service = new InvestidorService($this->db);

//     if ($service->validarUser($uid,$id)) {
//         $investidorData = $service->getInvestidorById($id);
//         $investidorInclude = $service->getIncludes($investidorData['relationships']);

//         $this->logger->addInfo("Sucesso: Read Investidor ".$uid." - ". $id);
//         $responseData = array( 
//             "data" => $investidorData,
//             "include" => $investidorInclude
//         );
//         $jsonResponse = $response->withJSON($responseData);
//     } else {
//         $this->logger->addInfo("ERRO: Read Investidor ".$uid." - ".$id);
//         $jsonResponse = $response->withStatus(401);
//     }
//     return $jsonResponse
//         ->withHeader('Content-Type', 'application/vnd.api+json')
//         ->withHeader('Access-Control-Allow-Origin', '*');  
// });




// // PUT (UPDATE) ROUTES


// $app->put('/investidor/{id}', function (Request $request, Response $response, array $args) {
//     $id = $args['id'];
//     $uid = $request->getHeader('user-id')[0];
//     $request_data = $request->getParsedBody();

//     $service = new InvestidorService($this->db);

//     if ($service->validarUser($uid,$id)) {
//         $investidor = $service->updateInvestidor($id,$request_data);

//         $this->logger->addInfo("Sucesso: Update Investidor ".$uid." - ". $id);
//         $responseData = array( "data" => $investidor );
//         $jsonResponse = $response->withJSON($responseData);
//     } else {
//         $this->logger->addInfo("ERRO: Update Investidor ".$uid." - ".$id);
//         $jsonResponse = $response->withStatus(401);
//     }
//     return $jsonResponse
//         ->withHeader('Content-Type', 'application/vnd.api+json')
//         ->withHeader('Access-Control-Allow-Origin', '*');
// });








// // DELETE ROUTES

// $app->delete('/investidor/{id}', function (Request $request, Response $response, array $args) {
//     $id = $args['id'];
//     $uid = $request->getHeader('user-id')[0];
    
//     $service = new InvestidorService($this->db);

//     if ($service->validarUser($uid,$id) && $service->validarInvestimentos($id)) {
//         $investidor = $service->deleteInvestidor($id);

//         $this->logger->addInfo("Sucesso: Deletar Investidor ".$uid." - ". $id);
//         $responseData = array( "data" => $investidor );
//         $jsonResponse = $response->withJSON($responseData);
//     } else {
//         $this->logger->addInfo("ERRO: Deletar Investidor ".$uid." - ".$id);
//         $jsonResponse = $response->withStatus(401);
//     }
//     return $jsonResponse
//         ->withHeader('Content-Type', 'application/vnd.api+json')
//         ->withHeader('Access-Control-Allow-Origin', '*');
// });


