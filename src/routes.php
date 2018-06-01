<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// TODO: Routing function
// TODO: REST Classes that takes the db 

// HINT: create a log for the server
// $this->logger->addInfo('Log message'); 

// get params
// $params = $request->getQueryParams()

// post body data
// $data = $request->getParsedBody();
// $ticket_data = [];
// $ticket_data['title'] = filter_var($data['title'], FILTER_SANITIZE_STRING);
// $ticket_data['description'] = filter_var($data['description'], FILTER_SANITIZE_STRING);

// Add routing

require __DIR__ . './routes/investidor.php';
require __DIR__ . './routes/empresa.php';

$app->get('/user/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];

    $this->logger->addInfo("get user ". $id);

    $dbo = new Investidor($this->db);
    $user = $dbo->getUser($id);

    $data = array(
        "data" => $user
    );

    $jsonResponse = $response->withJSON($data);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});



$app->get('/emprestimos', function (Request $request, Response $response, array $args) {
    $this->logger->addInfo("Emprestimo Request");

    $dbo = new Emprestimos($this->db);
    $emprestimos = $dbo->getEmprestimos();

    $jsonResponse = $response->withJSON($emprestimos);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});

$app->get('/emprestimos/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];

    $this->logger->addInfo("Emprestimo " . $id . " Request");
    $dbo = new Emprestimos($this->db);
    $emprestimo = $dbo->getEmprestimoById($id);

    $jsonResponse = $response->withJSON($emprestimo);
    return $jsonResponse
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');  
});


// $app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
//     $name = $args['name'];
//     $response->getBody()->write("Hello, $name");
    
//     return $response;
// });
