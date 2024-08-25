<?php

namespace app\dao;

use app\models\Endereco;
use core\ClassFactory;

class EnderecoDAO extends DAOEmBDR {
    public function __construct(){
        parent::__construct();
    }

    protected function nomeTabela(){
        return 'endereco';
    }

    protected function adicionarNovo( $endereco ){
        $comando = "INSERT INTO {$this->nomeTabela()} ( id, idCliente, cep, cidade, bairro, logradouro, numero, complemento ) VALUES ( :id, :idCliente, :cep, :cidade, :bairro, :logradouro, :numero, :complemento )";
        $this->getBancoDados()->executar( $comando, $this->parametros( $endereco ) );
    }

    protected function atualizar( $endereco ){
        $comando = "UPDATE {$this->nomeTabela()} SET idCliente = :idCliente, cep = :cep, cidade = :cidade, bairro = :bairro, logradouro = :logradouro, numero = :numero, complemento = :complemento WHERE id = :id";
        $this->getBancoDados()->executar( $comando, $this->parametros( $endereco ) );
    }

    protected function parametros( $endereco ){
        return $this->converterEmArray( $endereco );
    }

    protected function obterQuery( array $restricoes, array &$parametros ){
        $nomeTabela = $this->nomeTabela();

        $select = "SELECT * FROM {$nomeTabela}";
        $where = ' WHERE ativo = 1 ';
        $join = '';

        if( isset( $restricoes['id'] ) ){
            $where .= " AND $nomeTabela.id = :id ";
            $parametros['id'] = $restricoes['id'];
        }

        if( isset( $restricoes['idCliente'] ) ){
            $where .= " AND $nomeTabela.idCliente = :idCliente ";
            $parametros['idCliente'] = $restricoes['idCliente'];
        }

        $comando = $select . $join . $where;

        return $comando;
    }

    protected function transformarEmObjeto( array $linhas ){
        /** @var Endereco */
        $endereco = $this->converterEmObjeto( Endereco::class, $linhas );

        $clienteDAO = ClassFactory::makeDAO( 'Cliente' );
        $cliente = $clienteDAO->obterComId( $linhas['idCliente'] );
        $endereco->setCliente( $cliente );

        return $endereco;
    }

    public function desativarEnderecosDoCliente( int $idCliente ){
        $restricoes = [ 'idCliente' => $idCliente ];
        $enderecos = $this->obterComRestricoes( $restricoes );
        if( ! empty( $enderecos ) ){
            /** @var Endereco */
            foreach( $enderecos as $endereco ){
                $this->desativarComId( $endereco->getId() );
            }
        }
    }
}