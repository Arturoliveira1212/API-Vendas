<?php

namespace app\controllers;

use app\exceptions\CampoNaoEnviadoException;
use app\exceptions\NaoEncontradoException;
use app\exceptions\ServiceException;
use app\models\Categoria;
use app\traits\ConversorDados;
use app\views\CategoriaView;

class CategoriaController extends Controller {
    public function __construct(){
        parent::__construct();
    }

    use ConversorDados;

    public function listarTodos(){
        /** @var CategoriaView */
        $categoriaView = $this->getView();

        $categorias = $this->getService()->obterComRestricoes();
        $categoriaView->listarDados( $categorias );
    }

    public function listarComId( array $parametros ){
        $id = intval( $parametros['categorias'] );
        $categoria = $this->getService()->obterComId( $id );
        if( ! $categoria instanceof Categoria ){
            throw new NaoEncontradoException( 'Categoria não encontrada.' );
        }

        /** @var CategoriaView */
        $categoriaView = $this->getView();
        $categoriaView->listarDados( [ $categoria ] );
    }

    public function criar( array $corpoRequisicao ){
        $categoria = new Categoria();

        $campos = [ 'nome', 'descricao' ];
        $this->verificaEnvio( $campos, $corpoRequisicao );
        $this->povoarSimples( $categoria, $campos, $corpoRequisicao );

        return $categoria;
    }

    public function cadastrar(){
        /** @var CategoriaView */
        $categoriaView = $this->getView();
        $erro = [];

        try{
            $corpoRequisicao = $categoriaView->obterDadosEnviados();
            $categoria = $this->criar( $corpoRequisicao );
            $id = $this->getService()->salvar( $categoria, $erro );

            $categoriaView->recursoCriado( $id, 'Categoria cadastrada com sucesso.' );
        } catch( CampoNaoEnviadoException $e ){
            $categoriaView->campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            $categoriaView->erroAoSalvar( $erro );
        }
    }

    public function atualizar(){

    }

    public function excluir( array $parametros ){
        $id = intval( $parametros['categorias'] );
    }
}