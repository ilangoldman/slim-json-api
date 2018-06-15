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

        $this->investidor = $info["investidor"] ?? null;
        $this->empresa = $info["empresa"] ?? null;
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
        return $this->getCol();
    }

    public function allowAccess($userId,$type,$id,$method) {
        if ($type != 'admin' && $method !="get")
            return false;

        return parent::allowAccess($userId,$type,$id,$method);
    }

}