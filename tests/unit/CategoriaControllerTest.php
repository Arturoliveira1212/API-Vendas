<?php

use PHPUnit\Framework\TestCase;
use app\builders\CategoriaBuilder;
use app\builders\RequestBuilder;
use app\controllers\CategoriaController;
use app\exceptions\CampoNaoEnviadoException;
use app\exceptions\NaoEncontradoException;
use app\services\CategoriaService;
use core\Response;
use http\HttpStatusCode;

class CategoriaControllerTest extends TestCase {
    private $controller;
    private $service;

    protected function setUp() :void {
        $this->service = $this->createMock( CategoriaService::class );
        $this->controller = new CategoriaController( $this->service );
    }

    public function testLancaExceptionAoEnviarCorpoRequisicaoVazioParaCadastro(){
        $this->expectException( CampoNaoEnviadoException::class );
        $this->expectExceptionMessage( 'Corpo requisição inválido.' );

        $request = RequestBuilder::novo()->comCorpoRequisicao( [] )->build();
        $this->controller->cadastrar( $request );
    }

    public function testLancaExceptionAoNaoEnviarNomeDaCategoriaParaCadastro(){
        $this->expectException( CampoNaoEnviadoException::class );
        $this->expectExceptionMessage( 'nome não enviado.' );

        $corpoRequisicao = [
            'descricao' => 'Descrição válida.'
        ];
        $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->build();
        $this->controller->cadastrar( $request );
    }

    public function testLancaExceptionAoNaoEnviarDescricaoDaCategoriaParaCadastro(){
        $this->expectException( CampoNaoEnviadoException::class );
        $this->expectExceptionMessage( 'descricao não enviado.' );

        $corpoRequisicao = [
            'nome' => 'Nome válido.'
        ];
        $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->build();
        $this->controller->cadastrar( $request );
    }

    public function testCadastraComSucessoCategoria(){
        $idCadastrado = 1;
        $this->service->method('salvar')->willReturn( $idCadastrado );

        $corpoRequisicao = [
            'nome' => 'Nome válido',
            'descricao' => 'Descrição válida.'
        ];
        $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->build();
        $response = $this->controller->cadastrar( $request );

        $this->assertNotEmpty( $response );
        $this->assertInstanceOf( Response::class, $response );

        $this->assertEquals( $response->getStatusCode(), HttpStatusCode::CREATED );
        $this->assertEquals( $response->getData(), [ 'id' => $idCadastrado ] );
        $this->assertEquals( $response->getMessage(), 'Categoria cadastrada com sucesso.' );
    }

    public function testLancaExceptionAoEnviarCorpoRequisicaoVazioParaAtualizacao(){
        $this->service->method('obterComId')->willReturn( CategoriaBuilder::novo()->build() );

        $this->expectException( CampoNaoEnviadoException::class );
        $this->expectExceptionMessage( 'Corpo requisição inválido.' );

        $corpoRequisicao = [];
        $parametrosRota = [
            'categorias' => 1
        ];
        $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->comParametrosRota( $parametrosRota )->build();
        $this->controller->atualizar( $request );
    }

    public function testLancaExceptionAoTentarAtualizarCategoriaInexistente(){
        $this->service->method('obterComId')->willReturn( null );

        $this->expectException( NaoEncontradoException::class );
        $this->expectExceptionMessage( 'Categoria não encontrada.' );

        $parametrosRota = [
            'categorias' => 1
        ];
        $request = RequestBuilder::novo()->comParametrosRota( $parametrosRota )->build();
        $this->controller->atualizar( $request );
    }

    public function testLancaExceptionAoNaoEnviarNomeDaCategoriaParaAtualizacao(){
        $this->service->method('obterComId')->willReturn( CategoriaBuilder::novo()->build() );

        $this->expectException( CampoNaoEnviadoException::class );
        $this->expectExceptionMessage( 'nome não enviado.' );

        $corpoRequisicao = [
            'descricao' => 'Descrição válida.'
        ];
        $parametrosRota = [
            'categorias' => 1
        ];
        $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->comParametrosRota( $parametrosRota )->build();
        $this->controller->atualizar( $request );
    }

