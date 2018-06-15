<?php
namespace DBO\Gamefication;
use \DBO\DBO;

class BeneficioDBO extends DBO {
    private $criado;

    private $investidor;
    private $beneficio;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("beneficio");
        $this->setType("recompensa");
    }
    
    // helpers

    protected function addCol($info) {
        $this->investidor = $info['investidor'] ?? null;
        $this->beneficio = $info['beneficio'] ?? null;
    }

    protected function getCol() {
        return array(
            "investidor" => $this->investidor,
            "beneficio" => $this->beneficio
        );
    }

    protected function getSqlCol() {
        return $this->getCol();
    }

}