<?php
namespace Service;

use \DBO\InvestidorDBO as Investidor;
use \DBO\InvestimentoDBO as Investimento;
use \DBO\OportunidadeDBO as Oportunidade;
use \DBO\CarteiraDBO as Carteira;

use \DBO\EmpresaDBO as Empresa;
use \DBO\EmprestimoDBO as Emprestimo;
use \DBO\DetalheDBO as Detalhe;

use \DBO\ParcelaDBO as Parcela;

use \DBO\EnderecoDBO as Endereco;
use \DBO\ContaBancariaDBO as ContaBancaria;

use \DBO\PontuacaoDBO as Pontuacao;
use \DBO\AmigoDBO as Amigo;
use \DBO\BeneficioDBO as Beneficio;
use \DBO\ConquistaDBO as Conquista;



class DBOController {
    
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

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
        return array(
            "type" => $dbo->getType(),
            "id" => $id,
            "attributes" => $dbo->getAttributes(),
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
    


    
    public function empresa() {
        return new Empresa($this->db);        
    }

    public function emprestimo() {
        return new Emprestimo($this->db);        
    }

    public function detalhe() {
        return new Detalhe($this->db);        
    }

    public function parcela() {
        return new Parcela($this->db);        
    }
    



    public function investidor() {
        return new Investidor($this->db);        
    }

    public function investimento() {
        return new Investimento($this->db);        
    }

    public function oportunidade() {
        return new Oportunidade($this->db);        
    }

    public function carteira() {
        return new Carteira($this->db);        
    }
    
    // public function movimentacao() {
    //     return new Oportunidade($this->db);        
    // }


    public function endereco() {
        return new Endereco($this->db);
    }

    public function conta_bancaria() {
        return new ContaBancaria($this->db);
    }



    public function pontuacao() {
        return new Pontuacao($this->db);
    }

    public function conquista() {
        return new Conquista($this->db);
    }

    public function beneficio() {
        return new Beneficio($this->db);
    }

    public function amigo() {
        return new Amigo($this->db);
    }
}