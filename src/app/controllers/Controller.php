<?php

namespace app\controllers;

use app\exceptions\CampoNaoEnviadoException;
use app\models\Model;
use app\services\Service;
use app\traits\ConversorDados;
use DateTime;
use http\Request;
use http\Response;
use Throwable;

abstract class Controller {
    protected Service $service;
    protected Request $request;
    protected Response $response;

    use ConversorDados;

    public function __construct( Service $service ){
        $this->setService( $service );
    }

    protected function getService(){
        return $this->service;
    }

    protected function setService( Service $service ){
        $this->service = $service;
    }

    public function getRequest(){
        return $this->request;
    }

    public function setRequest( Request $request ){
        $this->request = $request;
    }

    public function getResponse(){
        return $this->response;
    }

    public function setResponse( Response $response ){
        $this->response = $response;
    }

    abstract protected function criar( array $corpoRequisicao );

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
                    } catch( Throwable $e ){
                        throw new CampoNaoEnviadoException( "Corpo requisição inválido." );
                    }
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
                    } else {
                        throw new CampoNaoEnviadoException( "$campo não enviado" );
                    }
                }
            }
        }
    }

    public function salvar( Model $objeto ){
        return $this->getService()->salvar( $objeto );
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