<?php

namespace app\dao;

use app\models\Model;
use app\traits\ConversorDados;
use core\QueryParams;

abstract class DAOEmBDR implements DAO {
    private ?BancoDadosRelacional $bancoDados = null;

    public function __construct(){
        $this->bancoDados = new BancoDadosRelacional();
    }

    use ConversorDados;

    protected function getBancoDados(){
        return $this->bancoDados;
    }

    abstract protected function nomeTabela();
    abstract protected function adicionarNovo( Model $objeto );
    abstract protected function atualizar( Model $objeto );
    abstract protected function parametros( Model $objeto );
    abstract protected function obterQuery( QueryParams $queryParams, array &$parametros );
    abstract protected function transformarEmObjeto( array $linhas );

    public function salvar( $objeto ){
        if( $objeto->getId() == BancoDadosRelacional::ID_INEXISTENTE ){
            $this->adicionarNovo( $objeto );
        } else {
            $this->atualizar( $objeto );
        }

        return $this->getBancoDados()->ultimoIdInserido();
    }

    public function desativarComId( int $id ){
        return $this->getBancoDados()->desativar( $this->nomeTabela(), $id );
    }

    public function existe( string $campo, string $valor ){
        return $this->getBancoDados()->existe( $this->nomeTabela(), $campo, $valor );
    }

    public function obterComId( $id ){
        $parametros = [];
        $comando = "SELECT * FROM {$this->nomeTabela()} WHERE id = :id";
        $objetos = $this->obterObjetos( $comando, [ $this, 'transformarEmObjeto' ], $parametros );
        return ! empty( $objetos ) ? array_shift( $objetos ) : null;
    }

    public function obterComRestricoes( QueryParams $queryParams ){
        $parametros = [];
        $query = $this->obterQuery( $queryParams, $parametros );
        return $this->obterObjetos( $query, [ $this, 'transformarEmObjeto' ], $parametros );
    }

    public function obterObjetos( string $comando, array $callback, array $parametros = [] ){
        $objetos = [];

        $resultados = $this->getBancoDados()->consultar( $comando, $parametros );

        if( ! empty( $resultados ) ){
            foreach( $resultados as $resultado ){
                $objeto = call_user_func_array( $callback, [ $resultado ] );
                $objetos[] = $objeto;
            }
        }

        return $objetos;
    }
}