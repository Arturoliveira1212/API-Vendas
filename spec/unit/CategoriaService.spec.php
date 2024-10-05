<?php

use app\builders\CategoriaBuilder;
use app\dao\DAOEmBDR;
use app\exceptions\ServiceException;
use app\models\Categoria;
use app\services\CategoriaService;
use core\QueryParams;

describe('CategoriaService', function() {
    beforeEach(function() {
        $this->dao = Mockery::mock(DAOEmBDR::class);
        $this->service = new CategoriaService($this->dao);
    });

    describe( 'Salvar', function(){
        it('Lança exceção ao não enviar nome válido para categoria', function() {
            $categoria = CategoriaBuilder::novo()->comId(1)->comNome('')->comDescricao('Descrição válida.')->build();

            try {
                $this->service->salvar($categoria);
            } catch (ServiceException $e) {
                $erro = json_decode($e->getMessage(), true);
                expect($erro)->not->toBeEmpty();
                expect($erro)->toContainKey('nome');
                expect($erro['nome'])->toEqual('Preencha o campo nome.');
            }
        });

        it('Lança exceção ao não enviar descrição válida para categoria', function() {
            $categoria = CategoriaBuilder::novo()->comId(1)->comNome('Nome válido.')->comDescricao('')->build();

            try {
                $this->service->salvar($categoria);
            } catch (ServiceException $e) {
                $erro = json_decode($e->getMessage(), true);
                expect($erro)->not->toBeEmpty();
                expect($erro)->toContainKey('descricao');
                expect($erro['descricao'])->toEqual('Preencha o campo descricao.');
            }
        });

        it('Lança exceção ao enviar nome com tamanho maior que o permitido', function() {
            $categoria = CategoriaBuilder::novo()->comId(1)->comNome(str_repeat('a', Categoria::TAMANHO_MAXIMO_NOME + 1))->comDescricao('Descrição válida.')->build();

            try {
                $this->service->salvar($categoria);
            } catch (ServiceException $e) {
                $erro = json_decode($e->getMessage(), true);
                expect($erro)->not->toBeEmpty();
                expect($erro)->toContainKey('nome');
                expect($erro['nome'])->toEqual("O campo nome deve ter entre " . Categoria::TAMANHO_MINIMO_NOME . " e " . Categoria::TAMANHO_MAXIMO_NOME . " caracteres.");
            }
        });

        it('Salva categoria com sucesso ao enviar nome com tamanho igual ao permitido', function() {
            $categoria = CategoriaBuilder::novo()->comId(1)->comNome(str_repeat('a', Categoria::TAMANHO_MAXIMO_NOME))->comDescricao('Descrição válida.')->build();

            $idCadastrado = 1;
            $this->dao->shouldReceive('salvar')->andReturn($idCadastrado);

            $resultado = $this->service->salvar($categoria);
            expect($resultado)->toEqual($idCadastrado);
        });

        it('Lança exceção ao enviar descrição com tamanho maior que o permitido', function() {
            $categoria = CategoriaBuilder::novo()->comId(1)->comNome('Nome válido.')->comDescricao(str_repeat('a', Categoria::TAMANHO_MAXIMO_DESCRICAO + 1))->build();

            try {
                $this->service->salvar($categoria);
            } catch (ServiceException $e) {
                $erro = json_decode($e->getMessage(), true);
                expect($erro)->not->toBeEmpty();
                expect($erro)->toContainKey('descricao');
                expect($erro['descricao'])->toEqual("O campo descricao deve ter entre " . Categoria::TAMANHO_MINIMO_DESCRICAO . " e " . Categoria::TAMANHO_MAXIMO_DESCRICAO . " caracteres.");
            }
        });

        it('Salva categoria com sucesso ao enviar descrição com tamanho igual ao permitido', function() {
            $categoria = CategoriaBuilder::novo()->comId(1)->comNome('Nome válido.')->comDescricao(str_repeat('a', Categoria::TAMANHO_MAXIMO_DESCRICAO))->build();

            $idCadastrado = 1;
            $this->dao->shouldReceive('salvar')->andReturn($idCadastrado);

            $resultado = $this->service->salvar($categoria);
            expect($resultado)->toEqual($idCadastrado);
        });
    } );

    describe( 'ObterComId', function(){
        it('Obtém categoria com sucesso', function() {
            $categoria = CategoriaBuilder::novo()->comId(1)->comNome('Nome válido.')->comDescricao('Descrição válida.')->build();

            $this->dao->shouldReceive('obterComId')->andReturn($categoria);

            $categoriaObtida = $this->service->obterComId($categoria->getId());
            expect($categoriaObtida)->toBeAnInstanceOf(Categoria::class);
            expect($categoriaObtida->getId())->toEqual($categoria->getId());
        });

        it('Retorna null se a categoria não existir', function() {
            $this->dao->shouldReceive('obterComId')->andReturn(null);

            $categoriaObtida = $this->service->obterComId(1);
            expect($categoriaObtida)->toBeNull();
        });
    } );

    describe( 'ObterComRestricoes', function(){
        it('Obtém com sucesso categorias com restrição', function() {
            $queryParams = new QueryParams(['campo' => 'valor']);
            $this->dao->shouldReceive('obterComRestricoes')->with($queryParams)->andReturn([]);

            $resultado = $this->service->obterComRestricoes($queryParams);
            expect($resultado)->toEqual([]);
        });
    } );

    describe( 'DesativarComId', function(){
        it('Desativa categoria com sucesso categoria', function() {
            $id = 1;
            $this->dao->shouldReceive('desativarComId')->with($id)->andReturn(1);

            $resultado = $this->service->desativarComId($id);
            expect($resultado)->toBeA('integer');
            expect($resultado)->toEqual(1);
        });

        it('Retorna zero ao desativar categoria inexistente', function() {
            $id = 1;
            $this->dao->shouldReceive('desativarComId')->with($id)->andReturn(0);

            $resultado = $this->service->desativarComId($id);
            expect($resultado)->toBeA('integer');
            expect($resultado)->toEqual(0);
        });
    } );

});
