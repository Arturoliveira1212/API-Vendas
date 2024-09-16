<?php

namespace app\builders;

use core\Request;

class RequestBuilder {
    private Request $request;

    private function __construct(){
        $this->request = new Request();
    }

    public static function novo(){
        return new RequestBuilder();
    }

    public function comUri( string $uri){
        $this->request->setUri( $uri );
        return $this;
    }

    public function comMetodo( string $metodo ){
        $this->request->setMetodoHttp( $metodo );
        return $this;
    }

    public function comCorpoRequisicao( array $corpoRequisicao ){
        $this->request->setCorpoRequisicao( $corpoRequisicao );
        return $this;
    }

    public function comParametrosRequisicao( array $parametrosRequisicao ){
        $this->request->setParametrosRequisicao( $parametrosRequisicao );
        return $this;
    }

    public function comParametrosRota( array $parametrosRota ){
        $this->request->setParametrosRota( $parametrosRota );
        return $this;
    }

    public function build(){
        return $this->request;
    }
}