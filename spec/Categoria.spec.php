<?php

use app\exceptions\NaoEncontradoException;
use core\App;
use core\ClassFactory;
use core\HttpRequest;

use function Kahlan\describe;
use function Kahlan\it;

describe( 'Categoria', function(){
    $this->categoriaDAO = null;

    beforeAll( function(){
        $this->categoriaDAO = ClassFactory::makeDAO( 'Categoria' );
    });

    it( 'Lança excessão ao buscar recurso inexistente.', function(){
        // expect( function(){
        //     $this->app->executar( '/recurso-inexistente', HttpRequest::METODO_GET );
        // })->toThrow( new NaoEncontradoException( 'Recurso não encontrado.', HttpRequest::CODIGO_NAO_EXISTENTE ) );
    });

    // TO DO => Implementar mais cenários de teste.
});