    public function testLancaExceptionAoNaoEnviarDescricaoDaCategoriaParaAtualizacao(){
        $this->service->method('obterComId')->willReturn( CategoriaBuilder::novo()->build() );

        $this->expectException( CampoNaoEnviadoException::class );
        $this->expectExceptionMessage( 'descricao não enviado.' );

        $corpoRequisicao = [
            'nome' => 'Nome válido.'
        ];
        $parametrosRota = [
            'categorias' => 1
        ];
        $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->comParametrosRota( $parametrosRota )->build();
        $this->controller->atualizar( $request );
    }

    public function testAtualizaComSucessoCategoria(){
        $this->service->method('obterComId')->willReturn( CategoriaBuilder::novo()->build() );

        $corpoRequisicao = [
            'nome' => 'Nome válido',
            'descricao' => 'Descrição válida.'
        ];
        $parametrosRota = [
            'categorias' => 1
        ];
        $request = RequestBuilder::novo()->comCorpoRequisicao( $corpoRequisicao )->comParametrosRota( $parametrosRota )->build();
        $response = $this->controller->atualizar( $request );

        $this->assertNotEmpty( $response );
        $this->assertInstanceOf( Response::class, $response );

        $this->assertEquals( $response->getStatusCode(), HttpStatusCode::OK );
        $this->assertEquals( $response->getMessage(), 'Categoria atualizada com sucesso.' );
        $this->assertEquals( $response->getData(), [] );
    }

    public function testLancaExceptionAoTentarExcluirCategoriaInexistente(){
        $this->service->method('obterComId')->willReturn( null );

        $this->expectException( NaoEncontradoException::class );
        $this->expectExceptionMessage( 'Categoria não encontrada' );

        $parametrosRota = [
            'categorias' => 1
        ];
        $request = RequestBuilder::novo()->comParametrosRota( $parametrosRota )->build();
        $this->controller->excluir( $request );
    }

    public function testExcluiComSucessoCategoria(){
        $this->service->method('obterComId')->willReturn( CategoriaBuilder::novo()->build() );

        $parametrosRota = [
            'categorias' => 1
        ];
        $request = RequestBuilder::novo()->comParametrosRota( $parametrosRota )->build();
        $response = $this->controller->excluir( $request );

        $this->assertNotEmpty( $response );
        $this->assertInstanceOf( Response::class, $response );

        $this->assertEquals( $response->getStatusCode(), HttpStatusCode::NO_CONTENT );
        $this->assertEquals( $response->getMessage(), '' );
        $this->assertEquals( $response->getData(), [] );
    }

    public function testObtemTodasAsCategoriasComSucesso(){
        $categorias = [
            CategoriaBuilder::novo()->comId( 1 )->comNome( 'Categoria 1' )->build(),
            CategoriaBuilder::novo()->comId( 2 )->comNome( 'Categoria 2' )->build()
        ];
        $this->service->method('obterComRestricoes')->willReturn( $categorias );

        $request = RequestBuilder::novo()->build();
        $response = $this->controller->listarTodos( $request );

        $this->assertNotEmpty( $response );
        $this->assertInstanceOf( Response::class, $response );

        $this->assertEquals( $response->getStatusCode(), HttpStatusCode::OK );
        $this->assertEquals( $response->getMessage(), '' );
        $this->assertEquals( $response->getData(), $categorias );
    }

    public function testLancaExceptionAoTentarObterComIdCategoriaInexistente(){
        $this->service->method('obterComId')->willReturn( null );

        $this->expectException( NaoEncontradoException::class );
        $this->expectExceptionMessage( 'Categoria não encontrada' );

        $parametrosRota = [
            'categorias' => 1
        ];
        $request = RequestBuilder::novo()->comParametrosRota( $parametrosRota )->build();
        $this->controller->listarComId( $request );
    }

    public function testObtemComSucessoCategoriaComId(){
        $categoria = CategoriaBuilder::novo()->build();
        $this->service->method('obterComId')->willReturn( $categoria );

        $parametrosRota = [
            'categorias' => 1
        ];
        $request = RequestBuilder::novo()->comParametrosRota( $parametrosRota )->build();
        $response = $this->controller->listarComId( $request );

        $this->assertNotEmpty( $response );
        $this->assertInstanceOf( Response::class, $response );

        $this->assertEquals( $response->getStatusCode(), HttpStatusCode::OK );
        $this->assertEquals( $response->getMessage(), '' );
        $this->assertEquals( $response->getData(), [$categoria] );
    }
}
