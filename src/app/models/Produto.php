<?php

namespace app\models;

use DateTime;

class Produto {
    private int $id = 0;
    private string $referencia = '';
    private string $nome = '';
    private string $descricao = '';
    private float $peso = 0.0;
    private ?Categoria $categoria = null;
    private string $caminhoImagem = '';
    private ?DateTime $dataCadastro = null;

    public function getId(){
        return $this->id;
    }

    public function setId( int $id ){
        $this->id = $id;
    }

    // TO DO => Fazer getters e setters para todos os atributos.
}