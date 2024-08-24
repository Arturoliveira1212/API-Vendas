<?php

namespace app\controllers;

use app\exceptions\CampoNaoEnviadoException;
use app\services\Service;
use app\views\View;
use core\ClassFactory;
use Exception;

abstract class Controller {
    protected string $classe;
    protected View $view;
    protected Service $service;

    public function __construct(){
        $this->setClasse( str_replace( 'Controller','', basename( str_replace( '\\', '/', get_class( $this ) ) ) ) );
        $this->setView( ClassFactory::makeView( $this->getClasse() ) );
        $this->setService( ClassFactory::makeService( $this->getClasse() ) );
    }

    public function getClasse(){
        return $this->classe;
    }

    public function setClasse( string $classe ){
        $this->classe = $classe;
    }

    public function getView(){
        return $this->view;
    }

    public function setView( View $view ){
        $this->view = $view;
    }

    public function getService(){
        return $this->service;
    }

    public function setService( Service $service ){
        $this->service = $service;
    }

    public function verificaEnvio( array $campos, array $corpoRequisicao ){
        foreach( $campos as $campo ){
            if( ! isset( $corpoRequisicao[ $campo ] ) ){
                throw new CampoNaoEnviadoException( "$campo não enviado." );
            }
        }
    }

    public function povoarSimples( $objeto, array $campos, array $corpoRequisicao ){
        foreach( $campos as $campo ){
            if( isset( $corpoRequisicao[ $campo ] ) ){
                $metodo = 'set' . ucfirst( $campo );
                if( method_exists( $objeto, $metodo ) ){
                    $objeto->$metodo( $corpoRequisicao[ $campo ] );
                }
            }
        }
    }

    public function salvar( $objeto, array &$erro = [] ){
        return $this->getService()->salvar( $objeto, $erro );
    }

    public function desativarComId( int $id ){
        return $this->getService()->desativarComId( $id );
    }

    public function obterComId( int $id ){
        return $this->getService()->obterComId( $id );
    }

    public function obterComRestricoes( array $restricoes = [] ){
        return $this->getService()->obterComRestricoes( $restricoes );
    }
}