<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// use \Service\DBOController as DBOController;

// Controllers
use \Controller\UserController as UserController;
use \Controller\InvestimentoController as InvestimentoController;
use \Controller\NotificationController as NotificationController;
use \Controller\GameficationController as GameficationController;


// Routes

// TODO -> Criar controllers
// TODO -> Criar view JSON API

// route generica para um user
$app->group('/{_:user|empresa|investidor}/{uid}', function () {

    // call controller
    // CRUD User
    $this->post('', UserController::class . ':novo');
    $this->get('', UserController::class . ':detalheUser');
    $this->put('', UserController::class . ':atualizar');
    $this->delete('', UserController::class . ':deletar');

    $this->group('/amigo', function() {
        $this->post('', UserController::class . ':convidarAmigo');
        $this->get('', UserController::class . ':convites');
        $this->get('/{id}', UserController::class . ':detalheConvite');
        $this->put('/{id}', UserController::class . ':atualizarConvite');
        $this->put('/{id}/reenviar', UserController::class . ':reenviarConvite');
        // $this->delete('/{id}', UserController::class . ':removerConvite');
    });

    $this->group('/notificacao', function() {
        $this->get('', NotificationController::class . ':todas');
        $this->get('/{id}', NotificationController::class . ':detalhe');
        $this->put('/{id}', NotificationController::class . ':marcarComoLida');
    });
});

// especifico da empresa
$app->group('/empresa/{uid}', function () {
    // CRUD emprestimo
    $this->group('/emprestimo', function() {
        $this->post('', InvestimentoController::class . ':pedirEmprestimo');
        $this->get('', InvestimentoController::class . ':pedidos');
        $this->get('/{id}', InvestimentoController::class . ':detalheEmprestimo');
        $this->put('/{id}', InvestimentoController::class . ':editarPedido');
        $this->delete('/{id}', InvestimentoController::class . ':removerPedido');
        
        // CRUD parcelas
        $this->group('{eid}/parcela', function() {
            $this->post('', InvestimentoController::class . ':pagar');
            $this->get('', InvestimentoController::class . ':parcelas');
            // $this->get('/{id}', InvestimentoController::class . ':verParcela');
        });
    });

    $this->get('/parcela', InvestimentoController::class . ':todasParcelasDaEmpresa');
});

// especifico do investidor
$app->group('/investidor/{uid}', function () {

    $this->group('/investimento', function() {
        $this->post('', InvestimentoController::class . ':investir');
        $this->get('', InvestimentoController::class . ':investimentos');
        $this->get('/{id}', InvestimentoController::class . ':detalheInvestimento');
        $this->put('/{id}', InvestimentoController::class . ':transferir');
        $this->delete('/{id}', InvestimentoController::class . ':removerOferta');
        
        // CRUD parcelas
        $this->group('/parcela', function() {
            $this->get('', InvestimentoController::class . ':parcelas');
            // $this->get('/{id}', InvestimentoController::class . ':detalheParcela');
        });
    });

    // Rodada de Investimento
    $this->group('/oportunidade', function() {
        $this->get('', InvestimentoController::class . ':oportunidades');
        $this->get('/{id}', InvestimentoController::class . ':detalheOportunidade');
    });

    // $this->get('/parcela', InvestimentoController::class . ':todasParcelasDoInvestidor');
    $this->get('/carteira', InvestimentoController::class . ':carteira');
    $this->get('/beneficio', GameficationController::class . ':beneficiosGanhos');
    $this->get('/conquista', GameficationController::class . ':atividadesConquistadas');
    
});

// Acesso livre
$app->get('/recompensa', GameficationController::class . ':recompensas');
$app->get('/atividade', GameficationController::class . ':atividades');
$app->get('/pontuacao', GameficationController::class . ':tblPontos');

// especifico para admin
$app->group('/admin/{uid}', function () {

    // Mensagens
    $this->group('/notificacao', function() {
        $this->post('', AdminController::class . ':novaNotificacao');
        $this->delete('/{id}', AdminController::class . ':removerNotificacao');
        
        $this->group('/mensagem', function() {
            $this->post('', AdminController::class . ':novaMensagem');
            $this->delete('/{id}', AdminController::class . ':removerMensagem');        
        });
    });

    // Investimentos 
    $this->group('/emprestimo/{id}', function() {
        $this->put('/aprovado', AdminController::class . ':aprovar');
        $this->put('/transferencia', AdminController::class . ':transferir');

    });

    // Gamefication
    $this->group('/atividade{id}', function() {
        $this->put('', AdminController::class . ':adcionarAtividade');
        $this->delete('', AdminController::class . ':removerAtividade');
    });

    $this->group('/recompensa/{id}', function() {
        $this->put('', AdminController::class . ':adcionarRecompensa');
        $this->delete('', AdminController::class . ':removerRecompensa');
    });


});


