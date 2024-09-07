<?php

namespace app\controllers;

use app\exceptions\CampoNaoEnviadoException;
use app\exceptions\NaoEncontradoException;
use app\exceptions\ServiceException;
use app\models\Item;
use app\models\Produto;
use core\ClassFactory;
use http\Request;
use http\Response;

class ItemController extends Controller {
    public function __construct(){
        parent::__construct();
    }

    protected function criar( array $corpoRequisicao ){
        $item = new Item();

        $campos = [ 'produto', 'tamanho', 'estoque' ];
        $this->verificaEnvio( $campos, $corpoRequisicao );

        $camposSimples = [ 'tamanho', 'estoque' ];
        $this->povoarSimples( $item, $camposSimples, $corpoRequisicao );

        // TO DO => Implementar povoarObjetos generico.
        if( isset( $corpoRequisicao['produto']['id'] ) ){
            /** @var ProdutoController */
            $produtoController = ClassFactory::makeController( 'Produto' );
            $produto = $produtoController->obterComId( $corpoRequisicao['produto']['id'] );
            $item->setProduto( $produto );
        }

        return $item;
    }

    public function cadastrar( array $parametros ){
        $erro = [];

        try{
            $idProduto = intval( $parametros['produtos'] );
            /** @var ProdutoController */
            $produtoController = ClassFactory::makeController( 'Produto' );
            $produto = $produtoController->obterComId( $idProduto );
            if( ! $produto instanceof Produto ){
                throw new NaoEncontradoException( 'Produto não encontrado.' );
            }

            $corpoRequisicao = $this->getRequest()->corpoRequisicao();
            $item = $this->criar( $corpoRequisicao );
            $item->setProduto( $produto );

            $id = $this->getService()->salvar( $item, $erro );
            $this->getResponse()->recursoCriado( $id, 'Item cadastrado com sucesso.' );
        } catch( CampoNaoEnviadoException $e ){
            $this->getResponse()->campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            $this->getResponse()->erroAoSalvar( $e );
        }
    }

    public function atualizar( array $parametros ){
        $erro = [];

        try{
            $id = intval( $parametros['itens'] );
            $item = $this->getService()->obterComId( $id );
            if( ! $item instanceof Item ){
                throw new NaoEncontradoException( 'Item não encontrado.' );
            }

            $corpoRequisicao = $this->getRequest()->corpoRequisicao();
            $item = $this->criar( $corpoRequisicao );
            $item->setId( $id );
            $id = $this->getService()->salvar( $item, $erro );

            $this->getResponse()->recursoAlterado( 'Item atualizado com sucesso.' );
        } catch( NaoEncontradoException $e ){
            throw $e;
        } catch( CampoNaoEnviadoException $e ){
            $this->getResponse()->campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            $this->getResponse()->erroAoSalvar( $e );
        }
    }

    public function excluir( array $parametros ){
        $id = intval( $parametros['itens'] );
        $item = $this->getService()->obterComId( $id );
        if( ! $item instanceof Item ){
            throw new NaoEncontradoException( 'Item não encontrado.' );
        }

        $this->getService()->desativarComId( $id );
        $this->getResponse()->recursoRemovido();
    }

    public function listarTodos(){
        $item = $this->getService()->obterComRestricoes();
        $this->getResponse()->listarDados( $item );
    }

    public function listarComId( array $parametros ){
        $id = intval( $parametros['itens'] );
        $item = $this->getService()->obterComId( $id );
        if( ! $item instanceof Item ){
            throw new NaoEncontradoException( 'Item não encontrado.' );
        }

        $this->getResponse()->listarDados( [ $item ] );
    }
}