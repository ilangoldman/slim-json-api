<?php
namespace DBO\Users;
use \DBO\DBO;
use \DBO\Users\UserDBO as User;

class ProfessorDBO extends DBO {
    private $criado;
    private $modificado;

    public $user;

    public $email; 
    public $nome; 
    public $sobrenome;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("professor");
        $this->setType("professor");
        $this->setFK(["user", "exemplo"]);

        // tabelas que possuem relacao com essa
        // essas tbls tem uma coluna professor q é uma FK para essa tbl
        $this->getRelations(["disciplina"]);
    }

    protected function getSQL() {
        // chama a function original do pai
        // usar quando não se quer fazer um override completo
        $cols = parent::getSQL();
        // datas devem estar com "
        $cols['data_nascimento'] = '"'.$this->formatDate($this->data_nascimento).'"';
        $cols['data_emissao'] = '"'.$this->formatDate($this->data_emissao).'"';
        return $cols;
    }

}