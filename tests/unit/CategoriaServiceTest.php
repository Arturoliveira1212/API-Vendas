<?php

// namespace tests\unit;

use app\dao\CategoriaDAO;
use app\dao\DAOEmBDR;
use app\exceptions\ServiceException;
use app\models\Categoria;
use app\services\CategoriaService;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class CategoriaServiceTest extends TestCase {
    private $service;
    private $dao;

    protected function setUp() :void {
        $this->dao = $this->createMock( DAOEmBDR::class );
        $this->service = new CategoriaService( $this->dao );
    }

    public function testLancaExceptionSalvarCategoriaComNomeNaoEnviado(){
        $categoria = new Categoria();
        $categoria->setId( 1 );
        $categoria->setNome( '' );
        $categoria->setDescricao( 'Descrição válida' );

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

    public function testSalvarCategoriaComDescricaoNaoEnviada(){
        $categoria = new Categoria();
        $categoria->setId( 1 );
        $categoria->setNome( 'Nome válido' );
        $categoria->setDescricao( '' );

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

    public function testSalvarCategoriaComNomeComTamanhoMaiorQueOPermitido(){
        $categoria = new Categoria();
        $categoria->setId( 1 );
        $categoria->setNome( str_repeat( 'a', Categoria::TAMANHO_MAXIMO_NOME + 1 ) );
        $categoria->setDescricao( 'Descrição válida.' );

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

    public function testSalvarCategoriaComNomeComTamanhoIgualAoPermitido(){
        $categoria = new Categoria();
        $categoria->setId( 1 );
        $categoria->setNome( str_repeat( 'a', Categoria::TAMANHO_MAXIMO_NOME ) );
        $categoria->setDescricao( 'Descrição válida.' );

        $idCadastrado = 1;
        $this->dao->method('salvar')->willReturn( $idCadastrado );

        $resultado = $this->service->salvar( $categoria );

        assertEquals( $resultado, $idCadastrado );
    }

    public function testSalvarCategoriaComDescricaoComTamanhoMaiorQueOPermitido(){
        $categoria = new Categoria();
        $categoria->setId( 1 );
        $categoria->setNome( 'Nome válido.' );
        $categoria->setDescricao( str_repeat( 'a', Categoria::TAMANHO_MAXIMO_DESCRICAO + 1 ) );

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

    public function testSalvarCategoriaComDescricaoComTamanhoIgualAoPermitido(){
        $categoria = new Categoria();
        $categoria->setId( 1 );
        $categoria->setNome( 'Nome válido.' );
        $categoria->setDescricao(  str_repeat( 'a', Categoria::TAMANHO_MAXIMO_DESCRICAO ) );

        $idCadastrado = 1;
        $this->dao->method('salvar')->willReturn( $idCadastrado );

        $resultado = $this->service->salvar( $categoria );

        assertEquals( $resultado, $idCadastrado );
    }
}