// Slim already handles this
// Route Not Found
// $app->map(['POST','GET','PUT','DELETE'], '', function ($request, $response, $args) {
//     return $response->withStatus(404);
// }



// Backup -> OLDER ROUTES



// Add routing

// require __DIR__ . './routes/user.php';
// require __DIR__ . './routes/admin.php';
// require __DIR__ . './routes/investidor.php';
// require __DIR__ . './routes/empresa.php';

// CREATE

// $app->post('/{type}', function (Request $request, Response $response, array $args) {
//     $type = $args['type'];

//     $uid = $request->getHeader('user-id')[0];
//     $userId = $request->getHeader('id')[0];
//     // $userType = $request->getHeader('user-type')[0];
//     $requestData = $request->getParsedBody();

//     $method = $request->getMethod();

//     $controller = new DBOController($this->db);
//     $security = new Security($this->db, $userId);
    
//     if (!$security->validarUser($uid) &&
//         !$security->userAccess(null, $type,$method))
//             return $response->withStatus(401);
    
//     $dbo = $controller->{$type}();       
//     $this->db->beginTransaction();
//     try {

//         if(isset($requestData['data']['relationships'])) {
//             foreach($requestData['data']['relationships'] as $r) {
//                 $rType = $r['type'];
//                 $relationshipDBO = $controller->$rType();
//                 $relationshipId = $relationshipDBO->create($r['attributes']);
//                 $requestData['data']['attributes'][$rType] = $relationshipId;
//             }
//         }

//         $requestData['data']['attributes'][$userType] = $userId;
//         $id = $dbo->create($requestData['data']['attributes']);

//         if ($id == null) return $response->withStatus(403);
        
//         if(isset($requestData['include'])) {
//             foreach($requestData['include'] as $i) {
//                 $includeDBO = $controller->{$i['type']}();
//                 $i['attributes'][$type] = $id;
//                 // var_export($i);
//                 $includeId = $includeDBO->create($i['attributes']);
//             }
//         }
        
//         $url = $request->getUri()->getPath() . "/" . $id;
//         $jsonResponse = $response->withStatus(201)->withHeader('location', $url);
        
//         $this->logger->addInfo("Sucesso: Cadastro Generico ".$uid." - ". $data['id']);

//         $this->db->commit();
//     } catch(PDOException $e) {
//         $this->logger->addInfo("ERRO: Cadastro Generico ".$uid.": ".$e->getMessage());
        
//         $jsonResponse = $response->withStatus(400);
//         $this->db->rollBack();
//     }
        
//     return $jsonResponse
//         ->withHeader('Content-Type', 'application/vnd.api+json')
//         ->withHeader('Access-Control-Allow-Origin', '*');
// });


// // READ

// $app->get('/{type}/{id}', function (Request $request, Response $response, array $args) {
//     $type = $args['type'];    
//     $id = $args['id'];

//     $uid = $request->getHeader('user-id')[0] ?? null;
//     $userId = $request->getHeader('id')[0] ?? null;
//     // $userType = $request->getHeader('user-type')[0] ?? null;

//     $method = $request->getMethod();
    
//     if(!isset($uid) || !isset($userId)) return $response->withStatus(403);
    
//     $controller = new DBOController($this->db);
//     $security = new Security($this->db, $userId);

//     if ($security->validarUser($uid) && 
//         $security->userAccess($id,$type,$method)) {

//         $dbo = $controller->{$type}();
//         $data = $controller->getJSONAPI($dbo,$id);
//         if ($data == null) return $response->withStatus(403);

//         $responseData = array( "data" => $data );
//         if ($data['relationships'] != null) {
//             $include = $controller->getIncludes($data['relationships']);
//             $responseData["include"] = $include;
//         }

//         $jsonResponse = $response->withJSON($responseData);

//         $this->logger->addInfo("Sucesso: Read generico ".$uid."|".$userId." - ". $id);
//     } else {
//         $jsonResponse = $response->withStatus(401);

//         $this->logger->addInfo("ERRO: Read generico ".$uid."|".$userId." - ".$id);
//     }

//     return $jsonResponse
//         ->withHeader('Content-Type', 'application/vnd.api+json')
//         ->withHeader('Access-Control-Allow-Origin', '*');  
// });


