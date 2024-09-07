<?php

namespace app\dao;

use app\models\Cliente;
use app\models\Endereco;
use core\ClassFactory;

class ClienteDAO extends DAOEmBDR {
    public function __construct(){
        parent::__construct();
    }

    protected function nomeTabela(){
        return 'cliente';
    }

    protected function adicionarNovo( $cliente ){
        $comando = "INSERT INTO {$this->nomeTabela()} ( id, nome, cpf, dataNascimento ) VALUES ( :id, :nome, :cpf, :dataNascimento )";
        $this->getBancoDados()->executar( $comando, $this->parametros( $cliente ) );
    }

    protected function atualizar( $cliente ){
        $comando = "UPDATE {$this->nomeTabela()} SET nome = :nome, cpf = :cpf, dataNascimento = :dataNascimento WHERE id = :id";
        $this->getBancoDados()->executar( $comando, $this->parametros( $cliente ) );
    }

    protected function parametros( $cliente ){
        return $this->converterEmArray( $cliente );
    }

    protected function obterQuery( array $restricoes, array &$parametros ){
        $nomeTabela = $this->nomeTabela();

        $select = "SELECT * FROM {$nomeTabela}";
        $where = ' WHERE ativo = 1 ';
        $join = '';

        if( isset( $restricoes['id'] ) ){
            $where .= " AND $nomeTabela.id = :id";
            $parametros['id'] = $restricoes['id'];
        }

        $comando = $select . $join . $where;

        return $comando;
    }

    protected function transformarEmObjeto( array $linhas ){
        return $this->converterEmObjeto( Cliente::class, $linhas );
    }

    public function desativarComId(int $id){
        parent::desativarComId( $id );

        /** @var EnderecoDAO */
        $enderecoDAO = ClassFactory::makeDAO( 'Endereco' );
        $enderecoDAO->desativarEnderecosDoCliente( $id );
    }
}