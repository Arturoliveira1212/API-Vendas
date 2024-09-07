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

    protected function criar( array $corpoRequisicao ){
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

            $corpoRequisicao = $this->getRequest()->corpoRequisicao();
            $endereco = $this->criar( $corpoRequisicao );
            $endereco->setCliente( $cliente );

            $id = $this->getService()->salvar( $endereco, $erro );
            $this->getResponse()->recursoCriado( $id, 'Endereco cadastrado com sucesso.' );
        } catch( CampoNaoEnviadoException $e ){
            $this->getResponse()->campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            $this->getResponse()->erroAoSalvar( $e );
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

            $corpoRequisicao = $this->getRequest()->corpoRequisicao();
            $endereco = $this->criar( $corpoRequisicao );
            $endereco->setId( $id );
            $endereco->setCliente( $cliente );

            $id = $this->getService()->salvar( $endereco, $erro );
            $this->getResponse()->recursoAlterado( 'Endereco atualizado com sucesso.' );
        } catch( NaoEncontradoException $e ){
            throw $e;
        } catch( CampoNaoEnviadoException $e ){
            $this->getResponse()->campoNaoEnviado( $e );
        } catch( ServiceException $e ){
            $this->getResponse()->erroAoSalvar( $e );
        }
    }

    public function excluir( array $parametros ){
        $id = intval( $parametros['enderecos'] );
        $endereco = $this->getService()->obterComId( $id );
        if( ! $endereco instanceof Endereco ){
            throw new NaoEncontradoException( 'Endereco não encontrado.' );
        }

        $this->getService()->desativarComId( $id );
        $this->getResponse()->recursoRemovido();
    }

    public function listarTodos(){
        $enderecos = $this->getService()->obterComRestricoes();
        $this->getResponse()->listarDados( $enderecos );
    }

    public function listarComId( array $parametros ){
        $id = intval( $parametros['enderecos'] );
        $endereco = $this->getService()->obterComId( $id );
        if( ! $endereco instanceof Endereco ){
            throw new NaoEncontradoException( 'Endereco não encontrado.' );
        }

        $this->getResponse()->listarDados( [ $endereco ] );
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
        $this->getResponse()->listarDados( $enderecos );
    }
}