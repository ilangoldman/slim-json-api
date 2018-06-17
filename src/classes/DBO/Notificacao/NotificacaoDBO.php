<?php
namespace DBO\Notificacao;
use \DBO\DBO;

class NotificacaoDBO extends DBO {
    private $notificacao;

    private $investidor;
    private $empresa;
    private $status;

    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("notificacao");
        $this->setType("mensagem");
    }

    protected function addCol($info) {
        $this->notificacao = $this->id ?? null;

        $this->{$info['type']} = $info['id'] ?? null;
        $this->status = $info["status"] ?? 0;
    }

    protected function getCol() {
        return array(
            "notificacao" => $this->notificacao,
            "investidor" => $this->investidor,
            "empresa" => $this->empresa,
            "status" => $this->status
        );
    }

    protected function getSqlCol() {
        $cols = $this->getCol();
        $cols["investidor"] = $cols["investidor"] ?? "null";
        $cols["empresa"] = $cols["empresa"] ?? "null";
        return $cols;
    }

    public function allowAccess($userId,$type,$id,$method) {
        if ($type != 'admin' && $method !="get")
            return false;

        return parent::allowAccess($userId,$type,$id,$method);
    }

    public function delete($info) {
        // $this->setId($info['id']);
        var_export($info);
        $sql = "DELETE FROM ".$this->table_name.
               " WHERE ".$this->table_name." = ".$info['notificacao'].
               " AND ".$info['type']." = ".$info['id'];
        var_export($sql);
        $stmt = $this->db->exec($sql);
        return ($stmt > 0);
    }

}