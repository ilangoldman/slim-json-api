<?php
namespace DBO\Users;
use \DBO\DBO;

class EmpresaDBO extends DBO {  
    private $criado;
    private $modificado;

    private $user;
    // private $endereco;
    // private $conta_bancaria;

    private $email; 
    private $nome; 
    private $sobrenome; 
    private $razao_social; 
    private $nome_fantasia; 
    private $cnpj; 
    private $telefone_comercial; 
    private $fundacao; 
    private $funcionarios; 
    private $grupo_economico; 
    private $pagina_web; 
    private $rede_social; 
    private $resumo_descricao; 
    private $descricao; 
    private $setor; 
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("empresa");
        $this->setType("empresa");        
    }
    
    // helpers

    protected function addCol($info) {
        $this->user = $info['user'];
        // $this->endereco = $info['endereco'];
        // $this->conta_bancaria = $info['conta_bancaria'];

        $this->email = filter_var($info['email'],FILTER_SANITIZE_STRING); 
        $this->nome = filter_var($info['nome'],FILTER_SANITIZE_STRING); 
        $this->sobrenome = filter_var($info['sobrenome'],FILTER_SANITIZE_STRING); 
        $this->razao_social = filter_var($info['razao_social'],FILTER_SANITIZE_STRING); 
        $this->nome_fantasia = filter_var($info['nome_fantasia'],FILTER_SANITIZE_STRING); 
        $this->cnpj = filter_var($info['cnpj'],FILTER_SANITIZE_STRING);
        $this->telefone_comercial = filter_var($info['telefone_comercial'],FILTER_SANITIZE_STRING); 
        $this->fundacao = $this->formatDate(filter_var($info['fundacao'],FILTER_SANITIZE_STRING)); 
        $this->funcionarios = filter_var($info['funcionarios'],FILTER_SANITIZE_NUMBER_INT); 
        $this->grupo_economico = filter_var($info['grupo_economico'],FILTER_SANITIZE_STRING); 
        $this->pagina_web = filter_var($info['pagina_web'],FILTER_SANITIZE_STRING); 
        $this->rede_social = filter_var($info['rede_social'],FILTER_SANITIZE_STRING); 
        $this->resumo_descricao = filter_var($info['resumo_descricao'],FILTER_SANITIZE_STRING); 
        $this->descricao = filter_var($info['descricao'],FILTER_SANITIZE_STRING); 
        $this->setor = filter_var($info['setor'],FILTER_SANITIZE_STRING); 
    }

    protected function getCol() {
        return array(
            "user" => $this->user,
            // "endereco" => $this->endereco,
            // "conta_bancaria" => $this->conta_bancaria,

            "email" => $this->email, 
            "nome" => $this->nome, 
            "sobrenome" => $this->sobrenome, 
            "razao_social" => $this->razao_social, 
            "nome_fantasia" => $this->nome_fantasia, 
            "cnpj" => $this->cnpj, 
            "telefone_comercial" => $this->telefone_comercial, 
            "fundacao" => $this->fundacao, 
            "funcionarios" => $this->funcionarios, 
            "grupo_economico" => $this->grupo_economico, 
            "pagina_web" => $this->pagina_web, 
            "rede_social" => $this->rede_social, 
            "resumo_descricao" => $this->resumo_descricao, 
            "descricao" => $this->descricao, 
            "setor" => $this->setor
        );
    }

    protected function getSqlCol() {
        return array(
            "user" => '"'.$this->user.'"',
            // "endereco" => '"'.$this->endereco.'"',
            // "conta_bancaria" => '"'.$this->conta_bancaria.'"',

            "email" => '"'.$this->email.'"', 
            "nome" => '"'.$this->nome.'"', 
            "sobrenome" => '"'.$this->sobrenome.'"', 
            "razao_social" => '"'.$this->razao_social.'"', 
            "nome_fantasia" => '"'.$this->nome_fantasia.'"', 
            "cnpj" => '"'.$this->cnpj.'"', 
            "telefone_comercial" => '"'.$this->telefone_comercial.'"', 
            "fundacao" => '"'.$this->fundacao.'"', 
            "funcionarios" => $this->funcionarios, 
            "grupo_economico" => '"'.$this->grupo_economico.'"', 
            "pagina_web" => '"'.$this->pagina_web.'"', 
            "rede_social" => '"'.$this->rede_social.'"', 
            "resumo_descricao" => '"'.$this->resumo_descricao.'"', 
            "descricao" => '"'.$this->descricao.'"', 
            "setor" => '"'.$this->setor.'"'
        );
    }

    public function getRelationships() {
        // $sql = "SELECT endereco, conta_bancaria".
        //     " FROM ".$this->table_name.
        //     " WHERE ".$this->table_name." = ".$this->id;

        // $stmt = $this->db->query($sql);
        // if ($row = $stmt->fetch()) {
        //     foreach ($row as $k => $v) {
        //         $dbo = $this->controller->{$k}();   
        //         $response[$dbo->getType()] = array(
        //                 "data" => array(
        //                     "type" => $dbo->getType(),
        //                     "id" => $v
        //             )
        //         );
        //     }
        // }
        $fk = ["endereco","conta_bancaria","notificacao","amigo"];
        $response = array();
        foreach($fk as $v) {
            // array_push($response,$this->getTablesFK($fk));
            $tableFK = $this->getTablesFK($v);
            if ($tableFK == NULL) continue;
            $response[$v] = $tableFK;             
        }

        return $response;
    }

    // CREATE

    // READ
    public function readFK($id) {
        $this->addId($id);

        $sql = "SELECT user, endereco, conta_bancaria ".
            " FROM ".$this->table_name.
            " WHERE ".$this->table_name." = ".$this->id;

        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            return $row;
        }

        return null;
    }

    public function readUserId($id) {
        $this->addId($id);
        $user = "";

        $sql = "SELECT user".
            " FROM ".$this->table_name.
            " WHERE ".$this->table_name." = ".$this->id;
        // var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            extract($row);
            // var_export($row);
        }

        // var_export($user);
        return $user;
    }

    // UPDATE
    public function updateDescricao($id,$descricao) {
        $set = "descricao = ".$this->descricao;
        return $this->update($id,$set);
    }
}