// // UPDATE
// $app->put('/{type}/{id}', function (Request $request, Response $response, array $args) {
//     $type = $args['type'];
//     $id = $args['id'];

//     $uid = $request->getHeader('user-id')[0];
//     $userId = $request->getHeader('id')[0];
//     // $userType = $request->getHeader('user-type')[0];
//     $requestData = $request->getParsedBody();

//     $method = $request->getMethod();

//     $controller = new DBOController($this->db);
//     $security = new Security($this->db, $userId);

//    if ($security->validarUser($uid) &&
//        $security->userAccess($id,$type,$method)) {

//         $dbo = $controller->{$type}();
//         $this->db->beginTransaction();        
//         try {
//             $dbo->setId($id);

//             if(isset($requestData['data']['relationships'])) {
//                 foreach($requestData['data']['relationships'] as $r) {
//                     $rType = $r['type'];
//                     $rId = $r['id'];
//                     $relationshipDBO = $controller->$rType();
//                     $relationshipDBO->updateAll($rId,$r['attributes']);
//                 }
//             }

//             $dbo->updateAll($id,$requestData['data']['attributes']);
            
//             if(isset($requestData['include'])) {
//                 $relationships = $dbo->getRelationships();
//                 // var_export($relationships);
//                 foreach($requestData['include'] as $i) {
//                     // var_export($i);
//                     $includeDBO = $controller->{$i['type']}();
//                     if (isset($i['id'])) {
//                         $includeID = $i['id'];
//                     } elseif ($i['type'] == 'detalhe') {
//                         $iData = $relationships[$i['attributes']['tipo']]['data'];
//                         if (isset($iData[0])) $iData = $iData[0];
//                         $includeID = $iData['id'];
//                     } else {
//                         $iData = $relationships[$i['type']]['data'];
//                         if (isset($iData[0])) $iData = $iData[0];
//                         $includeID = $iData['id'];
//                     }
//                     // var_export($includeID);
//                     $includeDBO->updateAll($includeID,$i['attributes']);
//                 }
//             }

//             $jsonResponse = $response->withStatus(202);
//             $this->logger->addInfo("Sucesso: Update generico ".$uid."|".$userId." - ". $id);
//             $this->db->commit();
//         } catch(PDOException $e) {
//             $this->logger->addInfo("ERRO: Update generico ".$uid."|".$userId." - ".$id.": ".$e->getMessage());
            
//             $jsonResponse = $response->withStatus(400);
//             $this->db->rollBack();
//         }
//     } else {
//         $this->logger->addInfo("ERRO: Update generico ".$uid."|".$userId." - ".$id.": Acesso não autorizado");        
//         $jsonResponse = $response->withStatus(401);
//     }

//     return $jsonResponse
//         ->withHeader('Content-Type', 'application/vnd.api+json')
//         ->withHeader('Access-Control-Allow-Origin', '*');
// });


// // DELETE

// $app->delete('/{type}/{id}', function (Request $request, Response $response, array $args) {
//     $type = $args['type'];    
//     $id = $args['id'];

//     $uid = $request->getHeader('user-id')[0];
//     $userId = $request->getHeader('id')[0];
//     // $userType = $request->getHeader('user-type')[0];

//     $method = $request->getMethod();

//     $controller = new DBOController($this->db);
//     $security = new Security($this->db, $userId);

//     if ($security->validarUser($uid) &&
//         $security->userAccess($id,$type,$method)) {

//         $dbo = $controller->{$type}();
//         $this->db->beginTransaction();        
//         try {
//             // $dbo->setId($id);
//             if (!$dbo->delete($id)) return $response->withStatus(403);
            
//             $this->logger->addInfo("Sucesso: Delete generico ".$uid."|".$userId." - ". $id);

//             $jsonResponse = $response->withStatus(204);
//             $this->db->commit();
//         } catch(PDOException $e) {
//             $this->logger->addInfo("ERRO: Delete generico ".$uid."|".$userId." - ".$id.": ".$e->getMessage());

//             $jsonResponse = $response->withStatus(400);
//             $this->db->rollBack();
//         }
//     } else {
//         $this->logger->addInfo("ERRO: Delete generico ".$uid."|".$userId." - ".$id.": Acesso não autorizado");        
//         $jsonResponse = $response->withStatus(401);
//     }

//     return $jsonResponse
//         ->withHeader('Content-Type', 'application/vnd.api+json')
//         ->withHeader('Access-Control-Allow-Origin', '*');
// });


