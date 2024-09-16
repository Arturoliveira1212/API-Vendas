<?php

use app\exceptions\NaoEncontradoException;
use core\App;
use http\HttpMethod;
use http\Request;
use http\Response;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase {
    private $app;
    private $request;
    private $response;

    public function setUp() :void {
        $this->request = $this->createMock( Request::class );
        $this->response = $this->createMock( Response::class );
        $this->app = new App( $this->request, $this->response );
    }

    public function testLancaExcpetionAoBuscarPorRotaInexistente(){
        $this->request->method('uri')->willReturn('/rota-inexistente');
        $this->request->method('metodo')->willReturn( HttpMethod::GET );

        $this->response->expects($this->once())->method('recursoNaoEncontrado')->with($this->callback( function( $exception ) {
            return $exception instanceof NaoEncontradoException && $exception->getMessage() === 'Rota não encontrada.';
        }));

        $this->app->executar();
    }

    // public function testExecutarComRotaDinamica(){
    //     $requestMock = $this->createMock(Request::class);
    //     $responseMock = $this->createMock(Response::class);
    //     $controllerMock = $this->createMock(CategoriaController::class);
    
    //     $requestMock->method('uri')->willReturn('/categoria/1');
    //     $requestMock->method('metodo')->willReturn('GET');
    
    //     ClassFactory::method('makeController')->willReturn($controllerMock);
    
    //     $controllerMock->expects($this->once())
    //                   ->method('show')
    //                   ->with($this->equalTo(['1']));
    
    //     $app = new App($requestMock, $responseMock);
    //     $app->executar();
    // }

    // public function testExecutarComRotaSimples(){
    //     $requestMock = $this->createMock(Request::class);
    //     $responseMock = $this->createMock(Response::class);
    //     $controllerMock = $this->createMock(HomeController::class);
    
    //     $requestMock->method('uri')->willReturn('/home');
    //     $requestMock->method('metodo')->willReturn('GET');
    
    //     ClassFactory::method('makeController')->willReturn($controllerMock);
    
    //     $controllerMock->expects($this->once())
    //                   ->method('index');
    
    //     $app = new App($requestMock, $responseMock);
    //     $app->executar();
    // }
}