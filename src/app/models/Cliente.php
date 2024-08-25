<?php

namespace app\models;

use DateTime;

class Cliente extends Model {
    private int $id = 0;
    private string $nome = '';
    private string $cpf = '';
    private ?DateTime $dataNascimento = null;

    const TAMANHO_MINIMO_NOME = 1;
    const TAMANHO_MAXIMO_NOME = 200;
    const IDADE_MINIMA = 18;

    public function getId(){
        return $this->id;
    }

    public function setId( int $id ){
        $this->id = $id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome( string $nome ){
        $this->nome = $nome;
    }

    public function getCpf(){
        return $this->cpf;
    }

    public function setCpf( string $cpf ){
        $this->cpf = $cpf;
    }

    public function getDataNascimento( string $formato = null ){
        if( isset( $formato ) )
            return $this->dataNascimento->format( $formato );
        return $this->dataNascimento;
    }

    public function setDataNascimento( ?DateTime $dataNascimento ){
        $this->dataNascimento = $dataNascimento;
    }
}