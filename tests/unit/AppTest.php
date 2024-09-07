<?php

use app\exceptions\NaoEncontradoException;
use core\App;
use http\Request;
use http\Response;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase {
    public function testLancaExcecaoComRecursoInexistente(){
        $mockRequest = $this->createMock( Request::class );
        $mockResponse = $this->createMock( Response::class );

        $mockRequest->method('uri')->willReturn('/recurso-inexistente');
        $mockRequest->method('metodo')->willReturn('GET');

        $app = new App( $mockRequest, $mockResponse );

        $this->expectException( NaoEncontradoException::class );
        $this->expectExceptionMessage('Recurso não encontrado.');

        $app->executar();
    }
}