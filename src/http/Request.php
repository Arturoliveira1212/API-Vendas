<?php

namespace http;

class Request {
    private $uri;
    private $method;
    private $body;

    public function __construct() {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->body = json_decode(file_get_contents('php://input'), true) ?? [];
    }

    /**
     * Método responsável por retornar a URI da requisição enviada.
     *
     * @return string
     */
    public function uri() {
        return $this->uri;
    }

    /**
     * Método responsável por retornar o método da requisição enviada.
     *
     * @return string
     */
    public function metodo() {
        return $this->method;
    }

    /**
     * Método responsável por retornar o corpo da requisição.
     *
     * @return array
     */
    public function corpoRequisicao() {
        return $this->body;
    }
}
