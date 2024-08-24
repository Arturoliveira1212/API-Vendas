<?php

namespace app\services;

use app\exceptions\ServiceException;
use app\models\Categoria;


class CategoriaService extends Service {
    public function __construct(){
        parent::__construct();
    }

    public function validar( $categoria, array &$erro = [] ){
        // $this->validarId( $categoria, $erro );
        // $this->validarNome( $categoria, $erro );

        // if( ! empty( $erro ) ){
        //     throw new ServiceException( 'Houve um erro ao salvar.' );
        // }
    }

    // private function validarId( Categoria $categoria, array &$erro ){
    //     if( ! is_numeric( $categoria->getId() ) ){
    //         $erro['id'] = 'O id precisa ser numérico.';
    //     }
    // }

    // private function validarNome( Categoria $categoria, array &$erro ){
    //     $tamanhoNomeCategoria =  mb_strlen( $categoria->getNome() );
    //     if( $tamanhoNomeCategoria == 0 ){
    //         $erro['nome'] = 'Preencha o nome.';
    //     } elseif( $tamanhoNomeCategoria > Categoria::TAMANHO_MAXIMO_NOME || $tamanhoNomeCategoria < Categoria::TAMANHO_MINIMO_NOME ){
    //         $erro['nome'] = 'O nome deve ter entre ' . Categoria::TAMANHO_MINIMO_NOME  . ' e ' . Categoria::TAMANHO_MAXIMO_NOME . ' caracteres';
    //     }
    // }
}