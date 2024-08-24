<?php

namespace http;

class Request {
    /**
     * Método responsável por retornar a URI da requisição enviada.
     *
     * @return string
     */
    public static function uri(){
        return parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
    }

    /**
     * Método responsável por retornar o método da requisição enviada.
     *
     * @return string
     */
    public static function metodo(){
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Método responsável por retornar o corpo da requisição.
     *
     * @return array
     */
    public static function corpoRequisicao(){
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
}