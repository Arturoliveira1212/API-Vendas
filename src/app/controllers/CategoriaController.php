<?php

namespace app\controllers;

use app\exceptions\CampoNaoEnviadoException;
use app\exceptions\NaoEncontradoException;
use app\exceptions\ServiceException;
use app\models\Categoria;

class CategoriaController extends Controller {

    protected function criar( array $corpoRequisicao ){
        $categoria = new Categoria();

        $campos = [ 'nome', 'descricao' ];
        $this->verificaEnvio( $campos, $corpoRequisicao );
        $this->povoarSimples( $categoria, $campos, $corpoRequisicao );

        return $categoria;
    }

    public function cadastrar(){
        $erro = [];

        try{
            $corpoRequisicao = $this->getRequest()->corpoRequisicao();
            $categoria = $this->criar( $corpoRequisicao );
            $id = $this->getService()->salvar( $categoria, $erro );

            $this->getResponse()->recursoCriado( $id, 'Categoria cadastrada com sucesso.' );
        } catch( CampoNaoEnviadoException $e ){
            $this->getResponse()->campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            $this->getResponse()->erroAoSalvar( $e );
        }
    }

    public function atualizar( array $parametros ){
        $erro = [];

        try{
            $id = intval( $parametros['categorias'] );
            $categoria = $this->getService()->obterComId( $id );
            if( ! $categoria instanceof Categoria ){
                throw new NaoEncontradoException( 'Categoria não encontrada.' );
            }

            $corpoRequisicao = $this->getRequest()->corpoRequisicao();
            $categoria = $this->criar( $corpoRequisicao );
            $categoria->setId( $id );
            $id = $this->getService()->salvar( $categoria, $erro );

            $this->getResponse()->recursoAlterado( 'Categoria atualizada com sucesso.' );
        } catch( NaoEncontradoException $e ){
            throw $e;
        } catch( CampoNaoEnviadoException $e ){
            $this->getResponse()->campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            $this->getResponse()->erroAoSalvar( $e );
        }
    }

    public function excluir( array $parametros ){
        $id = intval( $parametros['categorias'] );
        $categoria = $this->getService()->obterComId( $id );
        if( ! $categoria instanceof Categoria ){
            throw new NaoEncontradoException( 'Categoria não encontrada.' );
        }

        $this->getService()->desativarComId( $id );
        $this->getResponse()->recursoRemovido();
    }

    public function listarTodos(){
        $categorias = $this->getService()->obterComRestricoes();
        $this->getResponse()->listarDados( $categorias );
    }

    public function listarComId( array $parametros ){
        $id = intval( $parametros['categorias'] );
        $categoria = $this->getService()->obterComId( $id );
        if( ! $categoria instanceof Categoria ){
            throw new NaoEncontradoException( 'Categoria não encontrada.' );
        }

        $this->getResponse()->listarDados( [ $categoria ] );
    }
}