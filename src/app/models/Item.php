<?php

namespace app\models;

class Item extends Model {
    private int $id = 0;
    private ?Produto $produto = null;
    private string $tamanho = '';
    private int $estoque = 0;

    public function getId(){
        return $this->id;
    }

    public function setId( int $id ){
        $this->id = $id;
    }

    public function getProduto(){
        return $this->produto;
    }

    public function setProduto( ?Produto $produto ){
        $this->produto = $produto;
    }

    public function getTamanho(){
        return $this->tamanho;
    }

    public function setTamanho( string $tamanho ){
        $this->tamanho = $tamanho;
    }

    public function getEstoque(){
        return $this->estoque;
    }

    public function setEstoque( int $estoque ){
        $this->estoque = $estoque;
    }
}