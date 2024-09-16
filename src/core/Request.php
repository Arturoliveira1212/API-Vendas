<?php

namespace core;

use http\HttpMethod;

class Request {
    private string $uri = '';
    private string $metodoHttp = HttpMethod::GET;
    private array $corpoRequisicao = [];
    private array $parametrosRequisicao = [];
    private array $parametrosRota = [];

    public function __construct(){
        $this->setUri( $this->loadUri() );
        $this->setMetodoHttp( $this->loadMetodoHttp() );
        $this->setCorpoRequisicao( $this->loadCorpoRequisicao() );
        $this->setParametrosRequisicao( $this->loadParametrosRequisicao() );
    }

    public function getUri(): string {
        return $this->uri;
    }

    public function setUri( string $uri ){
        $this->uri = $uri;
    }

    private function loadUri(){
        return isset( $_SERVER['REQUEST_URI'] ) ? parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) : '';
    }

    public function getMetodoHttp() :string {
        return $this->metodoHttp;
    }

    public function setMetodoHttp( string $metodoHttp ){
        $this->metodoHttp = $metodoHttp;
    }

    private function loadMetodoHttp(){
        return isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : HttpMethod::GET;
    }

    public function getCorpoRequisicao() :array {
        return $this->corpoRequisicao;
    }

    public function setCorpoRequisicao( array $corpoRequisicao ){
        $this->corpoRequisicao = $corpoRequisicao;
    }

    private function loadCorpoRequisicao(){
        return json_decode( file_get_contents( 'php://input' ), true ) ?? [];
    }

    public function getParametrosRequisicao() :array {
        return $this->parametrosRequisicao;
    }

    public function setParametrosRequisicao( array $parametrosRequisicao ){
        $this->parametrosRequisicao = $parametrosRequisicao;
    }

    private function loadParametrosRequisicao(){
        return isset( $_GET ) ? $_GET : [];
    }

    public function getParametrosRota() :array {
        return $this->parametrosRota;
    }

    public function setParametrosRota( array $parametrosRota ){
        $this->parametrosRota = $parametrosRota;
    }
}
