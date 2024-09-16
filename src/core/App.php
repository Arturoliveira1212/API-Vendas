<?php

namespace core;

use app\controllers\Controller;
use app\exceptions\CampoNaoEnviadoException;
use core\ClassFactory;
use app\exceptions\NaoEncontradoException;
use app\exceptions\ServiceException;
use Exception;
use http\HttpStatusCode;
use router\Router;
use Throwable;

class App {
    private Request $request;
    private Response $response;

    public function __construct( Request $request, Response $response ){
        $this->request = $request;
        $this->response = $response;
    }

    public function executar(){
        try {
            $uri = $this->request->getUri();
            $metodoRequisicao = $this->request->getMetodoHttp();
            $informacoesRota = $this->obterInformacoesRota( $uri, $metodoRequisicao );
            if( empty( $informacoesRota ) ){
                throw new NaoEncontradoException( 'Rota não encontrada.' );
            }

            list( $nomeController, $metodo ) = explode( '@', array_values( $informacoesRota )[0] );

            /** @var Controller */
            $controller = ClassFactory::makeController( $nomeController );
            if( ! method_exists( $controller, $metodo ) ){
                throw new Exception( 'Método não encontrado.' );
            }

            $parametrosRota = $this->obterParametrosRota( $informacoesRota, $uri );
            $this->request->setParametrosRota( $parametrosRota );

            $this->response = $controller->$metodo( $this->request );
        } catch( NaoEncontradoException $e ){
            $this->response = new Response( HttpStatusCode::NOT_FOUND, $e->getMessage() ?? 'Conteúdo não encontrado.' );
        } catch( CampoNaoEnviadoException $e ){
            $this->response = new Response( HttpStatusCode::BAD_REQUEST,  $e->getMessage() ?? 'Conteúdo não encontrado.' );
        } catch( ServiceException $e ){
            $this->response = new Response( HttpStatusCode::BAD_REQUEST, 'Houve um erro ao salvar.', json_decode( $e->getMessage(), true ) );
        } catch( Throwable $e ){
            $this->response = new Response( HttpStatusCode::INTERNAL_SERVER_ERROR, 'Houve um erro interno.' );
        }

        $this->response->send();
    }

    /**
     * Método responsável por retornar um array com informações da rota.
     * @return array [ /home => Home@index ]
     */
    private function obterInformacoesRota( string $uri, string $metodoRequisicao ){
        $informacoesRota = [];
        $rotas = $this->obterRotasParaMetodo( $metodoRequisicao );

        if( array_key_exists( $uri, $rotas ) ){
            $informacoesRota = [ $uri => $rotas[ $uri ] ];
        } else {
            $informacoesRota = $this->obterInformacoesRotaDinamica( $rotas, $uri );
        }

        return $informacoesRota;
    }

    private function obterRotasParaMetodo( string $metodoRequisicao ){
        require_once './src/router/rotas.php';
        return (array) Router::rotas( $metodoRequisicao );
    }

    private function obterInformacoesRotaDinamica( array $rotas, string $uri ){
        return array_filter( $rotas, function( $rota ) use ($uri ){
            $regex = str_replace( '/', '\/', ltrim( $rota, '/' ) );
            return preg_match( "/^$regex$/", ltrim( $uri, '/' ) );
        }, ARRAY_FILTER_USE_KEY );
    }

    private function obterParametrosRota( array $informacoesRota, string $uri ){
        $conteudoRota = array_keys( $informacoesRota )[0];
        $arrayUri = explode( '/', ltrim( $uri, '/' ) );
        $parametros = array_diff(
            $arrayUri,
            explode( '/', ltrim( $conteudoRota, '/' ) )
        );

        return $this->formatarParametrosRota( $parametros, $arrayUri );
    }

    private function formatarParametrosRota( array $parametros, array $arrayUri ){
        $parametrosFormatado = [];

        foreach( $parametros as $index => $parametro ){
            $parametrosFormatado[ $arrayUri[ $index - 1 ] ] = $parametro;
        }

        return $parametrosFormatado;
    }
}