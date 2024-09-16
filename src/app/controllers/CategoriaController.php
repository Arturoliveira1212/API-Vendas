<?php

namespace app\controllers;

use app\exceptions\NaoEncontradoException;
use app\models\Categoria;
use core\QueryParams;
use core\Request;
use core\Response;
use http\HttpStatusCode;

class CategoriaController extends Controller {

    protected function criar( array $corpoRequisicao ) :Categoria {
        $categoria = new Categoria();

        $campos = [ 'nome', 'descricao' ];
        $this->verificaEnvio( $campos, $corpoRequisicao );
        $this->povoarSimples( $categoria, $campos, $corpoRequisicao );

        return $categoria;
    }

    public function cadastrar( Request $request ) :Response {
        $categoria = $this->criar( $request->getCorpoRequisicao() );
        $id = $this->service->salvar( $categoria );

        return new Response( HttpStatusCode::CREATED, 'Categoria cadastrada com sucesso.', [ 'id' => $id ] );
    }

    public function atualizar( Request $request ) :Response {
        $id = intval( $request->getParametrosRota()['categorias'] );
        $categoria = $this->getService()->obterComId( $id );
        if( ! $categoria instanceof Categoria ){
            throw new NaoEncontradoException( 'Categoria não encontrada.' );
        }

        $categoria = $this->criar( $request->getCorpoRequisicao() );
        $categoria->setId( $id );
        $id = $this->getService()->salvar( $categoria );

        return new Response( HttpStatusCode::OK, 'Categoria atualizada com sucesso.' );
    }

    public function excluir( Request $request ) :Response {
        $id = intval( $request->getParametrosRota()['categorias'] );
        $categoria = $this->getService()->obterComId( $id );
        if( ! $categoria instanceof Categoria ){
            throw new NaoEncontradoException( 'Categoria não encontrada.' );
        }

        $this->getService()->desativarComId( $id );

        return new Response( HttpStatusCode::NO_CONTENT );
    }

    public function listarTodos( Request $request ) :Response {
        $queryParams = new QueryParams( $request->getParametrosRequisicao() );
        $categorias = $this->getService()->obterComRestricoes( $queryParams );

        return new Response( HttpStatusCode::OK, '', $categorias );
    }

    public function listarComId( Request $request ) :Response {
        $id = intval( $request->getParametrosRota()['categorias'] );
        $categoria = $this->getService()->obterComId( $id );
        if( ! $categoria instanceof Categoria ){
            throw new NaoEncontradoException( 'Categoria não encontrada.' );
        }

        return new Response( HttpStatusCode::OK, '', [ $categoria ] );
    }
}