<?php

namespace app\models;

class Item {
    private int $id = 0;
    private ?Produto $produto = null;
    private string $tamanho = ''; // TO DO => criar Enum ou classe Tamanho
    private int $estoque = 0;

    // possuem variações de tamanhos e cada uma delas tem um controle de estoque
    // distinto
}