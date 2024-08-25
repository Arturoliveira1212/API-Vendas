<?php

namespace http;

use Exception;

class Response {

    public static function recursoNaoEncontrado( Exception $e ){
        $message = $e->getMessage() ?? 'Recurso não encontrado';
        self::json( [ 'message' => $message ], HttpStatusCode::NOT_FOUND );
    }

    public static function recursoCriado( $idCadastrado, string $mensagemSucesso ){
        self::json( [
            'message' => $mensagemSucesso,
            '_id' => $idCadastrado
        ], HttpStatusCode::CREATED );
    }

    public static function recursoAlterado( string $mensagemSucesso ){
        self::json( [
            'message' => $mensagemSucesso
        ], HttpStatusCode::OK );
    }

    public static function campoNaoEnviado( Exception $e ){
        $message = $e->getMessage() ?? 'Recurso não encontrado';
        self::json( [ 'message' => $message ], HttpStatusCode::BAD_REQUEST );
    }

    public static function erroAoSalvar( array $erro ){
        self::json( $erro, HttpStatusCode::BAD_REQUEST );
    }

    public static function erroInternoAPI(){
        self::json( [ 'menssage' => 'Houve um erro interno.' ], HttpStatusCode::INTERNAL_SERVER_ERROR );
    }

    public static function listarDados( array $dados ){
        self::json( $dados, HttpStatusCode::OK );
    }

    public static function recursoRemovido(){
        self::json( [], HttpStatusCode::NO_CONTENT );
    }

    public static function json( array $data = [], int $status = HttpStatusCode::OK ){
        http_response_code( $status );
        header( 'Content-Type: application/json' );
        echo json_encode( $data );
    }
}