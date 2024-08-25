<?php


require_once './bootstrap.php';

use core\App;
use app\exceptions\NaoEncontradoException;
use http\Response;

try {
    $app = new App();
    $app->executar();
} catch( NaoEncontradoException $e ){
    Response::recursoNaoEncontrado( $e );
} catch( Throwable $e ){
    Response::erroInternoAPI();
}