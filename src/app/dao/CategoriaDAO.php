<?php

namespace app\dao;

use app\models\Categoria;
use app\traits\ConversorDados;

class CategoriaDAO extends DAOEmBDR {
    public function __construct(){
        parent::__construct();
    }

    use ConversorDados;

    protected function nomeTabela(){
        return 'categoria';
    }

    protected function adicionarNovo( $categoria ){
        $comando = "INSERT INTO {$this->nomeTabela()} ( id, nome, descricao ) VALUES ( :id, :nome, :descricao )";
        $this->getBancoDados()->executar( $comando, $this->parametros( $categoria ) );
    }

    protected function atualizar( $categoria ){
        $comando = "UPDATE {$this->nomeTabela()} SET nome = :nome, descricao = :descricao WHERE id = :id";
        $this->getBancoDados()->executar( $comando, $this->parametros( $categoria ) );
    }

    protected function parametros( $categoria ){
        return $this->converterEmArray( $categoria );
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
        return $this->converterEmObjeto( Categoria::class, $linhas );
    }
}