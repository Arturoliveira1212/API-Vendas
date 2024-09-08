<?php

namespace app\dao;

use app\models\Model;

interface DAO {
    public function salvar( Model $objeto );
    public function desativarComId( int $id );
    public function existe( string $campo, string $valor );
    public function obterComId( int $id );
    public function obterComRestricoes( array $restricoes = [] );
}