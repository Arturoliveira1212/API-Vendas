<?php


require_once './bootstrap.php';

use app\exceptions\CampoNaoEnviadoException;
use core\App;
use app\exceptions\NaoEncontradoException;
use app\exceptions\ServiceException;
use http\Request;
use http\Response;

$request = new Request();
$response = new Response();

try {
    $app = new App( $request, $response );
    $app->executar();
} catch( NaoEncontradoException $e ){
    $response->recursoNaoEncontrado( $e );
} catch( CampoNaoEnviadoException $e ){
    $response->campoNaoEnviado( $e );
} catch( ServiceException $e ){
    $response->erroAoSalvar( $e );
} catch( Throwable $e ){
    dd( $e );
    $response->erroInternoAPI();
}