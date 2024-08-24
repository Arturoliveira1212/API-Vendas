<?php

namespace app\models;

class ItemPedido {
    private int $id = 0;
    private ?Item $item = null;
    private float $valorVenda = 0.0;
    private int $quantidade = 0;
}