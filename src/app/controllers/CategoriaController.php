<?php

namespace app\controllers;

use app\exceptions\CampoNaoEnviadoException;
use app\exceptions\NaoEncontradoException;
use app\exceptions\ServiceException;
use app\models\Categoria;
use http\Request;
use http\Response;

class CategoriaController extends Controller {
    public function __construct(){
        parent::__construct();
    }

    public function criar( array $corpoRequisicao ){
        $categoria = new Categoria();

        $campos = [ 'nome', 'descricao' ];
        $this->verificaEnvio( $campos, $corpoRequisicao );
        $this->povoarSimples( $categoria, $campos, $corpoRequisicao );

        return $categoria;
    }

    public function cadastrar(){
        $erro = [];

        try{
            $corpoRequisicao = Request::corpoRequisicao();
            $categoria = $this->criar( $corpoRequisicao );
            $id = $this->getService()->salvar( $categoria, $erro );

            Response::recursoCriado( $id, 'Categoria cadastrada com sucesso.' );
        } catch( CampoNaoEnviadoException $e ){
            Response::campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            Response::erroAoSalvar( $erro );
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

            $corpoRequisicao = Request::corpoRequisicao();
            $categoria = $this->criar( $corpoRequisicao );
            $categoria->setId( $id );
            $id = $this->getService()->salvar( $categoria, $erro );

            Response::recursoAlterado( 'Categoria atualizada com sucesso.' );
        } catch( NaoEncontradoException $e ){
            throw $e;
        } catch( CampoNaoEnviadoException $e ){
            Response::campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            Response::erroAoSalvar( $erro );
        }
    }

    public function excluir( array $parametros ){
        $id = intval( $parametros['categorias'] );
        $categoria = $this->getService()->obterComId( $id );
        if( ! $categoria instanceof Categoria ){
            throw new NaoEncontradoException( 'Categoria não encontrada.' );
        }

        $this->getService()->desativarComId( $id );
        Response::recursoRemovido();
    }

    public function listarTodos(){
        $categorias = $this->getService()->obterComRestricoes();
        Response::listarDados( $categorias );
    }

    public function listarComId( array $parametros ){
        $id = intval( $parametros['categorias'] );
        $categoria = $this->getService()->obterComId( $id );
        if( ! $categoria instanceof Categoria ){
            throw new NaoEncontradoException( 'Categoria não encontrada.' );
        }

        Response::listarDados( [ $categoria ] );
    }
}