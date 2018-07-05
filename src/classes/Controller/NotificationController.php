<?php
namespace Controller;

// Controllers
use \Controller\Controller;
// use \Controller\UserController;

class NotificationController extends Controller {

    // Notification Routes
    public function todas($request, $response, $args) {
        // TODO
        $item = $this->view->newItem();
        $item->setType('mensagem');
        $item->setId('1');
        
        $mock = array(
            'titulo' => 'Adiantamento aceito',
            'descricao' => 'Adiantamento de R$50.000, do sacado 1 foi aceito.',
            'data' => '04/07/2017',
            'categoria' => 'android',
            'lida' => 'false'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('mensagem');
        $item->setId('2');
        
        $mock = array(
            'titulo' => 'Adiantamento aceito 2',
            'descricao' => 'Adiantamento de R$50.000, do sacado 2 foi aceito.',
            'data' => '04/07/2017',
            'categoria' => 'android',
            'lida' => 'true'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('mensagem');
        $item->setId('3');
        
        $mock = array(
            'titulo' => 'Adiantamento aceito 3',
            'descricao' => 'Adiantamento de R$50.000, do sacado 3 foi aceito.',
            'data' => '04/07/2017',
            'categoria' => 'android',
            'lida' => 'false'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $item = $this->view->newItem();
        $item->setType('mensagem');
        $item->setId('4');
        
        $mock = array(
            'titulo' => 'Adiantamento aceito 4',
            'descricao' => 'Adiantamento de R$50.000, do sacado 4 foi aceito.',
            'data' => '04/07/2017',
            'categoria' => 'android',
            'lida' => 'true'
        );
        $item->setAttributes($mock);
        $this->view->addData($item);

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function detalhe($request, $response, $args) {
        // TODO
        $data = $this->view->getData();
        $data->setType('mensagem');
        $data->setId('1');
        
        $mock = array(
            'titulo' => 'Adiantamento aceito',
            'descricao' => 'Adiantamento de R$50.000, do sacado 1 foi aceito.',
            'data' => '04/07/2017',
            'categoria' => 'android',
        );
        $data->setAttributes($mock);

        $response = $response->withJSON($this->view->get());
        return $response;
    }

    public function marcarComoLida($request, $response, $args) {
        // TODO
        $response = $response->withStatus(202); 
        return $response;
    }

    
    // Business Logic


}