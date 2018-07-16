<?php
namespace Controller;

// Users
use \DBO\Users\ProfessorDBO as Professor;


class ModelController {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    // Usar essa classe para declarar todos os DBOs
    // Desse jeito nao e necessario declarar em mais nenhum lugar do codigo
    // chamar as classes usando:
    // $modelController->{'professor'}() ou $modelController->professor()

    public function professor() {
        return new Professor($this->db);        
    }

}