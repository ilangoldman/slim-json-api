<?php

class Investidor extends DBO {

    private $table_name = "investidor";
    
    public function formatDate($date) {
        $format = explode("/", $date);
        $newDate = $format[2]."-".$format[1]."-".$format[0];
        return $newDate;
    }

    // CREATE

    // cria um novo investidor
    // retorna o id do investidor
    public function createInvestidor($uid, $data) {
        // var_export($data);
        $this->db->beginTransaction();
        try {
            $user = '"'.$uid.'"';
            $pontuacao = 0;
            $pontos = 0;
            $email = '"'.filter_var($data['email'],FILTER_SANITIZE_EMAIL).'"';
            $nome = '"'.filter_var($data['nome'],FILTER_SANITIZE_STRING).'"';
            $sobrenome = '"'.filter_var($data['sobrenome'],FILTER_SANITIZE_STRING).'"';
            $sexo = '"'.filter_var($data['sexo'],FILTER_SANITIZE_STRING).'"';
            $estado_civil = '"'.filter_var($data['estado_civil'],FILTER_SANITIZE_STRING).'"';
            $nome_mae = '"'.filter_var($data['nome_mae'],FILTER_SANITIZE_STRING).'"';
            $nome_pai = '"'.filter_var($data['nome_pai'],FILTER_SANITIZE_STRING).'"';
            $tel1 = '"'.filter_var($data['tel1'],FILTER_SANITIZE_NUMBER_INT).'"';
            $tel2 = '"'.filter_var($data['tel2'],FILTER_SANITIZE_NUMBER_INT).'"';
            $naturalidade = '"'.filter_var($data['naturalidade'],FILTER_SANITIZE_STRING).'"';
            $nacionalidade = '"'.filter_var($data['nacionalidade'],FILTER_SANITIZE_STRING).'"';
            $cpf = '"'.filter_var($data['cpf'],FILTER_SANITIZE_NUMBER_INT).'"';
            
            

            $data_nascimento = '"'.$this->formatDate(filter_var($data['data_nascimento'],FILTER_SANITIZE_STRING)).'"';
            
            $rg = '"'.filter_var($data['rg'],FILTER_SANITIZE_NUMBER_INT).'"';
            $orgao_emissor = '"'.filter_var($data['orgao_emissor'],FILTER_SANITIZE_STRING).'"';
            $estado_emissor = '"'.filter_var($data['estado_emissor'],FILTER_SANITIZE_STRING).'"';
            
            $data_emissao = '"'.$this->formatDate(filter_var($data['data_emissao'],FILTER_SANITIZE_STRING)).'"';            
            
            $renda_mensal = filter_var($data['renda_mensal'],FILTER_SANITIZE_NUMBER_FLOAT);
            $patrimonio = filter_var($data['patrimonio'],FILTER_SANITIZE_NUMBER_FLOAT);
            $ppe = (filter_var($data['ppe'],FILTER_VALIDATE_BOOLEAN))?1:0;
            $doc_id = '"'.filter_var($data['doc_id'],FILTER_SANITIZE_STRING).'"';
            $doc_residencia = '"'.filter_var($data['doc_residencia'],FILTER_SANITIZE_STRING).'"';
            
            $endereco = new Endereco($this->db);
            $endereco_fk = $endereco->createEndereco($data['endereco']);

            $conta_bancaria = new ContaBancaria($this->db);
            $conta_bancaria_fk = $conta_bancaria->createContaBancaria($data['conta_bancaria']);

            var_export($endereco_fk);
            var_export($conta_bancaria_fk);
                
            $sql = "INSERT INTO ".$this->table_name.
                " (pontuacao,endereco,conta_bancaria,user,pontos,email,nome,sobrenome,sexo,estado_civil,nome_mae,nome_pai,tel1,tel2,naturalidade,nacionalidade,cpf,data_nascimento,rg,orgao_emissor,estado_emissor,data_emissao,renda_mensal,patrimonio,ppe,doc_id,doc_residencia) values (".
                $pontuacao.",".$endereco_fk.",".$conta_bancaria_fk.",".$user.",".$pontos.",".$email.",".$nome.",".$sobrenome.",".$sexo.",".$estado_civil.",".$nome_mae.",".$nome_pai.",".$tel1.",".$tel2.",".$naturalidade.",".$nacionalidade.",".$cpf.",".$data_nascimento.",".$rg.",".$orgao_emissor.",".$estado_emissor.",".$data_emissao.",".$renda_mensal.",".$patrimonio.",".$ppe.",".$doc_id.",".$doc_residencia.");";

            $stmt = $this->db->exec($sql);
                
            // var_export($sql);

            // TODO!!
            // checar se o varbinary tb funciona no user

            // $sql = "SELECT user FROM ".$this->table_name.
            //     " WHERE investidor = 5";
            // $stmt = $this->db->query($sql);
            
            // if ($row = $stmt->fetch()) {
            //     extract($row);
            //     var_export($row);                
            // }

            // throw new Exception();
            $this->db->commit();
        } catch(Exception $e) {
            $this->db->rollBack();
        }
        return $response;
    }

    // recebe o id do investidor
    // cria um novo investimento para o investidor
    public function createInvestimento($id) {

    }

    // READ

    // return todos os investidores da plataforma
    public function getInvestidor() {
        
    }

    // recebe o id do investidor
    // return as infos:
    //    - perfil do investidor
    //    - resumo da carteira
    public function getInvestidorById($id) {
        
    }

    // recebe id do investidor
    // return:
    //  - todas as parcelas recebidas de investimentos feitos pelo investidor
    //  - Investimento??
    public function getMovimentacao($id) {
        
    }

    
    // recebe id do investidor e periodo a ser filtrado
    // return:
    //  - todas as parcelas desse periodo recebidas de investimentos feitos pelo investidor
    //  - todo o investimento realizado
    public function getMovimentacaoFiltro($id, $periodo) {
        
    }

    // recebe id do investidor e id do investimento
    // return:
    //  - info do investimento
    //  - parcelas do investimento
    public function getInvestimentoById($id,$invid) {

    }

    // recebe id do investidor
    // return:
    //  - todos os investimentos do investidor
    public function getInvestimentos($id) {

    }

    // recebe id do investidor
    // return:
    //  - todos as notificacoes do investidor
    public function getNotificacoes($id) {

    }

    // UPDATE

    // recebe o id do investidor
    // atualiza as infos do investidor
    public function updateInvestidor($id) {

    }

    // DELETE

    // recebe o id do investidor
    // deleta o investidor dependendo dos status dos seus investimentos
    public function deleteInvestidor($id) {
        
    }

    // recebe o id do investidor e o id do investimento
    // delete o investimento feito pelo investidor (depende do status)
    public function deleteInvestimento($id, $invid) {
        
    }
}