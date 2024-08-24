<?php

namespace app\views;

use Exception;
use http\HttpStatusCode;
use http\Request;
use http\Response;

abstract class View {
    public function obterDadosEnviados(){
        return Request::corpoRequisicao();
    }

    public function recursoNaoEncontrado( Exception $e ){
        $message = $e->getMessage() ?? 'Recurso não encontrado';
        Response::json( [ 'message' => $message ], HttpStatusCode::NAO_EXISTENTE );
    }

    public function recursoCriado( $idCadastrado, string $mensagemSucesso ){
        Response::json( [
            'message' => $mensagemSucesso,
            '_id' => $idCadastrado
        ], HttpStatusCode::RECURSO_CRIADO );
    }

    public function campoNaoEnviado( Exception $e ){
        $message = $e->getMessage() ?? 'Recurso não encontrado';
        Response::json( [ 'message' => $message ], HttpStatusCode::ERRO_CLIENTE );
    }

    public function erroAoSalvar( array $erro ){
        Response::json( $erro, HttpStatusCode::ERRO_CLIENTE );
    }

    public function erroInternoAPI(){
        Response::json( [ 'menssage' => 'Houve um erro interno.' ], HttpStatusCode::ERRO_SERVIDOR );
    }

    public function listarDados( array $dados ){
        Response::json( $dados, HttpStatusCode::OK );
    }

}