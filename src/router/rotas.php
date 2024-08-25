<?php

use router\Router;

// Categoria
Router::get( '/categorias', 'Categoria@listarTodos' );
Router::get( '/categorias/[0-9]+', 'Categoria@listarComId' );
Router::post( '/categorias', 'Categoria@cadastrar' );
Router::put( '/categorias/[0-9]+', 'Categoria@atualizar' );
Router::delete( '/categorias/[0-9]+', 'Categoria@excluir' );

// Cliente
Router::get( '/clientes', 'Cliente@listarTodos' );
Router::get( '/clientes/[0-9]+', 'Cliente@listarComId' );
Router::post( '/clientes', 'Cliente@cadastrar' );
Router::put( '/clientes/[0-9]+', 'Cliente@atualizar' );
Router::delete( '/clientes/[0-9]+', 'Cliente@excluir' );

// Endereço
Router::get( '/enderecos', 'Endereco@listarTodos' );
Router::get( '/enderecos/[0-9]+', 'Endereco@listarComId' );
Router::get( '/clientes/[0-9]+/enderecos', 'Endereco@listarComCliente' );
Router::post( '/clientes/[0-9]+/enderecos', 'Endereco@cadastrar' );
Router::put( '/clientes/[0-9]+/enderecos/[0-9]+', 'Endereco@atualizar' );
Router::delete( '/enderecos/[0-9]+', 'Endereco@excluir' );

// Item
Router::get( '/itens', 'Item@ListarTodos' );
Router::get( '/itens/[0-9]+', 'Item@listarComId' );
Router::get( '/produtos/[0-9]+/itens', 'Item@listarComProduto' );
Router::post( '/produtos/[0-9]+/itens', 'Item@cadastrar' );
Router::put( '/produtos/[0-9]+/itens/[0-9]+', 'Item@atualizar' );
Router::delete( '/itens/[0-9]+', 'Item@desativarComId' );

// Item Pedido -> TO DO
// Pedido => TO DO

// Produto
Router::get( '/produtos', 'Produto@listarTodos' );
Router::get( '/produtos/[0-9]+', 'Produto@listarComId' );
Router::post( '/produtos', 'Produto@cadastrar' );
Router::put( '/produtos/[0-9]+', 'Produto@atualizar' );
Router::delete( '/produtos/[0-9]+', 'Produto@desativarComId' );