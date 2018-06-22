<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Service\DBOController as DBOController;
use \Service\Security as Security;
use \DBO\Users\UserDBO as User;

$app->post('/{_:user|empresa|investidor}', function (Request $request, Response $response, array $args) {
    // $type = $args['type'];

    $uid = $request->getHeader('user-id')[0];
    // $userId = $request->getHeader('id')[0];
    // $userType = $request->getHeader('user-type')[0];
    $requestData = $request->getParsedBody();

    $method = $request->getMethod();

    $controller = new DBOController($this->db);

    if (!isset($requestData['data']['type'])) return $response->withStatus(400);
    $tipo = $requestData['data']['type'];
    
    $userData = array(
        "tipo" => $tipo,
        "uid" => $uid
    );
    
    $dbo = $controller->{$tipo}();
    $this->db->beginTransaction();
    try {
        $user = new User($this->db);
        $userId = $user->create($userData);
        
        if ($userId == null) return $response->withStatus(403);
        
        $requestData['data']['attributes']['user'] = $userId;
        $id = $dbo->create($requestData['data']['attributes']);

        if ($id == null) return $response->withStatus(403);
        
        if(isset($requestData['include'])) {
            foreach($requestData['include'] as $i) {
                $includeDBO = $controller->{$i['type']}();
                $i['attributes']['user'] = $userId;
                $includeId = $includeDBO->create($i['attributes']);
            }
        }
       
        $url = $request->getUri()->getPath() . "/" . $userId;
        $jsonResponse = $response->withStatus(201)->withHeader('location', $url);
        
        $this->logger->addInfo("Sucesso: Cadastro User ".$uid." - ". $data['id']);

        $this->db->commit();
    } catch(PDOException $e) {
        $this->logger->addInfo("ERRO: Cadastro User ".$uid.": ".$e->getMessage());
        
        $jsonResponse = $response->withStatus(400);
        $this->db->rollBack();
    }
        
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});



// READ

$app->get('/{type:user|empresa|investidor}/{id}', function (Request $request, Response $response, array $args) {
    // $type = $args['type']; 
    $id = $args['id'];

    $uid = $request->getHeader('user-id')[0] ?? null;
    $userId = $request->getHeader('id')[0] ?? null;
    // $userType = $request->getHeader('user-type')[0] ?? null;

    $method = $request->getMethod();
    
    if(!isset($uid) || !isset($userId)) return $response->withStatus(403);
    
    $controller = new DBOController($this->db);
    $security = new Security($this->db, $userId);

    $user = new User($this->db);
    $user->read($id);
    $type = $user->getTipo();
    $id = $user->{$type}();

    if ($security->validarUser($uid) && 
        $security->userAccess($id,$type,$method)) {

        $dbo = $controller->{$type}();
        $data = $controller->getJSONAPI($dbo,$id);
        if ($data == null) return $response->withStatus(403);

        $responseData = array( "data" => $data );
        if ($data['relationships'] != null) {
            $include = $controller->getIncludes($data['relationships']);
            $responseData["include"] = $include;
        }

        $jsonResponse = $response->withJSON($responseData);

        $this->logger->addInfo("Sucesso: Read generico ".$uid."|".$userId." - ". $id);
    } else {
        $jsonResponse = $response->withStatus(401);

        $this->logger->addInfo("ERRO: Read generico ".$uid."|".$userId." - ".$id);
    }

    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});


// UPDATE
$app->put('/{type:user|empresa|investidor}/{id}', function (Request $request, Response $response, array $args) {
    $type = $args['type'];
    $id = $args['id'];

    $uid = $request->getHeader('user-id')[0];
    $userId = $request->getHeader('id')[0];
    // $userType = $request->getHeader('user-type')[0];
    $requestData = $request->getParsedBody();

    $method = $request->getMethod();

    $controller = new DBOController($this->db);
    $security = new Security($this->db, $userId);

    $user = new User($this->db);
    $user->read($id);
    $type = $user->getTipo();
    $id = $user->{$type};

   if ($security->validarUser($uid) &&
       $security->userAccess($id,$type,$method)) {

        $dbo = $controller->{$type}();
        $this->db->beginTransaction();        
        try {
            $dbo->setId($id);

            if(isset($requestData['data']['relationships'])) {
                foreach($requestData['data']['relationships'] as $r) {
                    $rType = $r['type'];
                    $rId = $r['id'];
                    $relationshipDBO = $controller->$rType();
                    $relationshipDBO->updateAll($rId,$r['attributes']);
                }
            }

            $dbo->updateAll($id,$requestData['data']['attributes']);
            
            if(isset($requestData['include'])) {
                $relationships = $dbo->getRelationships();
                // var_export($relationships);
                foreach($requestData['include'] as $i) {
                    // var_export($i);
                    $includeDBO = $controller->{$i['type']}();
                    if (isset($i['id'])) {
                        $includeID = $i['id'];
                    } elseif ($i['type'] == 'detalhe') {
                        $iData = $relationships[$i['attributes']['tipo']]['data'];
                        if (isset($iData[0])) $iData = $iData[0];
                        $includeID = $iData['id'];
                    } else {
                        $iData = $relationships[$i['type']]['data'];
                        if (isset($iData[0])) $iData = $iData[0];
                        $includeID = $iData['id'];
                    }
                    // var_export($includeID);
                    $includeDBO->updateAll($includeID,$i['attributes']);
                }
            }

            $jsonResponse = $response->withStatus(202);
            $this->logger->addInfo("Sucesso: Update generico ".$uid."|".$userId." - ". $id);
            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Update generico ".$uid."|".$userId." - ".$id.": ".$e->getMessage());
            
            $jsonResponse = $response->withStatus(400);
            $this->db->rollBack();
        }
    } else {
        $this->logger->addInfo("ERRO: Update generico ".$uid."|".$userId." - ".$id.": Acesso não autorizado");        
        $jsonResponse = $response->withStatus(401);
    }

    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});


// DELETE

$app->delete('/{type:user|empresa|investidor}/{id}', function (Request $request, Response $response, array $args) {
    $type = $args['type'];    
    $id = $args['id'];

    $uid = $request->getHeader('user-id')[0];
    $userId = $request->getHeader('id')[0];
    // $userType = $request->getHeader('user-type')[0];

    $method = $request->getMethod();

    $controller = new DBOController($this->db);
    $security = new Security($this->db, $userId);

    $user = new User($this->db);
    $user->read($id);
    $type = $user->getTipo();
    $id = $user->{$type};

    if ($security->validarUser($uid) &&
        $security->userAccess($id,$type,$method)) {

        $dbo = $controller->{$type}();
        $this->db->beginTransaction();        
        try {
            // $dbo->setId($id);
            if (!$dbo->delete($id)) return $response->withStatus(403);
            
            $this->logger->addInfo("Sucesso: Delete generico ".$uid."|".$userId." - ". $id);

            $jsonResponse = $response->withStatus(204);
            $this->db->commit();
        } catch(PDOException $e) {
            $this->logger->addInfo("ERRO: Delete generico ".$uid."|".$userId." - ".$id.": ".$e->getMessage());

            $jsonResponse = $response->withStatus(400);
            $this->db->rollBack();
        }
    } else {
        $this->logger->addInfo("ERRO: Delete generico ".$uid."|".$userId." - ".$id.": Acesso não autorizado");        
        $jsonResponse = $response->withStatus(401);
    }

    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});

