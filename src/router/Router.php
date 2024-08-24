<?php

namespace router;

use http\HttpMethod;

class Router {
    private static array $rotas = [];

    public static function get(string $path, string $action){
        self::$rotas[ HttpMethod::GET ][ $path ] = $action;
    }

    public static function post(string $path, string $action){
        self::$rotas[ HttpMethod::POST ][ $path ] = $action;
    }

    public static function put(string $path, string $action){
        self::$rotas[ HttpMethod::PUT ][ $path ] = $action;
    }

    public static function delete(string $path, string $action){
        self::$rotas[ HttpMethod::DELETE ][ $path ] = $action;
    }

    public static function rotas( string $metodoRequisicao ){
        return self::$rotas[ $metodoRequisicao ];
    }
}