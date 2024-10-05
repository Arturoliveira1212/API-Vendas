<?php

namespace app\controllers;

use app\exceptions\NaoEncontradoException;
use app\models\Cliente;
use core\QueryParams;
use core\Request;
use core\Response;
use http\HttpStatusCode;

class ClienteController extends Controller {

    protected function criar( array $corpoRequisicao ) :Cliente {
        $cliente = new Cliente();

        $campos = [ 'nome', 'cpf', 'dataNascimento' ];
        $this->verificaEnvio( $campos, $corpoRequisicao );
        $camposSimples = [ 'nome', 'cpf' ];
        $this->povoarSimples( $cliente, $camposSimples, $corpoRequisicao );
        $camposDateTime = [ 'dataNascimento' ];
        $this->povoarDateTime( $cliente, $camposDateTime, $corpoRequisicao );

        return $cliente;
    }

    public function cadastrar( Request $request ) :Response {
        $cliente = $this->criar( $request->getCorpoRequisicao() );
        $id = $this->getService()->salvar( $cliente );

        return new Response( HttpStatusCode::CREATED, 'Cliente cadastrado com sucesso.', [ 'id' => $id ] );
    }

    public function atualizar( Request $request ) :Response {
        $id = intval( $request->getParametrosRota()['clientes'] );
        $cliente = $this->getService()->obterComId( $id );
        if( ! $cliente instanceof Cliente ){
            throw new NaoEncontradoException( 'Cliente não encontrado.' );
        }

        $cliente = $this->criar( $request->getCorpoRequisicao() );
        $cliente->setId( $id );
        $id = $this->getService()->salvar( $cliente );

        return new Response( HttpStatusCode::OK, 'Cliente atualizado com sucesso.' );
    }

    public function excluir( Request $request ) :Response {
        $id = intval( $request->getParametrosRota()['clientes'] );
        $cliente = $this->getService()->obterComId( $id );
        if( ! $cliente instanceof Cliente ){
            throw new NaoEncontradoException( 'Cliente não encontrado.' );
        }

        $this->getService()->desativarComId( $id );

        return new Response( HttpStatusCode::NO_CONTENT );
    }

    public function listarTodos( Request $request ) :Response {
        $queryParams = new QueryParams( $request->getParametrosRequisicao() );
        $clientes = $this->getService()->obterComRestricoes( $queryParams );

        return new Response( HttpStatusCode::OK, '', $clientes );
    }

    public function listarComId( Request $request ) :Response {
        $id = intval( $request->getParametrosRota()['clientes'] );
        $cliente = $this->getService()->obterComId( $id );
        if( ! $cliente instanceof Cliente ){
            throw new NaoEncontradoException( 'Cliente não encontrado.' );
        }

        return new Response( HttpStatusCode::OK, '', [ $cliente ] );
    }
}