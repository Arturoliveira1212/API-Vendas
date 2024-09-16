<?php

// namespace tests\unit;

use app\builders\CategoriaBuilder;
use app\dao\DAOEmBDR;
use app\exceptions\ServiceException;
use app\models\Categoria;
use app\services\CategoriaService;
use core\QueryParams;
use PHPUnit\Framework\TestCase;

class CategoriaServiceTest extends TestCase {
    private $service;
    private $dao;

    protected function setUp() :void {
        $this->dao = $this->createMock( DAOEmBDR::class );
        $this->service = new CategoriaService( $this->dao );
    }

    public function testLancaExceptionAoSalvarCategoriaComNomeNaoEnviado(){
        $categoria = CategoriaBuilder::novo()->comId( 1 )->comNome( '' )->comDescricao( 'Descrição válida.' )->build();

        try {
            $this->service->salvar( $categoria );
            $this->fail('Esperava-se que uma ServiceException fosse lançada.');
        } catch( ServiceException $e ){
            $erro = json_decode( $e->getMessage(), true );

            $this->assertNotEmpty( $erro );
            $this->assertArrayHasKey( 'nome', $erro );
            $this->assertEquals( $erro['nome'], 'Preencha o campo nome.' );
        }
    }

    public function testLancaExceptionAoSalvarCategoriaComDescricaoNaoEnviada(){
        $categoria = CategoriaBuilder::novo()->comId( 1 )->comNome( 'Nome válido.' )->comDescricao( '' )->build();

        try {
            $this->service->salvar( $categoria );
            $this->fail('Esperava-se que uma ServiceException fosse lançada.');
        } catch( ServiceException $e ){
            $erro = json_decode( $e->getMessage(), true );

            $this->assertNotEmpty( $erro );
            $this->assertArrayHasKey( 'descricao', $erro );
            $this->assertEquals( $erro['descricao'], 'Preencha o campo descricao.' );
        }
    }

    public function testLancaExceptionAoSalvarCategoriaComNomeComTamanhoMaiorQueOPermitido(){
        $categoria = CategoriaBuilder::novo()->comId( 1 )->comNome( str_repeat( 'a', Categoria::TAMANHO_MAXIMO_NOME + 1 ) )->comDescricao( 'Descrição válida.' )->build();

        try {
            $this->service->salvar( $categoria );
            $this->fail('Esperava-se que uma ServiceException fosse lançada.');
        } catch( ServiceException $e ){
            $erro = json_decode( $e->getMessage(), true );

            $this->assertNotEmpty( $erro );
            $this->assertArrayHasKey( 'nome', $erro );
            $this->assertEquals( $erro['nome'], "O campo nome deve ter entre " . Categoria::TAMANHO_MINIMO_NOME . " e " . Categoria::TAMANHO_MAXIMO_NOME . " caracteres." );
        }
    }

    public function testSalvaComSucessoCategoriaComNomeComTamanhoIgualAoPermitido(){
        $categoria = CategoriaBuilder::novo()->comId( 1 )->comNome( str_repeat( 'a', Categoria::TAMANHO_MAXIMO_NOME ) )->comDescricao( 'Descrição válida.' )->build();

        $idCadastrado = 1;
        $this->dao->method('salvar')->willReturn( $idCadastrado );

        $resultado = $this->service->salvar( $categoria );

        $this->assertEquals( $resultado, $idCadastrado );
    }

    public function testLancaExceptionAoSalvarCategoriaComDescricaoComTamanhoMaiorQueOPermitido(){
        $categoria = CategoriaBuilder::novo()->comId( 1 )->comNome( 'Nome válido.' )->comDescricao( str_repeat( 'a', Categoria::TAMANHO_MAXIMO_DESCRICAO + 1 ) )->build();

        try {
            $this->service->salvar( $categoria );
            $this->fail('Esperava-se que uma ServiceException fosse lançada.');
        } catch( ServiceException $e ){
            $erro = json_decode( $e->getMessage(), true );

            $this->assertNotEmpty( $erro );
            $this->assertArrayHasKey( 'descricao', $erro );
            $this->assertEquals( $erro['descricao'], "O campo descricao deve ter entre " . Categoria::TAMANHO_MINIMO_DESCRICAO . " e " . Categoria::TAMANHO_MAXIMO_DESCRICAO . " caracteres." );
        }
    }

    public function testSalvaComSucessoCategoriaComDescricaoComTamanhoIgualAoPermitido(){
        $categoria = CategoriaBuilder::novo()->comId( 1 )->comNome( 'Nome válido.' )->comDescricao( str_repeat( 'a', Categoria::TAMANHO_MAXIMO_DESCRICAO ) )->build();

        $idCadastrado = 1;
        $this->dao->method('salvar')->willReturn( $idCadastrado );

        $resultado = $this->service->salvar( $categoria );

        $this->assertEquals( $resultado, $idCadastrado );
    }

    public function testObtemComSucessoCategoria(){
        $categoria = CategoriaBuilder::novo()->comId( 1 )->comNome( 'Nome válido.' )->comDescricao( 'Descrição válida.' )->build();

        $this->dao->method('obterComId')->willReturn( $categoria );

        $categoriaObtida = $this->service->obterComId( $categoria->getId() );

        $this->assertInstanceOf( Categoria::class, $categoriaObtida );
        $this->assertEquals( $categoria->getId(), $categoriaObtida->getId() );
    }

    public function testObtemComSucessoCategoriaNula(){
        $this->dao->method('obterComId')->willReturn( null );

        $categoriaObtida = $this->service->obterComId( 1 );

        $this->assertNull( $categoriaObtida );
    }

    public function testObtemComSucessoCategoriasComRestricao(){
        $queryParams = new QueryParams( ['campo' => 'valor'] );
        $this->dao->method('obterComRestricoes')->with($queryParams)->willReturn([]);

        $resultado = $this->service->obterComRestricoes($queryParams);

        $this->assertIsArray( $resultado );
    }

    public function testDesativaComSucessoCategoriaComId(){
        $id = 1;
        $this->dao->method('desativarComId')->with($id)->willReturn( 1 );

        $resultado = $this->service->desativarComId($id);

        $this->assertIsInt( $resultado );
        $this->assertEquals( $resultado, 1 );
    }

    public function testDesativaComSucessoCategoriaInexistente(){
        $id = 1;
        $this->dao->method('desativarComId')->with($id)->willReturn( 0 );

        $resultado = $this->service->desativarComId($id);

        $this->assertIsInt( $resultado );
        $this->assertEquals( $resultado, 0 );
    }
}