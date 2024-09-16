<?php

namespace core;

use http\HttpStatusCode;

class Response {
    private int $statusCode = HttpStatusCode::OK;
    private string $message = '';
    private array $data = [];
    private array $headers = [
        'Content-Type' => 'application/json'
    ];

    public function __construct( int $statusCode = HttpStatusCode::OK, $message = '', array $data = [], array $headers = [] ){
        $this->setStatusCode( $statusCode );
        $this->setMessage( $message );
        $this->setData( $data );
        $this->setHeaders( $headers );
    }

    public function getStatusCode() :int {
        return $this->statusCode;
    }

    public function setStatusCode( int $statusCode ){
        $this->statusCode = $statusCode;
    }

    public function getMessage() :string {
        return $this->message;
    }

    public function setMessage( string $message ){
        $this->message = $message;
    }

    public function getData() :array {
        return $this->data;
    }

    public function setData( array $data ){
        $this->data = $data;
    }

    public function getHeaders() :array {
        return $this->headers;
    }

    public function setHeaders( array $headers ){
        $this->headers = array_merge( $this->headers, $headers );
    }

    public function send(){
        http_response_code( $this->getStatusCode() );
        foreach( $this->getHeaders() as $key => $value ){
            header( "$key: $value" );
        }

        $conteudoRetorno = $this->getData();
        if( ! empty( $this->getMessage() ) ){
            $message = [ 'message' => $this->getMessage() ];
            $conteudoRetorno = array_merge( $message, $this->getData() );
        }

        echo json_encode( $conteudoRetorno );
    }
}