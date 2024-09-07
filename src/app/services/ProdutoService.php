<?php

namespace app\services;

use app\exceptions\ServiceException;
use app\models\Categoria;
use app\models\Produto;

class ProdutoService extends Service {
    public function __construct(){
        parent::__construct();
    }

    protected function validar( $produto, array &$erro = [] ){
        $this->validarReferencia( $produto, $erro );
        $this->validarNome( $produto, $erro );
        $this->validarDescricao( $produto, $erro );
        $this->validarPeso( $produto, $erro );
        $this->validarCategoria( $produto, $erro );
    }

    private function validarReferencia( Produto $produto, array &$erro ){
        $this->validarTexto( $produto->getReferencia(), Produto::TAMANHO_MINIMO_REFERENCIA, Produto::TAMANHO_MAXIMO_REFERENCIA, 'referencia', $erro );

        if( ! ctype_alnum( $produto->getReferencia() ) ){
            $erros['referencia'] = "A referência deve conter apenas letras e números.";
        }
    }

    private function validarNome( Produto $produto, array &$erro ){
        $this->validarTexto( $produto->getNome(), Produto::TAMANHO_MINIMO_NOME, Produto::TAMANHO_MAXIMO_NOME, 'nome', $erro );
    }

    private function validarDescricao( Produto $produto, array &$erro ){
        $this->validarTexto( $produto->getDescricao(), Produto::TAMANHO_MINIMO_DESCRICAO, Produto::TAMANHO_MAXIMO_DESCRICAO, 'descricao', $erro );
    }

    private function validarPeso( Produto $produto, array &$erro ){
        if( ! is_numeric( $produto->getPeso() ) ){
            $erro['numero'] = 'O campo peso precisa ser numérico.';
        }
    }

    private function validarCategoria( Produto $produto, array &$erro ){
        if( ! $produto->getCategoria() instanceof Categoria ){
            $erro['categoria'] = 'Categoria inválida.';
        }
    }
}