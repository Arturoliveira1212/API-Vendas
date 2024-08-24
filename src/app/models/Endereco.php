<?php

namespace app\models;

class Endereco {
    private int $id = 0;
    private ?Cliente $cliente = null;
    private string $cep = '';
    private string $logradouro = '';
    private string $cidade = '';
    private string $bairro = '';
    private int $numero = 0;
    private string $complemento = '';

    //nome do logradouro, nome da cidade, nome do bairro, número, CEP e complemento.
}