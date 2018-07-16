<?php
namespace DBO\Users;
use \DBO\DBO;

// Exemplo de uma classe real
// Endereco é uma tabela do BD
class EnderecoDBO extends DBO {
    
    // variaveis private podem ser lidas ou não,
    // mas nunca seria exportadas
    // visiveis apenas pela classe DBO
    private $criado;
    private $modificado;

    // variaveis public são visiveis por todos
    // na acao get são exportadas como os attributos da classe
    public $user;

    public $cep; 
    public $tipo; 
    public $logradouro; 
    public $numero; 
    public $complemento; 
    public $bairro; 
    public $cidade; 
    public $estado; 
    public $pais;
    
    public function __construct($db) {
        // chama o contrutor do pai (hierarquia - extends)
        parent::__construct($db);

        // set o nome da tabela no BD
        $this->setTableName("endereco");

        // set o tipo da classe para ser exportado no JSON API
        // pode ser um nome diferente da tbl para aumentar a seguranca
        // mas nesse caso o controller nao poderia implementar as funcoes de leitura de forma generica atual
        // obs: talvez seja interessante revisar isso
        $this->setType("endereco");

        // set as colunas da tbl q sao chaves estrangeiras (FK)
        // para isso o nome da tabela tem q ser igual o nome da coluna (sql naming convention)
        $this->setFK(["user"]);
    }
    
   
}






// [DEPRECATED]

 // helpers

    // protected function setCol($info) {
    //     $this->user = $info['user'];

    //     $this->cep = filter_var($info['cep'],FILTER_SANITIZE_NUMBER_INT);
    //     $this->tipo = filter_var($info['tipo'],FILTER_SANITIZE_STRING);
    //     $this->logradouro = filter_var($info['logradouro'],FILTER_SANITIZE_STRING);
    //     $this->numero = filter_var($info['numero'],FILTER_SANITIZE_NUMBER_INT);
    //     $this->complemento = filter_var($info['complemento'],FILTER_SANITIZE_STRING);
    //     $this->bairro = filter_var($info['bairro'],FILTER_SANITIZE_STRING);
    //     $this->cidade = filter_var($info['cidade'],FILTER_SANITIZE_STRING);
    //     $this->estado = filter_var($info['estado'],FILTER_SANITIZE_STRING);
    //     $this->pais =  filter_var($info['pais'],FILTER_SANITIZE_STRING);
    // }

    // protected function getCol() {
    //     return array(
    //         "user" => $this->user,
    //         "cep" => $this->cep,
    //         "tipo" => $this->tipo,
    //         "logradouro" => $this->logradouro, 
    //         "numero" => $this->numero, 
    //         "complemento" => $this->complemento, 
    //         "bairro" => $this->bairro, 
    //         "cidade" => $this->cidade,
    //         "estado" => $this->estado,
    //         "pais" => $this->pais
    //     );
    // }

    // protected function getSqlCol() {
    //     $cols = $this->getCol();
    //     $cols["user"] = $this->user;
    //     $cols["tipo"] = '"'.$this->tipo.'"';
    //     $cols["logradouro"] = '"'.$this->logradouro.'"';
    //     $cols["complemento"] = '"'.$this->complemento.'"'; 
    //     $cols["bairro"] = '"'.$this->bairro.'"'; 
    //     $cols["cidade"] = '"'.$this->cidade.'"';
    //     $cols["estado"] = '"'.$this->estado.'"';
    //     $cols["pais"] = '"'.$this->pais.'"';
    //     return $cols;
    // }

    // // public function getAttributes() {
    // //     $cols = $this->read($this->id);
    // //     // unset($cols["user"]);
    // //     return $this->removeFK($cols);
    // // }
