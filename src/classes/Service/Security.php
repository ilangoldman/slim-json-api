<?php
namespace Service;

// TODO fix security for the controllers

// use \Service\Controller\DBOController as DBOController;

use \DBO\Users\UserDBO as User;

class Security {
    private $db;
    private $user;
    private $controller;

    public function __construct($db, $id = null) {
        // $this->controller = new DBOController($db);
        $this->user = new User($db);
        // $this->user->read($id);
    }
    
    public function userLogged($uid) {
        return true;
    } 

    public function validarUser($uid) {
        // var_export($this->user->getUID());
        // var_export($uid);
        return ($this->user->getUID() == $uid);
    }
    
    // itemId = id do item que desejar acessar
    // itemType = tipo do item q deseja acessar
    // method = metodo de acesso a bd
    public function userAccess($itemId, $itemType, $method) {
        $dbo = $this->controller->{$itemType}();
        return $dbo->allowAccess($this->user->getId(), $this->user->getTipo(), $itemId, $method);
    }

    public function adminOnly($userId) {
        return ($this->user->getTipo() == 'admin');
    }

    public function empresaOnly($id) {
        return ($this->user->getTipo() == 'empresa');
    }

    public function investidorOnly($id) {
        return ($this->user->getTipo() == 'investidor');
    }

}