<?php
namespace DBO;

class InvestidorDBO extends DBO {
    private $criado;
    private $modificado;

    private $user;
    private $pontuacao;
    // private $endereco;
    // private $conta_bancaria;
    
    private $pontos; 
    private $email; 
    private $nome; 
    private $sobrenome; 
    private $sexo; 
    private $estado_civil; 
    private $nome_mae; 
    private $nome_pai; 
    private $tel1; 
    private $tel2; 
    private $naturalidade; 
    private $nacionalidade; 
    private $cpf; 
    private $data_nascimento; 
    private $rg; 
    private $orgao_emissor; 
    private $estado_emissor; 
    private $data_emissao; 
    private $renda_mensal; 
    private $patrimonio; 
    private $ppe;
    private $doc_id; 
    private $doc_residencia; 
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("investidor");
        $this->setType("investidor");
    }

    // helpers

    protected function addCol($info) {
        $this->user = $info['user'];
        $this->pontuacao = (array_key_exists('pontuacao', $info)) ? filter_var($info['pontuacao'],FILTER_SANITIZE_NUMBER_INT) : 1;
        // $this->endereco = $info['endereco'];
        // $this->conta_bancaria = $info['conta_bancaria'];

        $this->pontos = (array_key_exists('pontos', $info)) ? filter_var($info['pontos'],FILTER_SANITIZE_NUMBER_INT) : 10;
        $this->email = filter_var($info['email'],FILTER_SANITIZE_EMAIL);
        $this->nome = filter_var($info['nome'],FILTER_SANITIZE_STRING);
        $this->sobrenome = filter_var($info['sobrenome'],FILTER_SANITIZE_STRING);
        $this->sexo = filter_var($info['sexo'],FILTER_SANITIZE_STRING);
        $this->estado_civil = filter_var($info['estado_civil'],FILTER_SANITIZE_STRING);
        $this->nome_mae = filter_var($info['nome_mae'],FILTER_SANITIZE_STRING);
        $this->nome_pai = filter_var($info['nome_pai'],FILTER_SANITIZE_STRING);
        $this->tel1 = filter_var($info['tel1'],FILTER_SANITIZE_NUMBER_INT);
        $this->tel2 = filter_var($info['tel2'],FILTER_SANITIZE_NUMBER_INT);
        $this->naturalidade = filter_var($info['naturalidade'],FILTER_SANITIZE_STRING);
        $this->nacionalidade = filter_var($info['nacionalidade'],FILTER_SANITIZE_STRING);
        $this->cpf = filter_var($info['cpf'],FILTER_SANITIZE_NUMBER_INT);
        $this->data_nascimento = $this->formatDate(filter_var($info['data_nascimento'],FILTER_SANITIZE_STRING));
        $this->rg = filter_var($info['rg'],FILTER_SANITIZE_NUMBER_INT);
        $this->orgao_emissor = filter_var($info['orgao_emissor'],FILTER_SANITIZE_STRING);
        $this->estado_emissor = filter_var($info['estado_emissor'],FILTER_SANITIZE_STRING);
        $this->data_emissao = $this->formatDate(filter_var($info['data_emissao'],FILTER_SANITIZE_STRING));
  
        $this->renda_mensal = filter_var($info['renda_mensal'],FILTER_SANITIZE_NUMBER_FLOAT);
        $this->patrimonio = filter_var($info['patrimonio'],FILTER_SANITIZE_NUMBER_FLOAT);
        $this->ppe = (filter_var($info['ppe'],FILTER_VALIDATE_BOOLEAN))?1:0;
        
        $this->doc_id = filter_var($info['doc_id'],FILTER_SANITIZE_STRING);
        $this->doc_residencia = filter_var($info['doc_residencia'],FILTER_SANITIZE_STRING);
    }

    protected function getCol() {
        return array(
            "user" => $this->user,
            // "pontuacao" => $this->pontuacao,
            // "endereco" => $this->endereco,
            // "conta_bancaria" => $this->conta_bancaria,     
            "pontos" => $this->pontos, 
            "email" => $this->email, 
            "nome" => $this->nome, 
            "sobrenome" => $this->sobrenome, 
            "sexo" => $this->sexo, 
            "estado_civil" => $this->estado_civil, 
            "nome_mae" => $this->nome_mae, 
            "nome_pai" => $this->nome_pai, 
            "tel1" => $this->tel1, 
            "tel2" => $this->tel2, 
            "naturalidade" => $this->naturalidade, 
            "nacionalidade" => $this->nacionalidade, 
            "cpf" => $this->cpf, 
            "data_nascimento" => $this->data_nascimento, 
            "rg" => $this->rg, 
            "orgao_emissor" => $this->orgao_emissor, 
            "estado_emissor" => $this->estado_emissor, 
            "data_emissao" => $this->data_emissao, 
            "renda_mensal" => $this->renda_mensal, 
            "patrimonio" => $this->patrimonio, 
            "ppe" => $this->ppe,
            "doc_id" => $this->doc_id, 
            "doc_residencia" => $this->doc_residencia
        );
    }

    protected function getSqlCol() {
        return array(
            "user" => '"'.$this->user.'"',
            "pontuacao" => $this->pontuacao,
            // "endereco" => $this->endereco,
            // "conta_bancaria" => $this->conta_bancaria,     
            "pontos" => $this->pontos, 
            "email" => '"'.$this->email.'"', 
            "nome" => '"'.$this->nome.'"', 
            "sobrenome" => '"'.$this->sobrenome.'"', 
            "sexo" => '"'.$this->sexo.'"', 
            "estado_civil" => '"'.$this->estado_civil.'"', 
            "nome_mae" => '"'.$this->nome_mae.'"', 
            "nome_pai" => '"'.$this->nome_pai.'"', 
            "tel1" => '"'.$this->tel1.'"', 
            "tel2" => '"'.$this->tel2.'"', 
            "naturalidade" => '"'.$this->naturalidade.'"', 
            "nacionalidade" => '"'.$this->nacionalidade.'"', 
            "cpf" => '"'.$this->cpf.'"', 
            "data_nascimento" => '"'.$this->data_nascimento.'"', 
            "rg" => '"'.$this->rg.'"', 
            "orgao_emissor" => '"'.$this->orgao_emissor.'"', 
            "estado_emissor" => '"'.$this->estado_emissor.'"', 
            "data_emissao" => '"'.$this->data_emissao.'"', 
            "renda_mensal" => $this->renda_mensal, 
            "patrimonio" => $this->patrimonio, 
            "ppe" => $this->ppe,
            "doc_id" => '"'.$this->doc_id.'"', 
            "doc_residencia" => '"'.$this->doc_residencia.'"'
        );
    }


    public function getRelationships() {
        $sql = "SELECT pontuacao".
            " FROM ".$this->table_name.
            " WHERE ".$this->table_name." = ".$this->id;
        // var_export($sql);
        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            foreach ($row as $k => $v) {
                // var_export($k."|".$v);                
                $dbo = $this->controller->{$k}();
                $response[$dbo->getType()] = array(
                        "data" => array(
                            "type" => $dbo->getType(),
                            "id" => $v
                    )
                );
            }
        }

        $fk = ["endereco","conta_bancaria","amigo","notificacao","investimento"];
        // $response = array();
        foreach($fk as $v) {
            $tblFK = $this->getTablesFK($v);
            if ($tblFK == NULL) continue;
            $response[$v] = $tblFK;
        }
        
        // var_export($response);
        return $response;
    }

    // CREATE
    public function addConquista($type,$id) {
        $sql = "INSERT INTO ".$this->table_name."_".$type.
               " (".$this->table_name.",".$type.")".
               " VALUES (".$this->id.",".$id.");";
        $stmt = $this->db->exec($sql);
        return ($stmt > 0);
    }

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

        $stmt = $this->db->query($sql);
        if ($row = $stmt->fetch()) {
            extract($row);
        }

        return $user;
    }

    // UPDATE
    public function updatePontos($id,$pontos) {
        $set = "pontos = ".$this->pontos;
        return $this->update($id,$set);
    }

    public function updatePontuacao($id,$pontuacao) {
        $set = "pontuacao = ".$this->pontuacao;
        return $this->update($id,$set);
    }

}