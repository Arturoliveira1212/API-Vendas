<?php

namespace app\models;

class Endereco extends Model {
    private int $id = 0;
    private ?Cliente $cliente = null;
    private string $cep = '';
    private string $logradouro = '';
    private string $cidade = ''; // TO DO => Fazer uma entidade separada.
    private string $bairro = ''; // TO DO => Fazer uma entidade separada.
    private int $numero = 0;
    private string $complemento = '';

    const TAMANHO_MINIMO_LOGRADOURO = 1;
    const TAMANHO_MAXIMO_LOGRADOURO = 500;
    const TAMANHO_MINIMO_CIDADE = 1;
    const TAMANHO_MAXIMO_CIDADE = 100;
    const TAMANHO_MINIMO_BAIRRO = 1;
    const TAMANHO_MAXIMO_BAIRRO = 100;
    const TAMANHO_MINIMO_COMPLEMENTO = 1;
    const TAMANHO_MAXIMO_COMPLEMENTO = 200;

    public function getId(){
        return $this->id;
    }

    public function setId( int $id ){
        $this->id = $id;
    }

    public function getCliente(){
        return $this->cliente;
    }

    public function setCliente( ?Cliente $cliente ){
        $this->cliente = $cliente;
    }

    public function getCep(){
        return $this->cep;
    }

    public function setCep( string $cep ){
        $this->cep = $cep;
    }

    public function getLogradouro(){
        return $this->logradouro;
    }

    public function setLogradouro( string $logradouro ){
        $this->logradouro = $logradouro;
    }

    public function getCidade(){
        return $this->cidade;
    }

    public function setCidade( string $cidade ){
        $this->cidade = $cidade;
    }

    public function getBairro(){
        return $this->bairro;
    }

    public function setBairro( string $bairro ){
        $this->bairro = $bairro;
    }

    public function getNumero(){
        return $this->numero;
    }

    public function setNumero( int $numero ){
        $this->numero = $numero;
    }

    public function getComplemento(){
        return $this->complemento;
    }

    public function setComplemento( string $complemento ){
        $this->complemento = $complemento;
    }
}