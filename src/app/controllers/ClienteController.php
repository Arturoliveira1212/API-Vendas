<?php

namespace app\controllers;

use app\exceptions\CampoNaoEnviadoException;
use app\exceptions\NaoEncontradoException;
use app\exceptions\ServiceException;
use app\models\Cliente;
use http\Request;
use http\Response;

class ClienteController extends Controller {
    public function __construct(){
        parent::__construct();
    }

    public function criar( array $corpoRequisicao ){
        $cliente = new Cliente();

        $campos = [ 'nome', 'cpf', 'dataNascimento' ];
        $this->verificaEnvio( $campos, $corpoRequisicao );
        $camposSimples = [ 'nome', 'cpf' ];
        $this->povoarSimples( $cliente, $camposSimples, $corpoRequisicao );
        $camposDateTime = [ 'dataNascimento' ];
        $this->povoarDateTime( $cliente, $camposDateTime, $corpoRequisicao );

        return $cliente;
    }

    public function cadastrar(){
        $erro = [];

        try{
            $corpoRequisicao = Request::corpoRequisicao();
            $cliente = $this->criar( $corpoRequisicao );
            $id = $this->getService()->salvar( $cliente, $erro );

            Response::recursoCriado( $id, 'Cliente cadastrado com sucesso.' );
        } catch( CampoNaoEnviadoException $e ){
            Response::campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            Response::erroAoSalvar( $erro );
        }
    }

    public function atualizar( array $parametros ){
        $erro = [];

        try{
            $id = intval( $parametros['clientes'] );
            $cliente = $this->getService()->obterComId( $id );
            if( ! $cliente instanceof Cliente ){
                throw new NaoEncontradoException( 'Cliente não encontrado.' );
            }

            $corpoRequisicao = Request::corpoRequisicao();
            $cliente = $this->criar( $corpoRequisicao );
            $cliente->setId( $id );
            $id = $this->getService()->salvar( $cliente, $erro );

            Response::recursoAlterado( 'Cliente atualizado com sucesso.' );
        } catch( NaoEncontradoException $e ){
            throw $e;
        } catch( CampoNaoEnviadoException $e ){
            Response::campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            Response::erroAoSalvar( $erro );
        }
    }

    public function excluir( array $parametros ){
        $id = intval( $parametros['clientes'] );
        $cliente = $this->getService()->obterComId( $id );
        if( ! $cliente instanceof Cliente ){
            throw new NaoEncontradoException( 'Cliente não encontrado.' );
        }

        $this->getService()->desativarComId( $id );
        Response::recursoRemovido();
    }

    public function listarTodos(){
        $clientes = $this->getService()->obterComRestricoes();
        Response::listarDados( $clientes );
    }

    public function listarComId( array $parametros ){
        $id = intval( $parametros['clientes'] );
        $cliente = $this->getService()->obterComId( $id );
        if( ! $cliente instanceof Cliente ){
            throw new NaoEncontradoException( 'Cliente não encontrado.' );
        }

        Response::listarDados( [ $cliente ] );
    }
}