<?php

namespace app\controllers;

use app\exceptions\CampoNaoEnviadoException;
use app\models\Model;
use app\services\Service;
use app\traits\ConversorDados;
use DateTime;
use Throwable;

abstract class Controller {
    protected Service $service;

    use ConversorDados;

    public function __construct( Service $service ){
        $this->setService( $service );
    }

    public function getService(){
        return $this->service;
    }

    public function setService( Service $service ){
        $this->service = $service;
    }

    abstract protected function criar( array $corpoRequisicao ) :Model;

    protected function verificaEnvio( array $campos, array $corpoRequisicao ){
        if( empty( $corpoRequisicao ) ){
            throw new CampoNaoEnviadoException( 'Corpo requisição inválido.' );
        }

        foreach( $campos as $campo ){
            if( ! isset( $corpoRequisicao[ $campo ] ) ){
                throw new CampoNaoEnviadoException( "$campo não enviado." );
            }
        }
    }

    protected function povoarSimples( Model $objeto, array $campos, array $corpoRequisicao ){
        foreach( $campos as $campo ){
            if( isset( $corpoRequisicao[ $campo ] ) ){
                $metodo = 'set' . ucfirst( $campo );
                if( method_exists( $objeto, $metodo ) ){
                    try{
                        $objeto->$metodo( $corpoRequisicao[ $campo ] );
                    } catch( Throwable $e ){}
                }
            }
        }
    }

    protected function povoarDateTime( Model $objeto, array $campos, array $corpoRequisicao ){
        foreach( $campos as $campo ){
            if( isset( $corpoRequisicao[ $campo ] ) ){
                $metodo = 'set' . ucfirst( $campo );
                if( method_exists( $objeto, $metodo ) ){
                    $data = DateTime::createFromFormat( 'd/m/Y', $corpoRequisicao[ $campo ] );
                    if( $data ){
                        $objeto->$metodo( $data );
                    }
                }
            }
        }
    }
}