<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// use \Service\DBOController as DBOController;

// Controllers
use \Controller\UserController as UserController;
// criar os controllers materia, notas e etc. para separar a logica de cada grupo de rotas

// Routes

// route generica para um user (prof ou aluno)
$app->group('/{_:user|professor|aluno}/{uid}', function () {

    // call controller
    // CRUD User
    $this->post('', UserController::class . ':novo');
    $this->get('', UserController::class . ':detalheUser');
    $this->put('', UserController::class . ':atualizar');
    $this->delete('', UserController::class . ':deletar');
});

// especifico do professor
$app->group('/professor/{uid}', function () {
    
    // CRUD recebiveis
    $this->group('/disciplina', function() {
        $this->post('', UserController::class . ':adcMateria');
        $this->get('', UserController::class . ':todasMaterias');
        $this->get('/{id}', UserController::class . ':detalheMateria');
        $this->put('/{id}', UserController::class . ':mudarMateria');
        $this->delete('/{id}', UserController::class . ':removerMateria');

        // pode-se colocar group dentro de group para facilitar a organizacao
        // a route para chegar aqui vai ser a soma de todos os group anteriores + a route de dentro do method
        $this->group('/nota', function() {
            $this->post('', UserController::class . ':adcNota');
            $this->get('', UserController::class . ':todasNotas');
            // $this->get('/{id}', UserController::class . ':detalheNota');
            $this->put('/{id}', UserController::class . ':mudarNota');
            $this->delete('/{id}', UserController::class . ':removerNota');
        });
    });
});

// especifico do aluno
$app->group('/aluno/{uid}', function () {

    $this->group('/disciplina', function() {
        // $this->post('', UserController::class . ':adcMateria');
        $this->get('', UserController::class . ':todasMaterias');
        $this->get('/{id}', UserController::class . ':detalheMateria');
        // $this->put('/{id}', UserController::class . ':mudarMateria');
        // $this->delete('/{id}', UserController::class . ':removerMateria');
    });

    $this->get('/notas', UserController::class . ':notas');
});

// especifico para admin
$app->group('/admin/{uid}', function () {
    // TODO
});
