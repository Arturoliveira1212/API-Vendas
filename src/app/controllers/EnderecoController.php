<?php

namespace app\controllers;

use app\exceptions\CampoNaoEnviadoException;
use app\exceptions\NaoEncontradoException;
use app\exceptions\ServiceException;
use app\models\Cliente;
use app\models\Endereco;
use core\ClassFactory;
use http\Request;
use http\Response;

class EnderecoController extends Controller {
    public function __construct(){
        parent::__construct();
    }

    public function criar( array $corpoRequisicao ){
        $endereco = new Endereco();

        $campos = [ 'cep', 'logradouro', 'cidade', 'bairro', 'numero' ];
        $this->verificaEnvio( $campos, $corpoRequisicao );

        $camposSimples = [ 'cep', 'logradouro', 'cidade', 'bairro', 'numero', 'complemento' ];
        $this->povoarSimples( $endereco, $camposSimples, $corpoRequisicao );

        return $endereco;
    }

    public function cadastrar( array $parametros ){
        $erro = [];

        try{
            $idCliente = intval( $parametros['clientes'] );
            /** @var ClienteController */
            $clienteController = ClassFactory::makeController( 'Cliente' );
            $cliente = $clienteController->obterComId( $idCliente );
            if( ! $cliente instanceof Cliente ){
                throw new NaoEncontradoException( 'Cliente não encontrado.' );
            }

            $corpoRequisicao = Request::corpoRequisicao();
            $endereco = $this->criar( $corpoRequisicao );
            $endereco->setCliente( $cliente );

            $id = $this->getService()->salvar( $endereco, $erro );
            Response::recursoCriado( $id, 'Endereco cadastrado com sucesso.' );
        } catch( CampoNaoEnviadoException $e ){
            Response::campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            Response::erroAoSalvar( $erro );
        }
    }

    public function atualizar( array $parametros ){
        $erro = [];

        try{
            $idCliente = intval( $parametros['clientes'] );
            /** @var ClienteController */
            $clienteController = ClassFactory::makeController( 'Cliente' );
            $cliente = $clienteController->obterComId( $idCliente );
            if( ! $cliente instanceof Cliente ){
                throw new NaoEncontradoException( 'Cliente não encontrado.' );
            }

            $id = intval( $parametros['enderecos'] );
            $endereco = $this->getService()->obterComId( $id );
            if( ! $endereco instanceof Endereco ){
                throw new NaoEncontradoException( 'Endereco não encontrado.' );
            }

            $corpoRequisicao = Request::corpoRequisicao();
            $endereco = $this->criar( $corpoRequisicao );
            $endereco->setId( $id );
            $endereco->setCliente( $cliente );

            $id = $this->getService()->salvar( $endereco, $erro );
            Response::recursoAlterado( 'Endereco atualizado com sucesso.' );
        } catch( NaoEncontradoException $e ){
            throw $e;
        } catch( CampoNaoEnviadoException $e ){
            Response::campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            Response::erroAoSalvar( $erro );
        }
    }

    public function excluir( array $parametros ){
        $id = intval( $parametros['enderecos'] );
        $endereco = $this->getService()->obterComId( $id );
        if( ! $endereco instanceof Endereco ){
            throw new NaoEncontradoException( 'Endereco não encontrado.' );
        }

        $this->getService()->desativarComId( $id );
        Response::recursoRemovido();
    }

    public function listarTodos(){
        $enderecos = $this->getService()->obterComRestricoes();
        Response::listarDados( $enderecos );
    }

    public function listarComId( array $parametros ){
        $id = intval( $parametros['enderecos'] );
        $endereco = $this->getService()->obterComId( $id );
        if( ! $endereco instanceof Endereco ){
            throw new NaoEncontradoException( 'Endereco não encontrado.' );
        }

        Response::listarDados( [ $endereco ] );
    }

    public function listarComCliente( array $parametros ){
        $idCliente = intval( $parametros['clientes'] );

        /** @var ClienteController */
        $clienteController = ClassFactory::makeController( 'Cliente' );
        $cliente = $clienteController->obterComId( $idCliente );
        if( ! $cliente instanceof Cliente ){
            throw new NaoEncontradoException( 'Cliente não encontrado.' );
        }

        $restricoes = [ 'idCliente' => $idCliente ];
        $enderecos = $this->getService()->obterComRestricoes( $restricoes );
        Response::listarDados( $enderecos );
    }
}