<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Service\DBOController as DBOController;
// use \Service\InvestidorService as InvestidorService;


// Add routing

require __DIR__ . './routes/investidor.php';
require __DIR__ . './routes/empresa.php';
require __DIR__ . './routes/admin.php';

// CREATE

$app->post('/{type}', function (Request $request, Response $response, array $args) {
    $type = $args['type'];

    $uid = $request->getHeader('user-id')[0];
    $userId = $request->getHeader('id')[0];
    $userType = $request->getHeader('user-type')[0];
    $requestData = $request->getParsedBody();

    $method = $request->getMethod();

    $controller = new DBOController($this->db);

    if ($userId != null) {
        if (!$controller->validarUser($uid,$userId,$userType) &&
            !$controller->validarUserAccess($userId,$userType,$id,$type,$method))
            return $response->withStatus(401);
    } elseif ($type == 'investidor' || $type == 'empresa') {
        $requestData['data']['attributes']['user'] = $uid;     
    } else {
        return $response->withStatus(403);
    }
    
    $dbo = $controller->{$type}();       
    $this->db->beginTransaction();
    try {

        if(isset($requestData['data']['relationships'])) {
            foreach($requestData['data']['relationships'] as $r) {
                $rType = $r['type'];
                $relationshipDBO = $controller->$rType();
                $relationshipId = $relationshipDBO->create($r['attributes']);
                $requestData['data']['attributes'][$rType] = $relationshipId;
            }
        }

        $requestData['data']['attributes'][$userType] = $userId;
        $id = $dbo->create($requestData['data']['attributes']);

        if ($id == null) return $response->withStatus(403);
        
        if(isset($requestData['include'])) {
            foreach($requestData['include'] as $i) {
                $includeDBO = $controller->{$i['type']}();
                $i['attributes'][$type] = $id;
                // var_export($i);
                $includeId = $includeDBO->create($i['attributes']);
            }
        }
        // $data = $controller->getJSONAPI($dbo,$id);
        
        // $responseData = array( "data" => $data );
        // withJSON($responseData)
        $url = $request->getUri()->getPath() . "/" . $id;
        $jsonResponse = $response->withStatus(201)->withHeader('location', $url);
        
        $this->logger->addInfo("Sucesso: Cadastro Generico ".$uid." - ". $data['id']);

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


// READ

$app->get('/{type}/{id}', function (Request $request, Response $response, array $args) {
    $type = $args['type'];    
    $id = $args['id'];

    $uid = $request->getHeader('user-id')[0] ?? null;
    $userId = $request->getHeader('id')[0] ?? null;
    $userType = $request->getHeader('user-type')[0] ?? null;

    $method = $request->getMethod();

    $controller = new DBOController($this->db);

    if(!isset($userType) || !isset($userId)) return $response->withStatus(403);

    if ($controller->validarUser($uid,$userId,$userType) && 
        $controller->validarUserAccess($userId,$userType,$id,$type,$method)) {

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
$app->put('/{type}/{id}', function (Request $request, Response $response, array $args) {
    $type = $args['type'];
    $id = $args['id'];

    $uid = $request->getHeader('user-id')[0];
    $userId = $request->getHeader('id')[0];
    $userType = $request->getHeader('user-type')[0];
    $requestData = $request->getParsedBody();

    $method = $request->getMethod();

    $controller = new DBOController($this->db);

   if ($controller->validarUser($uid,$userId,$userType) &&
        $controller->validarUserAccess($userId,$userType,$id,$type,$method)) {

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

            //$data = $controller->getJSONAPI($dbo,$id);
            
            //$responseData = array( "data" => $data );
            //$jsonResponse = $response->withJSON($responseData);
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

$app->delete('/{type}/{id}', function (Request $request, Response $response, array $args) {
    $type = $args['type'];    
    $id = $args['id'];

    $uid = $request->getHeader('user-id')[0];
    $userId = $request->getHeader('id')[0];
    $userType = $request->getHeader('user-type')[0];

    $method = $request->getMethod();

    $controller = new DBOController($this->db);

    if ($controller->validarUser($uid,$userId,$userType) &&
        $controller->validarUserAccess($userId,$userType,$id,$type,$method)) {

        $dbo = $controller->{$type}();
        $this->db->beginTransaction();        
        try {
            // $dbo->setId($id);
            $dbo->delete($id);
            
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


