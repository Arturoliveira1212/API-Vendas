<?php

namespace app\views;

use http\HttpStatusCode;
use http\Response;

class CategoriaView extends View {
    public function sucessoAoSalvar( int $idCadastrado ){
        Response::json( [
            'message' => 'Categoria cadastrada com sucesso.',
            '_id' => $idCadastrado
        ], HttpStatusCode::RECURSO_CRIADO );
    }
}