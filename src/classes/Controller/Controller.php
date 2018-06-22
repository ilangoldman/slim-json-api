<?php
namespace Controller;

use \Slim\Container as ContainerInterface;

use \View\JSONAPI;
use \Service\Security;
use \DBO\Users\UserDBO as User;
use \Controller\ModelController;

abstract class Controller {
    protected $container;
    protected $db;
    protected $logger;
    protected $view;
    protected $security;
    protected $user;
    protected $models;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->db = $container->db;
        $this->logger = $container->logger;
        $this->view = new JSONAPI();
        $this->security = new Security($this->db);
        $this->user = new User($this->db);
        $this->models = new ModelController($this->db);
    }

    // CRUD Operations
    protected function create($data,$fk = []) {
        $model = $this->models->{$data->getType()}();
        $info = $data->getAttributes();
        foreach ($fk as $k => $v) {
            $info[$k] = $v;
        }
        $model->set($info);
        return $model->create();
    }

    protected function read($model) {
        $data = $this->view->getData();
        $data->setType($model->getType());
        $data->setAttributes($model->get());
        $data->setId($model->getId());

        foreach($model->getRelations() as $r) {
            $rModel = $this->models->{$r}();
            $rModel->readByFK($model->getType(),$model->getId());
            if ($rModel->getId() == null) continue;

            $item = $this->view->newItem();
            $item->setId($rModel->getId());
            $item->setType($rModel->getType());
            $data->addRelationships($item->get());
            $item->setAttributes($rModel->get());
            $this->view->addIncluded($item);
        }
    }

    protected function update($data) {

        $relationshipDBO = $controller->{$data['type']}();
        $relationshipDBO->updateAll($rId,$data['attributes']);
    }

    protected function delete() {

    }
}