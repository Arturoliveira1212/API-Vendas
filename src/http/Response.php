<?php
namespace http;

use Exception;

class Response {
    private $statusCode;
    private $data;
    private $headers;

    public function __construct(){
        $this->statusCode = HttpStatusCode::OK;
        $this->data = [];
        $this->headers = ['Content-Type' => 'application/json'];
    }

    public function getStatusCode(): int {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode) {
        $this->statusCode = $statusCode;
    }

    public function getData(): array {
        return $this->data;
    }

    public function setData(array $data) {
        $this->data = $data;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function setHeaders(array $headers) {
        $this->headers = array_merge($this->headers, $headers);
    }

    public function recursoNaoEncontrado(Exception $e) {
        $this->setStatusCode(HttpStatusCode::NOT_FOUND);
        $this->setData(['message' => $e->getMessage() ?? 'Recurso não encontrado']);
        $this->sendResponse();
    }

    public function recursoCriado($idCadastrado, string $mensagemSucesso) {
        $this->setStatusCode(HttpStatusCode::CREATED);
        $this->setData([
            'message' => $mensagemSucesso,
            '_id' => $idCadastrado
        ]);
        $this->sendResponse();
    }

    public function recursoAlterado(string $mensagemSucesso) {
        $this->setStatusCode(HttpStatusCode::OK);
        $this->setData(['message' => $mensagemSucesso]);
        $this->sendResponse();
    }

    public function campoNaoEnviado(Exception $e) {
        $this->setStatusCode(HttpStatusCode::BAD_REQUEST);
        $this->setData(['message' => $e->getMessage() ?? 'Campo não enviado']);
        $this->sendResponse();
    }

    public function erroAoSalvar( Exception $e ){
        $this->setStatusCode(HttpStatusCode::BAD_REQUEST);
        $this->setData( json_decode( $e->getMessage(), true ) );
        $this->sendResponse();
    }

    public function erroInternoAPI() {
        $this->setStatusCode(HttpStatusCode::INTERNAL_SERVER_ERROR);
        $this->setData(['message' => 'Houve um erro interno.']);
        $this->sendResponse();
    }

    public function listarDados(array $dados) {
        $this->setStatusCode(HttpStatusCode::OK);
        $this->setData($dados);
        $this->sendResponse();
    }

    public function recursoRemovido() {
        $this->setStatusCode(HttpStatusCode::NO_CONTENT);
        $this->setData([]);
        $this->sendResponse();
    }

    private function sendResponse() {
        http_response_code($this->statusCode);
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }
        echo json_encode($this->data);
    }
}