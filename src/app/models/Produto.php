<?php

namespace app\models;

use DateTime;

class Produto extends Model {
    private int $id = 0;
    private string $referencia = '';
    private string $nome = '';
    private string $descricao = '';
    private float $peso = 0.0;
    private ?Categoria $categoria = null;
    // private string $caminhoImagem = ''; // TO DO => Implementar imagens.
    private ?DateTime $dataCadastro = null;

    const TAMANHO_MINIMO_REFERENCIA = 5;
    const TAMANHO_MAXIMO_REFERENCIA = 20;
    const TAMANHO_MINIMO_NOME = 1;
    const TAMANHO_MAXIMO_NOME = 100;
    const TAMANHO_MINIMO_DESCRICAO = 1;
    const TAMANHO_MAXIMO_DESCRICAO = 500;

    public function getId(){
        return $this->id;
    }

    public function setId( int $id ){
        $this->id = $id;
    }

    public function getReferencia(){
        return $this->referencia;
    }

    public function setReferencia( string $referencia ){
        $this->referencia = $referencia;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome( string $nome ){
        $this->nome = $nome;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function setDescricao( string $descricao ){
        $this->descricao = $descricao;
    }

    public function getPeso(){
        return $this->peso;
    }

    public function setPeso( float $peso ){
        $this->peso = $peso;
    }

    public function getCategoria(){
        return $this->categoria;
    }

    public function setCategoria( ?Categoria $categoria ){
        $this->categoria = $categoria;
    }

    public function getCaminhoImagem(){
        return $this->caminhoImagem;
    }

    public function setCaminhoImagem( string $caminhoImagem ){
        $this->caminhoImagem = $caminhoImagem;
    }

    public function getDataCadastro(){
        return $this->dataCadastro;
    }

    public function setDataCadastro( ?DateTime $dataCadastro ){
        $this->dataCadastro = $dataCadastro;
    }
}