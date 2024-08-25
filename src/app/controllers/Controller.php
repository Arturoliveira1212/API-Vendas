<?php

namespace app\controllers;

use app\exceptions\CampoNaoEnviadoException;
use app\services\Service;
use app\traits\ConversorDados;
use core\ClassFactory;
use DateTime;

abstract class Controller {
    protected string $classe;
    protected Service $service;

    use ConversorDados;

    public function __construct(){
        $this->setClasse( str_replace( 'Controller','', basename( str_replace( '\\', '/', get_class( $this ) ) ) ) );
        $this->setService( ClassFactory::makeService( $this->getClasse() ) );
    }

    public function getClasse(){
        return $this->classe;
    }

    public function setClasse( string $classe ){
        $this->classe = $classe;
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

    public function povoarDateTime( $objeto, array $campos, array $corpoRequisicao ){
        foreach( $campos as $campo ){
            if( isset( $corpoRequisicao[ $campo ] ) ){
                $metodo = 'set' . ucfirst( $campo );
                if( method_exists( $objeto, $metodo ) ){
                    $data = DateTime::createFromFormat( 'd/m/Y', $corpoRequisicao[ $campo ] );
                    if( $data ){
                        $objeto->$metodo( $data );
                    } else {
                        throw new CampoNaoEnviadoException( "$campo não enviado" );
                    }
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

    public function existe( string $campo, string $valor ){
        return $this->getService()->existe( $campo, $valor );
    }

    public function obterComId( int $id ){
        return $this->getService()->obterComId( $id );
    }

    public function obterComRestricoes( array $restricoes = [] ){
        return $this->getService()->obterComRestricoes( $restricoes );
    }
}