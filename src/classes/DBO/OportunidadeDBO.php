<?php
namespace DBO;

class OportunidadeDBO extends DBO {
    private $criado;
    private $modificado;

    private $empresa;

    private $nome; 

    private $avalista;
    private $valor; 
    private $taxa;
    private $prazo; 
    private $valor_parcela;
    private $status; 
    private $motivo;
    private $faturamento;
    private $prazo_medio_receber;
    private $prazo_medio_pagar;

    // Pensar como fazer esses detalhes
    private $detalhe;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("emprestimo");
        $this->setType("emprestimo");        
    }

    // helper
    
    protected function addCol($info) {
        $this->empresa = $info['empresa'];
        $this->nome = $info['nome'];

        $this->avalista = filter_var($info['avalista'],FILTER_SANITIZE_STRING);
        $this->valor = filter_var($info['valor'],FILTER_SANITIZE_NUMBER_INT); 
        $this->taxa = filter_var($info['taxa'],FILTER_SANITIZE_NUMBER_FLOAT);
        $this->prazo = filter_var($info['prazo'],FILTER_SANITIZE_NUMBER_INT); 
        $this->valor_parcela = filter_var($info['valor_parcela'],FILTER_SANITIZE_NUMBER_FLOAT);
        $this->status = filter_var($info['status'],FILTER_SANITIZE_NUMBER_INT); 
        $this->motivo = filter_var($info['motivo'],FILTER_SANITIZE_STRING);
        $this->faturamento = filter_var($info['faturamento'],FILTER_SANITIZE_NUMBER_INT);
        $this->prazo_medio_receber = filter_var($info['prazo_medio_receber'],FILTER_SANITIZE_NUMBER_INT);
        $this->prazo_medio_pagar = filter_var($info['prazo_medio_pagar'],FILTER_SANITIZE_NUMBER_INT);
    }

    protected function getCol() {
        return array(
            "empresa" => $this->empresa,
            "nome" => $this->nome,
            
            "avalista" => $this->avalista,
            "valor" => $this->valor, 
            "taxa" => $this->taxa,
            "prazo" => $this->prazo, 
            "valor_parcela" => $this->valor_parcela,
            "status" => $this->status, 
            "motivo" => $this->motivo,
            "faturamento" => $this->faturamento,
            "prazo_medio_receber" => $this->prazo_medio_receber,
            "prazo_medio_pagar" => $this->prazo_medio_pagar
        );
    }

    protected function getSqlCol() {
        return array(
            "empresa" => $this->empresa,
            "nome" => '"'.$this->nome.'"',

            "avalista" => '"'.$this->avalista.'"',
            "valor" => $this->valor, 
            "taxa" => $this->taxa,
            "prazo" => $this->prazo, 
            "valor_parcela" => $this->valor_parcela,
            "status" => $this->status, 
            "motivo" => '"'.$this->motivo.'"',
            "faturamento" => $this->faturamento,
            "prazo_medio_receber" => $this->prazo_medio_receber,
            "prazo_medio_pagar" => $this->prazo_medio_pagar
        );
    }

    // CREATE


    // READ


    // UPDATE
    
}