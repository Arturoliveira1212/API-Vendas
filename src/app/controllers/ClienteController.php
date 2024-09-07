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

    protected function criar( array $corpoRequisicao ){
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
            $corpoRequisicao = $this->getRequest()->corpoRequisicao();
            $cliente = $this->criar( $corpoRequisicao );
            $id = $this->getService()->salvar( $cliente, $erro );

            $this->getResponse()->recursoCriado( $id, 'Cliente cadastrado com sucesso.' );
        } catch( CampoNaoEnviadoException $e ){
            $this->getResponse()->campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            $this->getResponse()->erroAoSalvar( $e );
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

            $corpoRequisicao = $this->getRequest()->corpoRequisicao();
            $cliente = $this->criar( $corpoRequisicao );
            $cliente->setId( $id );
            $id = $this->getService()->salvar( $cliente, $erro );

            $this->getResponse()->recursoAlterado( 'Cliente atualizado com sucesso.' );
        } catch( NaoEncontradoException $e ){
            throw $e;
        } catch( CampoNaoEnviadoException $e ){
            $this->getResponse()->campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            $this->getResponse()->erroAoSalvar( $e );
        }
    }

    public function excluir( array $parametros ){
        $id = intval( $parametros['clientes'] );
        $cliente = $this->getService()->obterComId( $id );
        if( ! $cliente instanceof Cliente ){
            throw new NaoEncontradoException( 'Cliente não encontrado.' );
        }

        $this->getService()->desativarComId( $id );
        $this->getResponse()->recursoRemovido();
    }

    public function listarTodos(){
        $clientes = $this->getService()->obterComRestricoes();
        $this->getResponse()->listarDados( $clientes );
    }

    public function listarComId( array $parametros ){
        $id = intval( $parametros['clientes'] );
        $cliente = $this->getService()->obterComId( $id );
        if( ! $cliente instanceof Cliente ){
            throw new NaoEncontradoException( 'Cliente não encontrado.' );
        }

        $this->getResponse()->listarDados( [ $cliente ] );
    }
}