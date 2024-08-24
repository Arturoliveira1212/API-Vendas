<?php

namespace app\models;

class Pedido {
    private int $id = 0;
    private ?Endereco $endereco = null;
    private ?Cliente $cliente = null;
    private float $valorProdutos = 0.0;
    private float $valorDescontos = 0.0;
    private float $valorFrete = 0.0;
    private float $valorTotal = 0.0;
    private int $formaPagamento = 0;
    private array $itensPedido = [];

    /* Para a venda desses produtos é desejado armazenar as variações vendidas e seu
preço de venda, o endereço de entrega, o cliente, o valor total, o valor de frete,
descontos e a forma de pagamento, podendo ser PIX, Boleto ou Cartão(1x). Pagamentos
via PIX possuem um desconto fixo de 10% no valor dos itens com o frete. Para calcular
o valor total, deve-se somar todos os preços das variações dos produtos com o valor
do frete e subtrair pelo desconto da forma de pagamento, se tiver. */
}