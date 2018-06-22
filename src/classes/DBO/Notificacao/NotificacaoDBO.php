<?php
namespace DBO\Notificacao;
use \DBO\DBO;

class NotificacaoDBO extends DBO {
    public $notificacao;
    public $user;
    public $status;

    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("notificacao");
        $this->setType("mensagem");
        // $this->setFK([""]);
        $this->setRelations(["user","notificacao"]);
    }



}


// [DEPRECATED]

    // protected function addCol($info) {
    //     $this->notificacao = $this->id ?? null;

    //     $this->user = $info['user'];
    //     $this->status = $info["status"] ?? 0;
    // }

    // protected function getCol() {
    //     return array(
    //         "notificacao" => $this->notificacao,
    //         "user" => $this->user,
    //         "status" => $this->status
    //     );
    // }

    // protected function getSqlCol() {
    //     $cols = $this->getCol();
    //     return $cols;
    // }

    // public function allowAccess($userId, $userType, $itemId, $method) {
    //     if ($userType != 'admin' && $method !="get")
    //         return false;

    //     return parent::allowAccess($userId,$userType, $itemId,$method);
    // }

    // public function delete($info) {
    //     // $this->setId($info['id']);
    //     // var_export($info);
    //     $sql = "DELETE FROM ".$this->table_name.
    //            " WHERE ".$this->table_name." = ".$info['notificacao'].
    //            " AND user = ".$info['id'];
    //     var_export($sql);
    //     $stmt = $this->db->exec($sql);
    //     return ($stmt > 0);
    // }