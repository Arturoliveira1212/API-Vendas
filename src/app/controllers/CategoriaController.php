<?php

namespace app\controllers;

use app\exceptions\NaoEncontradoException;
use app\models\Categoria;
use core\QueryParams;

class CategoriaController extends Controller {

    protected function criar( array $corpoRequisicao ){
        $categoria = new Categoria();

        $campos = [ 'nome', 'descricao' ];
        $this->verificaEnvio( $campos, $corpoRequisicao );
        $this->povoarSimples( $categoria, $campos, $corpoRequisicao );

        return $categoria;
    }

    public function cadastrar(){
        $corpoRequisicao = $this->getRequest()->corpoRequisicao();
        $categoria = $this->criar( $corpoRequisicao );
        $id = $this->salvar( $categoria );

        $this->getResponse()->recursoCriado( $id, 'Categoria cadastrada com sucesso.' );
    }

    public function atualizar( array $parametros ){
        $id = intval( $parametros['categorias'] );
        $categoria = $this->obterComId( $id );
        if( ! $categoria instanceof Categoria ){
            throw new NaoEncontradoException( 'Categoria não encontrada.' );
        }

        $corpoRequisicao = $this->getRequest()->corpoRequisicao();
        $categoria = $this->criar( $corpoRequisicao );
        $categoria->setId( $id );
        $id = $this->salvar( $categoria );

        $this->getResponse()->recursoAlterado( 'Categoria atualizada com sucesso.' );
    }

    public function excluir( array $parametros ){
        $id = intval( $parametros['categorias'] );
        $categoria = $this->obterComId( $id );
        if( ! $categoria instanceof Categoria ){
            throw new NaoEncontradoException( 'Categoria não encontrada.' );
        }

        $this->desativarComId( $id );
        $this->getResponse()->recursoRemovido();
    }

    public function listarTodos(){
        $queryParams = new QueryParams( $this->getRequest()->parametrosRequisicao() );
        $categorias = $this->obterComRestricoes( $queryParams );
        $this->getResponse()->listarDados( $categorias );
    }

    public function listarComId( array $parametros ){
        $id = intval( $parametros['categorias'] );
        $categoria = $this->obterComId( $id );
        if( ! $categoria instanceof Categoria ){
            throw new NaoEncontradoException( 'Categoria não encontrada.' );
        }

        $this->getResponse()->listarDados( [ $categoria ] );
    }
}