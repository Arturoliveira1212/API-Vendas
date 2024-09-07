<?php

use PHPUnit\Framework\TestCase;
use app\controllers\CategoriaController;
use app\controllers\Controller;
use app\exceptions\CampoNaoEnviadoException;
use app\exceptions\NaoEncontradoException;
use app\models\Categoria;
use app\services\CategoriaService;
use app\services\Service;
use http\Response;
use http\Request;

class CategoriaControllerTest extends TestCase {
    private $controller;
    private $service;
    private $response;

    protected function setUp() :void {
        // Criar mocks das dependências
        $this->service = $this->createMock( CategoriaService::class );
        $this->response = $this->createMock( Response::class );
        // $this->response = $this->getMockBuilder( Response::class )->disableOriginalConstructor()->getMock();

        // Criar instância do Controller usando a fábrica
        $this->controller = new CategoriaController( $this->service );
        $this->controller->setResponse( $this->response );
    }

    public function testCadastrarComSucesso(){
        $dadosCategoria = [
            'nome' => 'Categoria 1',
            'descricao' => 'Descrição da categoria'
        ];

        // Simula o serviço retornando um ID após salvar
        $this->service->method('salvar')->willReturn(1);

        // Mock do corpo da requisição
        $requestMock = $this->getMockBuilder( Request::class )->disableOriginalConstructor()->getMock();
        $requestMock->method('corpoRequisicao')->willReturn($dadosCategoria);

        $this->controller->setRequest($requestMock);

        // Espera que o método recursoCriado seja chamado
        $this->response->expects($this->once())->method('recursoCriado')->with( $this->equalTo(1), $this->equalTo('Categoria cadastrada com sucesso.') );

        // Executa o método cadastrar da controller
        $this->controller->cadastrar();
    }

    public function testCadastrarCampoNaoEnviado(){
        // Simula uma exceção de campo não enviado
        $this->service->method('salvar')->will($this->throwException(new CampoNaoEnviadoException('Campo não enviado')));

        // Mock do corpo da requisição com campo ausente
        $requestMock = $this->getMockBuilder('http\Request')->disableOriginalConstructor()->getMock();
        $requestMock->method('corpoRequisicao')->willReturn([]);

        $this->controller->setRequest($requestMock);

        // Espera que o método campoNaoEnviado seja chamado com a exceção
        $this->response->expects($this->once())->method('campoNaoEnviado')->with($this->isInstanceOf(CampoNaoEnviadoException::class));

        // Executa o método cadastrar da controller
        $this->controller->cadastrar();
    }

    public function testAtualizarComSucesso(){
        $dadosCategoria = ['nome' => 'Categoria Atualizada', 'descricao' => 'Descrição atualizada'];
        $idCategoria = 1;

        // Mock do serviço retornando uma categoria existente
        $this->service->method('obterComId')->willReturn(new Categoria());

        // Simula o serviço retornando um ID após salvar
        $this->service->method('salvar')->willReturn($idCategoria);

        // Mock do corpo da requisição
        $requestMock = $this->getMockBuilder('http\Request')->disableOriginalConstructor()->getMock();
        $requestMock->method('corpoRequisicao')->willReturn($dadosCategoria);

        $this->controller->setRequest($requestMock);

        // Espera que o método recursoAlterado seja chamado
        $this->response->expects($this->once())->method('recursoAlterado')->with($this->equalTo('Categoria atualizada com sucesso.'));

        // Executa o método atualizar da controller
        $this->controller->atualizar(['categorias' => $idCategoria]);
    }

    public function testExcluirCategoriaNaoEncontrada(){
        $idCategoria = 1;

        // Simula o serviço retornando uma categoria não encontrada
        // $this->service->method('obterComId')->willThrowException(new NaoEncontradoException('Categoria não encontrada.'));
        $this->service->method('obterComId')->willReturn(null);

        $this->expectException( NaoEncontradoException::class );;

        // Executa o método excluir da controller
        $this->controller->excluir(['categorias' => $idCategoria]);

        // Espera que o método recursoNaoEncontrado seja chamado com a exceção
        $this->response->expects($this->once())->method('recursoNaoEncontrado')->with($this->isInstanceOf(NaoEncontradoException::class));
    }
}
