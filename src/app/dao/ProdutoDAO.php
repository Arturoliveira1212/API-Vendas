<?php

namespace app\dao;

use app\models\Produto;
use core\ClassFactory;

class ProdutoDAO extends DAOEmBDR {
    public function __construct(){
        parent::__construct();
    }

    protected function nomeTabela(){
        return 'produto';
    }

    protected function adicionarNovo( $produto ){
        $comando = "INSERT INTO {$this->nomeTabela()} ( id, referencia, nome, descricao, peso, idCategoria, dataCadastro ) VALUES ( :id, :referencia, :nome, :descricao, :peso, :idCategoria, NOW() )";
        $this->getBancoDados()->executar( $comando, $this->parametros( $produto ) );
    }

    protected function atualizar( $produto ){
        $comando = "UPDATE {$this->nomeTabela()} SET referencia = :referencia, nome = :nome, descricao = :descricao, peso = :peso, idCategoria = :idCategoria WHERE id = :id";
        $this->getBancoDados()->executar( $comando, $this->parametros( $produto ) );
    }

    protected function parametros( $produto ){
        $parametros = $this->converterEmArray( $produto );
        unset( $parametros['dataCadastro'] );

        return $parametros;
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
        /** @var Produto */
        $produto = $this->converterEmObjeto( Produto::class, $linhas );

        $categoriaDAO = ClassFactory::makeDAO( 'Categoria' );
        $categoria = $categoriaDAO->obterComId( $linhas['idCategoria'] );
        $produto->setCategoria( $categoria );

        return $produto;
    }
}