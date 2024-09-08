<?php


require_once './bootstrap.php';

use core\App;
use http\Request;
use http\Response;

$request = new Request();
$response = new Response();

$app = new App( $request, $response );
$app->executar();