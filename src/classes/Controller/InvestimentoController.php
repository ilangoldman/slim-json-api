<?php
namespace Controller;

// Controllers
use \Controller\Controller;
// use \Controller\UserController;

class InvestimentoController extends Controller {
    // Routes Implementation

    // Empresa
    public function pedirEmprestimo($request, $response, $args) {
        // TODO
        return $response;
    }

    public function pedidos($request, $response, $args) {
        // TODO
        $item = $this->view->newItem();
        $item->setType('recebivel');
        $item->setId('1');
        
        $mock = array(
            'nfe' => 'url/nfe/1',
            'valor' => '50000',
            'porcentagem' => '80',
            'status' => 'Em análise',
            'sacado' => "Sacado 1",
            'data' => '04/07/2018'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('recebivel');
        $item->setId('10');
        
        $mock = array(
            'nfe' => 'url/nfe/1',
            'valor' => '100000',
            'porcentagem' => '80',
            'status' => 'Em análise',
            'sacado' => "Sacado 3",
            'data' => '14/07/2018'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('recebivel');
        $item->setId('2');
        
        $mock = array(
            'nfe' => 'url/nfe/1',
            'valor' => '50000',
            'porcentagem' => '80',
            'status' => 'Recebido',
            'sacado' => "Sacado 2",
            'data' => '04/05/2018'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('recebivel');
        $item->setId('3');
        
        $mock = array(
            'nfe' => 'url/nfe/1',
            'valor' => '50000',
            'porcentagem' => '90',
            'status' => 'Concluído',
            'sacado' => "Sacado 2",
            'data' => '04/04/2018'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('recebivel');
        $item->setId('4');
        
        $mock = array(
            'nfe' => 'url/nfe/1',
            'valor' => '50000',
            'porcentagem' => '100',
            'status' => 'Concluído',
            'sacado' => "Sacado 1",
            'data' => '04/03/2018'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function detalheEmprestimo($request, $response, $args) {
        // TODO
        return $response;
    }

    public function editarPedido($request, $response, $args) {
        // TODO
        return $response;
    }

    public function removerPedido($request, $response, $args) {
        // TODO
        return $response;
    }

    // Carteira
    public function infoConta($request, $response, $args) {
        // TODO: accessa api da Iugu
        $data = $this->view->getData();
        $data->setType('carteira');
        $data->setId('1');
        
        $mock = array(
            'balanco' => '10000',
            'disponivel' => '5000',
            'ultima_retirada' => '05/04/2018',
            'auto_retirada' => 'Não'

        );
        $data->setAttributes($mock);
        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function transferencias($request, $response, $args) {
        // TODO
        $item = $this->view->newItem();
        $item->setType('transferencia');
        $item->setId('1');
        
        $mock = array(
            'data' => '04/07/2018',
            'valor' => '50000',
            'nome' => 'Itau',
            'status' => 'recebido'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('transferencia');
        $item->setId('2');
        
        $mock = array(
            'data' => '04/07/2018',
            'valor' => '50000',
            'nome' => 'Itau',
            'status' => 'recebido'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('transferencia');
        $item->setId('3');
        
        $mock = array(
            'data' => '04/07/2018',
            'valor' => '50000',
            'nome' => 'Bradesco',
            'status' => 'enviado'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('transferencia');
        $item->setId('4');
        
        $mock = array(
            'data' => '04/07/2018',
            'valor' => '50000',
            'nome' => 'Itau',
            'status' => 'enviado'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function saque($request, $response, $args) {
        // TODO
        $response = $response->withStatus(202);
        return $response;
    }

    public function fatura($request, $response, $args) {
        // TODO
        $response = $response->withStatus(202);
        return $response;
    }


    // Investidor
    public function investir($request, $response, $args) {
        // TODO
        return $response;
    }

    public function investimentos($request, $response, $args) {
        // TODO
        return $response;
    }

    public function detalheInvestimento($request, $response, $args) {
        // TODO
        return $response;
    }

    public function transferir($request, $response, $args) {
        // TODO
        return $response;
    }

    public function removerOferta($request, $response, $args) {
        // TODO
        return $response;
    }

    public function oportunidades($request, $response, $args) {
        // TODO
        return $response;
    }

    public function detalheOportunidade($request, $response, $args) {
        // TODO
        return $response;
    }

    public function carteira($request, $response, $args) {
        // TODO
        return $response;
    }


    // Parcelas
    public function pagar($request, $response, $args) {
        // TODO
        return $response;
    }

    public function parcelas($request, $response, $args) {
        // TODO
        return $response;
    }

    // public function detalheParcela($request, $response, $args) {
    //     // TODO
    //     return $response;
    // }
    


    
    // Business Logic




}