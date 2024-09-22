<?php

use app\builders\CategoriaBuilder;
use app\builders\RequestBuilder;
use app\controllers\CategoriaController;
use app\exceptions\CampoNaoEnviadoException;
use app\exceptions\NaoEncontradoException;
use app\services\CategoriaService;
use core\Response;
use http\HttpStatusCode;

describe('CategoriaController', function() {
    beforeEach(function() {
        $this->service = Mockery::mock(CategoriaService::class);
        $this->controller = new CategoriaController($this->service);
    });

    describe( 'Cadastrar', function(){
        it('lança exceção ao enviar corpo da requisição vazio para cadastro', function() {
            expect( function(){
                $request = RequestBuilder::novo()->comCorpoRequisicao([])->build();
                $this->controller->cadastrar($request);
            })->toThrow( new CampoNaoEnviadoException('Corpo requisição inválido.') );
        });

        it('lança exceção ao não enviar nome da categoria para cadastro', function() {
            expect(function() {
                $corpoRequisicao = ['descricao' => 'Descrição válida.'];
                $request = RequestBuilder::novo()->comCorpoRequisicao($corpoRequisicao)->build();
                $this->controller->cadastrar($request);
            })->toThrow(new CampoNaoEnviadoException('nome não enviado.'));
        });

        it('lança exceção ao não enviar descrição da categoria para cadastro', function() {
            expect(function() {
                $corpoRequisicao = ['nome' => 'Nome válido.'];
                $request = RequestBuilder::novo()->comCorpoRequisicao($corpoRequisicao)->build();
                $this->controller->cadastrar($request);
            })->toThrow(new CampoNaoEnviadoException('descricao não enviado.'));
        });

        it('cadastra com sucesso categoria', function() {
            $idCadastrado = 1;
            $this->service->shouldReceive('salvar')->andReturn($idCadastrado);

            $corpoRequisicao = ['nome' => 'Nome válido', 'descricao' => 'Descrição válida.'];
            $request = RequestBuilder::novo()->comCorpoRequisicao($corpoRequisicao)->build();
            $response = $this->controller->cadastrar($request);

            expect($response)->toBeAnInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(HttpStatusCode::CREATED);
            expect($response->getData())->toBe(['id' => $idCadastrado]);
            expect($response->getMessage())->toBe('Categoria cadastrada com sucesso.');
        });
    } );

    describe( 'Atualizar', function(){
        it('lança exceção ao enviar corpo da requisição vazio para atualização', function() {
            $this->service->shouldReceive('obterComId')->andReturn(CategoriaBuilder::novo()->build());

            expect(function() {
                $corpoRequisicao = [];
                $parametrosRota = ['categorias' => 1];
                $request = RequestBuilder::novo()->comCorpoRequisicao($corpoRequisicao)->comParametrosRota($parametrosRota)->build();
                $this->controller->atualizar($request);
            })->toThrow(new CampoNaoEnviadoException('Corpo requisição inválido.'));
        });

        it('lança exceção ao tentar atualizar categoria inexistente', function() {
            $this->service->shouldReceive('obterComId')->andReturn(null);

            expect(function() {
                $parametrosRota = ['categorias' => 1];
                $request = RequestBuilder::novo()->comParametrosRota($parametrosRota)->build();
                $this->controller->atualizar($request);
            })->toThrow(new NaoEncontradoException('Categoria não encontrada.'));
        });

        it('lança exceção ao não enviar nome da categoria para atualização', function() {
            $this->service->shouldReceive('obterComId')->andReturn(CategoriaBuilder::novo()->build());

            expect(function() {
                $corpoRequisicao = ['descricao' => 'Descrição válida.'];
                $parametrosRota = ['categorias' => 1];
                $request = RequestBuilder::novo()->comCorpoRequisicao($corpoRequisicao)->comParametrosRota($parametrosRota)->build();
                $this->controller->atualizar($request);
            })->toThrow(new CampoNaoEnviadoException('nome não enviado.'));
        });

        it('lança exceção ao não enviar descrição da categoria para atualização', function() {
            $this->service->shouldReceive('obterComId')->andReturn(CategoriaBuilder::novo()->build());

            expect(function() {
                $corpoRequisicao = ['nome' => 'Nome válido.'];
                $parametrosRota = ['categorias' => 1];
                $request = RequestBuilder::novo()->comCorpoRequisicao($corpoRequisicao)->comParametrosRota($parametrosRota)->build();
                $this->controller->atualizar($request);
            })->toThrow(new CampoNaoEnviadoException('descricao não enviado.'));
        });

        it('atualiza com sucesso categoria', function() {
            $this->service->shouldReceive('salvar')->andReturn(1);
            $this->service->shouldReceive('obterComId')->andReturn(CategoriaBuilder::novo()->build());

            $corpoRequisicao = ['nome' => 'Nome válido', 'descricao' => 'Descrição válida.'];
            $parametrosRota = ['categorias' => 1];
            $request = RequestBuilder::novo()->comCorpoRequisicao($corpoRequisicao)->comParametrosRota($parametrosRota)->build();
            $response = $this->controller->atualizar($request);

            expect($response)->toBeAnInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(HttpStatusCode::OK);
            expect($response->getMessage())->toBe('Categoria atualizada com sucesso.');
            expect($response->getData())->toBe([]);
        });
    } );

    describe( 'Excluir', function(){
        it('lança exceção ao tentar excluir categoria inexistente', function() {
            $this->service->shouldReceive('obterComId')->andReturn(null);

            expect(function() {
                $parametrosRota = ['categorias' => 1];
                $request = RequestBuilder::novo()->comParametrosRota($parametrosRota)->build();
                $this->controller->excluir($request);
            })->toThrow(new NaoEncontradoException('Categoria não encontrada.'));
        });

        it('exclui com sucesso categoria', function() {
            $this->service->shouldReceive('obterComId')->andReturn(CategoriaBuilder::novo()->build());
            $this->service->shouldReceive('desativarComId')->with(1)->andReturn(1);

            $parametrosRota = ['categorias' => 1];
            $request = RequestBuilder::novo()->comParametrosRota($parametrosRota)->build();
            $response = $this->controller->excluir($request);

            expect($response)->toBeAnInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(HttpStatusCode::NO_CONTENT);
            expect($response->getMessage())->toBe('');
            expect($response->getData())->toBe([]);
        });
    } );

    describe( 'Listar Todos', function(){
        it('obtém todas as categorias com sucesso', function() {
            $categorias = [
                CategoriaBuilder::novo()->comId(1)->comNome('Categoria 1')->build(),
                CategoriaBuilder::novo()->comId(2)->comNome('Categoria 2')->build()
            ];
            $this->service->shouldReceive('obterComRestricoes')->andReturn($categorias);

            $request = RequestBuilder::novo()->build();
            $response = $this->controller->listarTodos($request);

            expect($response)->toBeAnInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(HttpStatusCode::OK);
            expect($response->getData())->toBe($categorias);
        });
    } );

    describe( 'Listar com Id', function(){
        it('lança exceção ao tentar obter categoria inexistente por ID', function() {
            $this->service->shouldReceive('obterComId')->andReturn(null);

            expect(function() {
                $parametrosRota = ['categorias' => 1];
                $request = RequestBuilder::novo()->comParametrosRota($parametrosRota)->build();
                $this->controller->listarComId($request);
            })->toThrow(new NaoEncontradoException('Categoria não encontrada.'));
        });

        it('obtém com sucesso categoria por ID', function() {
            $categoria = CategoriaBuilder::novo()->build();
            $this->service->shouldReceive('obterComId')->andReturn($categoria);

            $parametrosRota = ['categorias' => 1];
            $request = RequestBuilder::novo()->comParametrosRota($parametrosRota)->build();
            $response = $this->controller->listarComId($request);

            expect($response)->toBeAnInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(HttpStatusCode::OK);
            expect($response->getData())->toBe([$categoria]);
        });
    } );
});
