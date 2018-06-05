<?php
namespace DBO;

class Parcela extends DBO {
    private $criado;

    private $emprestimo;
    private $investimento;
    private $parcela_empresa;
    
    private $valor; 
    private $principal; 
    private $rendimentos; 
    private $multa;
    private $ir;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("parcela");
    }

    // helper
    
    protected function addCol($info) {
        $this->emprestimo = $info['emprestimo'];
        $this->investimento = $info['investimento'];
        $this->parcela_empresa = $info['parcela_empresa'];
        
        $this->valor = filter_var($info['valor'],FILTER_SANITIZE_NUMBER_FLOAT); 
        $this->principal = filter_var($info['principal'],FILTER_SANITIZE_NUMBER_FLOAT); 
        $this->rendimentos = filter_var($info['rendimentos'],FILTER_SANITIZE_NUMBER_FLOAT); 
        $this->multa = filter_var($info['multa'],FILTER_SANITIZE_NUMBER_FLOAT);
        $this->ir = filter_var($info['ir'],FILTER_SANITIZE_NUMBER_FLOAT);
    }

    protected function getCol() {
        return array(
            "emprestimo" => $this->emprestimo,
            "investimento" => $this->investimento,
            "parcela_empresa" => $this->parcela_empresa,
            
            "valor" => $this->valor, 
            "principal" => $this->principal, 
            "rendimentos" => $this->rendimentos, 
            "multa" => $this->multa,
            "ir" => $this->ir
        );
    }

    protected function getSqlCol() {
        return $this->getCol();
    }

    // CREATE


    // READ


    // UPDATE
    


}