<?php

use app\builders\ClienteBuilder;
use app\builders\RequestBuilder;
use app\controllers\ClienteController;
use app\exceptions\CampoNaoEnviadoException;
use app\exceptions\NaoEncontradoException;
use app\services\ClienteService;
use core\Response;
use http\HttpStatusCode;

describe( 'ClienteController', function(){
    beforeEach( function(){
        $this->service = Mockery::mock( ClienteService::class );
        $this->controller = new ClienteController( $this->service );
    } );

    describe( 'Cadastrar', function(){
        it( 'Lança exceção ao enviar corpo requisição vazio', function() {
            $request = RequestBuilder::novo()->comCorpoRequisicao([])->build();

            expect( function() use ( $request ){
                $this->controller->cadastrar($request);
            })->toThrow( new CampoNaoEnviadoException('Corpo requisição inválido.') );
        });

        it( 'Lança exceção ao não enviar nome do cliente', function(){
            $corpoRequisicao = [
                'cpf' => 'Cpf válido',
                'dataNascimento' => 'Data válida'
            ];
            $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->build();

            expect( function() use ( $request ) {
                $this->controller->cadastrar( $request );
            } )->toThrow( new CampoNaoEnviadoException( 'nome não enviado.' ) );
        } );

        it( 'Lança exceção ao não enviar cpf do cliente', function(){
            $corpoRequisicao = [
                'nome' => 'Nome válido',
                'dataNascimento' => 'Data válida'
            ];
            $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->build();

            expect( function() use ( $request ) {
                $this->controller->cadastrar( $request );
            } )->toThrow( new CampoNaoEnviadoException( 'cpf não enviado.' ) );
        } );

        it( 'Lança exceção ao não enviar dataNascimento do cliente', function(){
            $corpoRequisicao = [
                'nome' => 'Nome válido',
                'cpf' => 'Cpf válido'
            ];
            $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->build();

            expect( function() use ( $request ) {
                $this->controller->cadastrar( $request );
            } )->toThrow( new CampoNaoEnviadoException( 'dataNascimento não enviado.' ) );
        } );

        it( 'Cadastra cliente com sucesso', function(){
            $idCadastrado = 1;
            $this->service->shouldReceive('salvar')->andReturn($idCadastrado);

            $corpoRequisicao = [
                'nome' => 'Nome válido',
                'cpf' => '181.365.217-19',
                'dataNascimento' => '28/11/2003'
            ];
            $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->build();
            $response = $this->controller->cadastrar( $request );

            expect($response)->toBeAnInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(HttpStatusCode::CREATED);
            expect($response->getData())->toBe(['id' => $idCadastrado]);
            expect($response->getMessage())->toBe('Cliente cadastrado com sucesso.');
        } );
    } );

    describe( 'Atualizar', function(){
        it('Lança exceção ao enviar corpo requisição vazio', function() {
            $this->service->shouldReceive('obterComId')->andReturn(ClienteBuilder::novo()->build());

            $corpoRequisicao = [];
            $parametrosRota = ['clientes' => 1];
            $request = RequestBuilder::novo()->comCorpoRequisicao($corpoRequisicao)->comParametrosRota($parametrosRota)->build();

            expect(function() use ( $request ) {
                $this->controller->atualizar($request);
            })->toThrow(new CampoNaoEnviadoException('Corpo requisição inválido.'));
        });

        it('Lança exceção ao atualizar cliente inexistente', function() {
            $this->service->shouldReceive('obterComId')->andReturn(null);

            $parametrosRota = ['clientes' => 1];
            $request = RequestBuilder::novo()->comParametrosRota($parametrosRota)->build();

            expect(function() use ( $request ) {
                $this->controller->atualizar($request);
            })->toThrow(new NaoEncontradoException('Cliente não encontrado.'));
        });

        it( 'Lança exceção ao não enviar nome do cliente', function(){
            $this->service->shouldReceive('obterComId')->andReturn(ClienteBuilder::novo()->build());

            $corpoRequisicao = [
                'cpf' => 'Cpf válido',
                'dataNascimento' => 'Data válida'
            ];
            $parametrosRota = ['clientes' => 1];
            $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->comParametrosRota($parametrosRota)->build();

            expect( function() use ( $request ) {
                $this->controller->atualizar( $request );
            } )->toThrow( new CampoNaoEnviadoException( 'nome não enviado.' ) );
        } );

        it( 'Lança exceção ao não enviar cpf do cliente', function(){
            $this->service->shouldReceive('obterComId')->andReturn(ClienteBuilder::novo()->build());

            $corpoRequisicao = [
                'nome' => 'Nome válido',
                'dataNascimento' => 'Data válida'
            ];
            $parametrosRota = ['clientes' => 1];
            $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->comParametrosRota($parametrosRota)->build();

            expect( function() use ( $request ) {
                $this->controller->atualizar( $request );
            } )->toThrow( new CampoNaoEnviadoException( 'cpf não enviado.' ) );
        } );

        it( 'Lança exceção ao não enviar dataNascimento do cliente', function(){
            $this->service->shouldReceive('obterComId')->andReturn(ClienteBuilder::novo()->build());

            $corpoRequisicao = [
                'nome' => 'Nome válido',
                'cpf' => 'Cpf válido'
            ];
            $parametrosRota = ['clientes' => 1];
            $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->comParametrosRota($parametrosRota)->build();

            expect( function() use ( $request ) {
                $this->controller->atualizar( $request );
            } )->toThrow( new CampoNaoEnviadoException( 'dataNascimento não enviado.' ) );
        } );
    } );

    describe( 'Excluir', function(){
        it( 'Lança exceção ao tentar excluir categoria inexistente', function(){
            $this->service->shouldReceive('obterComId')->andReturn(null);

            $parametrosRota = [
                'clientes' => 1
            ];
            $request = RequestBuilder::novo()->comParametrosRota( $parametrosRota )->build();

            expect( function() use ( $request ){
                $this->controller->excluir( $request );
            } )->toThrow( new NaoEncontradoException( 'Cliente não encontrado.' ) );
        } );

        it( 'Exclui cliente com sucesso', function(){
            $this->service->shouldReceive('obterComId')->andReturn( ClienteBuilder::novo()->build() );
            $this->service->shouldReceive('desativarComId')->andReturn( 1 );

            $parametrosRota = [
                'clientes' => 1
            ];
            $request = RequestBuilder::novo()->comParametrosRota( $parametrosRota )->build();

            $response = $this->controller->excluir( $request );

            expect( $response )->toBeAnInstanceOf( Response::class );
            expect( $response->getStatusCode() )->toBe( HttpStatusCode::NO_CONTENT );
            expect( $response->getMessage() )->toBe( '' );
            expect( $response->getData() )->toBe( [] );
        } );
    } );

    describe( 'Listar Todos', function(){
        it( 'Lista todos os clientes com sucesso', function(){
            $clientes = [
                ClienteBuilder::novo()->comId(1)->comNome('Categoria 1')->build(),
                ClienteBuilder::novo()->comId(2)->comNome('Categoria 2')->build()
            ];
            $this->service->shouldReceive('obterComRestricoes')->andReturn($clientes);

            $request = RequestBuilder::novo()->build();
            $response = $this->controller->listarTodos($request);

            expect($response)->toBeAnInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(HttpStatusCode::OK);
            expect($response->getData())->toBe($clientes);
        } );
    } );

    describe( 'Listar com Id', function(){
        it('Lança exceção ao tentar obter cliente inexistente', function() {
            $this->service->shouldReceive('obterComId')->andReturn(null);

            expect(function() {
                $parametrosRota = ['clientes' => 1];
                $request = RequestBuilder::novo()->comParametrosRota($parametrosRota)->build();
                $this->controller->listarComId($request);
            })->toThrow(new NaoEncontradoException('Cliente não encontrado.'));
        });

        it('Lista cliente com sucesso', function() {
            $cliente = ClienteBuilder::novo()->build();
            $this->service->shouldReceive('obterComId')->andReturn($cliente);

            $parametrosRota = ['clientes' => 1];
            $request = RequestBuilder::novo()->comParametrosRota($parametrosRota)->build();
            $response = $this->controller->listarComId($request);

            expect($response)->toBeAnInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(HttpStatusCode::OK);
            expect($response->getData())->toBe([$cliente]);
        });
    } );
} );