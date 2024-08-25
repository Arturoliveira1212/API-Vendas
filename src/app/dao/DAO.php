<?php

namespace app\dao;

interface DAO {
    public function salvar( $objeto );
    public function desativarComId( int $id );
    public function existe( string $campo, string $valor );
    public function obterComId( int $id );
    public function obterComRestricoes( array $restricoes = [] );
}