<?php
namespace Service;

// Gamefication
use \DBO\Gamefication\PontuacaoDBO as Pontuacao;
use \DBO\Gamefication\BeneficioDBO as Beneficio;
use \DBO\Gamefication\RecompensaDBO as Recompensa;
use \DBO\Gamefication\ConquistaDBO as Conquista;
use \DBO\Gamefication\AtividadeDBO as Atividade;

// Investimentos
use \DBO\Investimento\EmprestimoDBO as Emprestimo;
use \DBO\Investimento\DetalheDBO as Detalhe;
use \DBO\Investimento\InvestimentoDBO as Investimento;
use \DBO\Investimento\OportunidadeDBO as Oportunidade;
use \DBO\Investimento\ParcelaDBO as Parcela;

// Notificaoes
use \DBO\Notificacao\NotificacaoDBO as Notificacao;
use \DBO\Notificacao\MensagemDBO as Mensagem;

// Users
use \DBO\Users\EmpresaDBO as Empresa;
use \DBO\Users\InvestidorDBO as Investidor;
use \DBO\Users\EnderecoDBO as Endereco;
use \DBO\Users\ContaBancariaDBO as ContaBancaria;
use \DBO\Users\AmigoDBO as Amigo;


class DBOController {
    
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }


    // security validation
    public function validarUser($userId, $id, $type) {
        if ($type == 'investidor') {
            $investidor = new Investidor($this->db);
            $user = $investidor->readUserId($id);
        } else if ($type == 'empresa') {
            $empresa = new Empresa($this->db);
            $user = $empresa->readUserId($id);
        } else {
            $user = null;
        }

        return ($user == $userId);
    }
    
    public function validarUserAccess($userId,$userType,$id,$type,$method) {
        // TODO
        // validar se o usuario pode acessar a classe q ele quer

        if ($userType == $type && $userId == $id) {
            $allowAccess = true;
        } else {
            $dbo = $this->{$type}();
            $allowAccess = $dbo->allowAccess($userId,$userType,$id,$method);
        }

        return $allowAccess;
    }

    public function validarAdmin($userId) {
        // TODO
        // validar se o usuario pode acessar a classe q ele quer
        return ($userId == 'admin');
    }

    public function allowEmpresaOnly($type) {
        return ($type == 'empresa');
    }

    public function allowInvestidorOnly($type) {
        return ($type == 'investidor');
    }


    // api
    public function getAllJSONAPI($dbo) {
        $data = $dbo->readAll();
        // var_export($data);
        foreach($data as $d) {
            $type = $dbo->getType();
            $id = $d[$type];
            unset($d[$type]);
            $result[] = array(
                "type" => $type,
                "id" => $id,
                "attributes" => $d
            );
        }

        return $result;
    }

    public function getJSONAPI($dbo,$id) {
        $dbo->setId($id);
        $attributes = $dbo->getAttributes();
        if ($attributes == null) return null;
        return array(
            "type" => $dbo->getType(),
            "id" => $id,
            "attributes" => $attributes,
            "relationships" => $dbo->getRelationships()
        );
    }
    
    public function getIncludes($relationships) {
        $include = array();
        // var_export($relationships);
        foreach($relationships as $k => $r) {  
            if ($r == NULL) continue;      
            $data = $r['data'];
            // var_export($k);
            // $dbo = $this->{$k}();
            if (isset($data[0])) {
                foreach($data as $d) {
                    $dbo = $this->{$d['type']}();
                    // var_export($d);
                    array_push($include,$this->getJSONAPI($dbo,$d['id']));  
                }
            } else {
                // var_export($data['type']);
                $dbo = $this->{$data['type']}();                
                array_push($include,$this->getJSONAPI($dbo,$data['id']));
            }
        }
        
        return $include;
    }
    
    // Gamefication

    public function pontuacao() {
        return new Pontuacao($this->db);
    }

    public function atividade() {
        return new Atividade($this->db);
    }

    public function Recompensa() {
        return new recompensa($this->db);
    }


    // Investimento

    public function emprestimo() {
        return new Emprestimo($this->db);        
    }

    public function detalhe() {
        return new Detalhe($this->db);        
    }

    public function investimento() {
        return new Investimento($this->db);        
    }

    public function oportunidade() {
        return new Oportunidade($this->db);        
    }

    public function parcela() {
        return new Parcela($this->db);        
    }

    // public function movimentacao() {
    //     return new Movimentacao($this->db);        
    // }


    // Notificacao
    public function mensagem() {
        return new Mensagem($this->db);        
    }


    // Users

    public function empresa() {
        return new Empresa($this->db);        
    }

    public function investidor() {
        return new Investidor($this->db);        
    }

    public function endereco() {
        return new Endereco($this->db);
    }

    public function conta_bancaria() {
        return new ContaBancaria($this->db);
    }

    public function amigo() {
        return new Amigo($this->db);
    }

}