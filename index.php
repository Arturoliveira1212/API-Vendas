<?php

require_once './bootstrap.php';

use core\App;
use core\Request;
use core\Response;

$request = new Request();
$response = new Response();

$app = new App( $request, $response );
$app->executar();