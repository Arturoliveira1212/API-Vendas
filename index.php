<?php


require_once './bootstrap.php';

use core\App;
use app\exceptions\NaoEncontradoException;
use app\models\Categoria;
use http\Request;
use http\Response;

$request = new Request();
$response = new Response();

try {
    $app = new App( $request, $response );
    $app->executar();
} catch( NaoEncontradoException $e ){
    $response->recursoNaoEncontrado( $e );
} catch( Throwable $e ){
    dd( $e );
    $response->erroInternoAPI();
}