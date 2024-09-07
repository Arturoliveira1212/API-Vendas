<?php

namespace app\controllers;

use app\exceptions\CampoNaoEnviadoException;
use app\exceptions\NaoEncontradoException;
use app\exceptions\ServiceException;
use app\models\Categoria;
use app\models\Produto;
use core\ClassFactory;
use http\Request;
use http\Response;

class ProdutoController extends Controller {
    public function __construct(){
        parent::__construct();
    }

    protected function criar( array $corpoRequisicao ){
        $produto = new Produto();

        $campos = [ 'referencia', 'nome', 'descricao', 'peso', 'categoria' ];
        $this->verificaEnvio( $campos, $corpoRequisicao );

        $camposSimples = [ 'referencia', 'nome', 'descricao', 'peso' ];
        $this->povoarSimples( $produto, $camposSimples, $corpoRequisicao );

        // TO DO => Implementar povoarObjetos generico.
        if( isset( $corpoRequisicao['categoria']['id'] ) ){
            /** @var CategoriaController */
            $categoriaController = ClassFactory::makeController( 'Categoria' );
            $categoria = $categoriaController->obterComId( $corpoRequisicao['categoria']['id'] );
            $produto->setCategoria( $categoria );
        }

        return $produto;
    }

    public function cadastrar(){
        $erro = [];

        try{
            $corpoRequisicao = $this->getRequest()->corpoRequisicao();
            $produto = $this->criar( $corpoRequisicao );
            $id = $this->getService()->salvar( $produto, $erro );
            $this->getResponse()->recursoCriado( $id, 'Produto cadastrado com sucesso.' );
        } catch( CampoNaoEnviadoException $e ){
            $this->getResponse()->campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            $this->getResponse()->erroAoSalvar( $e );
        }
    }

    public function atualizar( array $parametros ){
        $erro = [];

        try{
            $id = intval( $parametros['produtos'] );
            $produto = $this->getService()->obterComId( $id );
            if( ! $produto instanceof Produto ){
                throw new NaoEncontradoException( 'Produto não encontrado.' );
            }

            $corpoRequisicao = $this->getRequest()->corpoRequisicao();
            $produto = $this->criar( $corpoRequisicao );
            $produto->setId( $id );
            $id = $this->getService()->salvar( $produto, $erro );

            $this->getResponse()->recursoAlterado( 'Produto atualizado com sucesso.' );
        } catch( NaoEncontradoException $e ){
            throw $e;
        } catch( CampoNaoEnviadoException $e ){
            $this->getResponse()->campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            $this->getResponse()->erroAoSalvar( $e );
        }
    }

    public function excluir( array $parametros ){
        $id = intval( $parametros['produtos'] );
        $produto = $this->getService()->obterComId( $id );
        if( ! $produto instanceof Produto ){
            throw new NaoEncontradoException( 'Produto não encontrado.' );
        }

        $this->getService()->desativarComId( $id );
        $this->getResponse()->recursoRemovido();
    }

    public function listarTodos(){
        $produto = $this->getService()->obterComRestricoes();
        $this->getResponse()->listarDados( $produto );
    }

    public function listarComId( array $parametros ){
        $id = intval( $parametros['produtos'] );
        $produto = $this->getService()->obterComId( $id );
        if( ! $produto instanceof Produto ){
            throw new NaoEncontradoException( 'Produto não encontrado.' );
        }

        $this->getResponse()->listarDados( [ $produto ] );
    }
}