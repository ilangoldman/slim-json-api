<?php
namespace Controller;

// Users
use \DBO\Users\EmpresaDBO as Empresa;
use \DBO\Users\InvestidorDBO as Investidor;
use \DBO\Users\EnderecoDBO as Endereco;
use \DBO\Users\ContaBancariaDBO as ContaBancaria;
use \DBO\Users\AmigoDBO as Amigo;

// Investimentos
use \DBO\Investimento\EmprestimoDBO as Emprestimo;
use \DBO\Investimento\DetalheDBO as Detalhe;
use \DBO\Investimento\InvestimentoDBO as Investimento;
use \DBO\Investimento\OportunidadeDBO as Oportunidade;
use \DBO\Investimento\ParcelaDBO as Parcela;

// Notificaoes
use \DBO\Notificacao\NotificacaoDBO as Notificacao;
use \DBO\Notificacao\MensagemDBO as Mensagem;

class ModelController {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    
    // User DBO
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

    // Notification
    public function mensagem() {
        return new Mensagem($this->db);        
    }

    public function notificacao() {
        return new Notificacao($this->db);        
    }


    // Investimentos
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


}