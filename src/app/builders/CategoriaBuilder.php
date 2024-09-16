<?php

namespace app\builders;

use app\models\Categoria;

class CategoriaBuilder {
    private Categoria $categoria;

    private function __construct(){
        $this->categoria = new Categoria();
    }

    public static function novo(){
        return new CategoriaBuilder();
    }

    public function comId( int $id ){
        $this->categoria->setId( $id );
        return $this;
    }

    public function comNome( string $nome ){
        $this->categoria->setNome( $nome );
        return $this;
    }

    public function comDescricao( string $descricao ){
        $this->categoria->setDescricao( $descricao );
        return $this;
    }

    public function build(){
        return $this->categoria;
    }
}