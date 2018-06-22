<?php
namespace DBO\Users;
use \DBO\DBO;
use \DBO\Users\UserDBO as User;

class EmpresaDBO extends DBO {  
    private $criado;
    private $modificado;

    public $user;

    public $email; 
    public $nome; 
    public $sobrenome; 
    public $razao_social; 
    public $nome_fantasia; 
    public $cnpj; 
    public $telefone_comercial; 
    public $fundacao; 
    public $funcionarios; 
    public $grupo_economico; 
    public $pagina_web; 
    public $rede_social; 
    public $resumo_descricao; 
    public $descricao; 
    public $setor; 
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("empresa");
        $this->setType("empresa");
        $this->setFK(["user"]);
        $this->setRelations(["emprestimo"]);
    }

    protected function getSQL() {
        $cols = parent::getSQL();
        $cols['fundacao'] = '"'.$this->formatDate($this->fundacao).'"';
        return $cols;
    }

}










    // [DEPRECATED]


    // DELETE
    // public function delete($id) {
    //     $dbo = $this->controller->emprestimo();
    //     if ($dbo->temEmprestimoAtivo($id)) return false;

    //     $this->read($id);
    //     $set = "user = null";

    //     $user = new User($this->db);
    //     $id = $this->user;
    //     $this->update($id,$set);

    //     return $user->delete($id);
    // }





    
    // helpers

    // protected function setCol($info) {
    //     $this->user = $info['user'];

    //     $this->email = filter_var($info['email'],FILTER_SANITIZE_STRING); 
    //     $this->nome = filter_var($info['nome'],FILTER_SANITIZE_STRING); 
    //     $this->sobrenome = filter_var($info['sobrenome'],FILTER_SANITIZE_STRING); 
    //     $this->razao_social = filter_var($info['razao_social'],FILTER_SANITIZE_STRING); 
    //     $this->nome_fantasia = filter_var($info['nome_fantasia'],FILTER_SANITIZE_STRING); 
    //     $this->cnpj = filter_var($info['cnpj'],FILTER_SANITIZE_STRING);
    //     $this->telefone_comercial = filter_var($info['telefone_comercial'],FILTER_SANITIZE_STRING); 
    //     $this->fundacao = $this->formatDate(filter_var($info['fundacao'],FILTER_SANITIZE_STRING)); 
    //     $this->funcionarios = filter_var($info['funcionarios'],FILTER_SANITIZE_NUMBER_INT); 
    //     $this->grupo_economico = filter_var($info['grupo_economico'],FILTER_SANITIZE_STRING); 
    //     $this->pagina_web = filter_var($info['pagina_web'],FILTER_SANITIZE_STRING); 
    //     $this->rede_social = filter_var($info['rede_social'],FILTER_SANITIZE_STRING); 
    //     $this->resumo_descricao = filter_var($info['resumo_descricao'],FILTER_SANITIZE_STRING); 
    //     $this->descricao = filter_var($info['descricao'],FILTER_SANITIZE_STRING); 
    //     $this->setor = filter_var($info['setor'],FILTER_SANITIZE_STRING); 
    // }

    // protected function getCol() {
    //     return array(
    //         "user" => $this->user,

    //         "email" => $this->email, 
    //         "nome" => $this->nome, 
    //         "sobrenome" => $this->sobrenome, 
    //         "razao_social" => $this->razao_social, 
    //         "nome_fantasia" => $this->nome_fantasia, 
    //         "cnpj" => $this->cnpj, 
    //         "telefone_comercial" => $this->telefone_comercial, 
    //         "fundacao" => $this->fundacao, 
    //         "funcionarios" => $this->funcionarios, 
    //         "grupo_economico" => $this->grupo_economico, 
    //         "pagina_web" => $this->pagina_web, 
    //         "rede_social" => $this->rede_social, 
    //         "resumo_descricao" => $this->resumo_descricao, 
    //         "descricao" => $this->descricao, 
    //         "setor" => $this->setor
    //     );
    // }

    // protected function getSqlCol() {
    //     $cols = $this->getCol();
    //     $cols["email"] = '"'.$this->email.'"'; 
    //     $cols["nome"] = '"'.$this->nome.'"'; 
    //     $cols["sobrenome"] = '"'.$this->sobrenome.'"'; 
    //     $cols["razao_social"] = '"'.$this->razao_social.'"'; 
    //     $cols["nome_fantasia"] = '"'.$this->nome_fantasia.'"'; 
    //     $cols["cnpj"] = '"'.$this->cnpj.'"'; 
    //     $cols["telefone_comercial"] = '"'.$this->telefone_comercial.'"'; 
    //     $cols["fundacao"] = '"'.$this->fundacao.'"';
    //     $cols["grupo_economico"] = '"'.$this->grupo_economico.'"'; 
    //     $cols["pagina_web"] = '"'.$this->pagina_web.'"'; 
    //     $cols["rede_social"] = '"'.$this->rede_social.'"'; 
    //     $cols["resumo_descricao"] = '"'.$this->resumo_descricao.'"'; 
    //     $cols["descricao"] = '"'.$this->descricao.'"'; 
    //     $cols["setor"] = '"'.$this->setor.'"';
    //     return $cols;
    // }

    // public function getRelationships() {
    //     $u = new User($this->db);
    //     $u->setId($this->user);
    //     $response = $u->getRelationships();

    //     // TODO
    //     // add emprestimos ??

    //     return $response;
    // }