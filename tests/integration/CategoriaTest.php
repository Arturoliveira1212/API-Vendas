<?php

namespace app\integration;

use GuzzleHttp\Client;
use http\HttpMethod;
use http\HttpStatusCode;
use PHPUnit\Framework\TestCase;

class CategoriaTest extends TestCase {
    private Client $client;
    private string $nomeRecurso = '/categorias';

    protected function setUp(): void {
        parent::setUp();
        // Configure o cliente Guzzle com a URL base da API
        $this->client = new Client([
            'base_uri' => 'http://localhost/api/', // Substitua pela URL base da sua API
            'timeout'  => 5.0,
        ]);
    }

    public function testListarTodos(){
        $response = $this->client->get( $this->nomeRecurso );
        $contentType = $response->getHeaderLine('Content-Type');
        $bodyRequest = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals( HttpStatusCode::OK, $response->getStatusCode() );
        $this->assertStringContainsString( 'application/json', $contentType );
        $this->assertStringContainsString( 'UTF-8', $contentType );
        $this->assertIsArray( $bodyRequest );
    }

    public function testPostRequest(): void {
        // try {
            $response = $this->client->request('POST', 'categorias', [
                'json' => [
                    'nome' => 'Nova Categoria',
                    'descricao' => 'Descrição da nova categoria',
                ]
            ]);
            $this->assertEquals(201, $response->getStatusCode());
            $data = json_decode($response->getBody()->getContents(), true);
            $this->assertArrayHasKey('id', $data);
            // Adicione outras asserções conforme necessário
        // } catch (RequestException $e) {
        //     $this->fail('Request failed: ' . $e->getMessage());
        // }
    }
}