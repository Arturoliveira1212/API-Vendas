<?php

namespace app\builders;

use app\models\Cliente;
use DateTime;

class ClienteBuilder {
    private Cliente $cliente;

    private function __construct(){
        $this->cliente = new Cliente();
    }

    public static function novo(){
        return new ClienteBuilder();
    }

    public function comId( int $id ){
        $this->cliente->setId( $id );
        return $this;
    }

    public function comNome( string $nome ){
        $this->cliente->setNome( $nome );
        return $this;
    }

    public function comCpf( string $cpf ){
        $this->cliente->setCpf( $cpf );
        return $this;
    }

    public function comDataNascimento( DateTime $dataNascimento ){
        $this->cliente->setDataNascimento( $dataNascimento );
        return $this;
    }

    public function build(){
        return $this->cliente;
    }
}