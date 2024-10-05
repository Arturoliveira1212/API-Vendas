<?php

use app\builders\CategoriaBuilder;
use app\dao\BancoDadosRelacional;
use app\dao\CategoriaDAO;
use app\models\Model;
use core\QueryParams;

describe( 'CategoriaDAO', function(){

    beforeEach(function() {
        $this->bancoDados = Mockery::mock( BancoDadosRelacional::class );
        $this->dao = new CategoriaDAO( $this->bancoDados );
    });

    describe( 'Salvar', function(){
        it( 'Salva categoria com sucesso', function(){
            $categoria = CategoriaBuilder::novo()->comId( BancoDadosRelacional::ID_INEXISTENTE )->comNome( 'Nome' )->comDescricao( 'Descricao' )->build();

            $this->bancoDados->shouldReceive('ultimoIdInserido')->andReturn(1);
            $this->bancoDados->shouldReceive('executarComTransacao')->andReturnUsing(function($operacao) use ($categoria) {
                // Chama a operação e passa a categoria
                return $operacao($categoria);
            });
            $this->bancoDados->shouldReceive('executar')->once()->withArgs( function( $comando, $parametros ){
                return $comando == "INSERT INTO categoria ( id, nome, descricao ) VALUES ( :id, :nome, :descricao )"
                && $parametros == [
                    'id' => 0,
                    'nome' => 'Nome',
                    'descricao' => 'Descricao'
                ];
            } )->andReturn(1);

            $idInserido = $this->dao->salvar( $categoria );
            expect( $idInserido )->toBe(1);
        } );

        it('Atualiza categoria com sucesso', function() {
            $categoria = CategoriaBuilder::novo()->comId(1)->comNome('Nome')->comDescricao('Descricao')->build();

            $this->bancoDados->shouldReceive('ultimoIdInserido')->andReturn(1);
            $this->bancoDados->shouldReceive('executarComTransacao')->andReturnUsing(function($operacao) use ($categoria) {
                // Chama a operação e passa a categoria
                return $operacao($categoria);
            });

            // Verifica a chamada ao método executar
            $this->bancoDados->shouldReceive('executar')->withArgs(function($comando, $parametros) use ($categoria) {
                return $comando === "UPDATE categoria SET nome = :nome, descricao = :descricao WHERE id = :id"
                    && $parametros === [
                        'id' => 1,
                        'nome' => $categoria->getNome(),
                        'descricao' => $categoria->getDescricao(),
                    ];
            })->andReturn(1);

            $idInserido = $this->dao->salvar($categoria);
            expect($idInserido)->toBe(1);
        });
    } );

    describe('Desativar', function() {
        it('Desativa categoria com sucesso', function() {
            $id = 1;

            $this->bancoDados->shouldReceive('desativar')->with('categoria', $id)->andReturn(true);
            $this->bancoDados->shouldReceive('executarComTransacao')->andReturnUsing(function($operacao) use ($id) {
                return $operacao($id);
            });

            $result = $this->dao->desativarComId($id);
            expect($result)->toBe(true);
        });

        it('Lança exceção ao falhar o desativar', function() {
            $id = 1;

            $this->bancoDados->shouldReceive('executarComTransacao')->andThrow(new Exception('Erro ao desativar'));

            expect(function() use ($id) {
                $this->dao->desativarComId($id);
            })->toThrow(new Exception('Erro ao desativar'));
        });
    });

    describe('Existe', function() {
        it('Verifica se categoria existe com sucesso', function() {
            $campo = 'nome';
            $valor = 'Nome';

            $this->bancoDados->shouldReceive('existe')->with('categoria', $campo, $valor)->andReturn(true);

            $result = $this->dao->existe($campo, $valor);
            expect($result)->toBe(true);
        });
    });

    describe('Obter com ID', function() {
        it('Obtém categoria com sucesso', function() {
            $id = 1;
            $resultado = ['id' => 1, 'nome' => 'Nome', 'descricao' => 'Descricao'];

            $this->bancoDados->shouldReceive('consultar')->withArgs(function($comando, $parametros) use ($id) {
                return $comando === "SELECT * FROM categoria WHERE id = :id AND ativo = :ativo"
                    && $parametros === ['id' => $id, 'ativo' => true];
            })->andReturn([$resultado]);

            $categoria = $this->dao->obterComId($id);
            expect($categoria)->toBeAnInstanceOf(Model::class); // Verifique se a classe está correta.
        });

        it('Retorna null se a categoria não existir', function() {
            $id = 999;

            $this->bancoDados->shouldReceive('consultar')->andReturn([]);

            $categoria = $this->dao->obterComId($id);
            expect($categoria)->toBeNull();
        });
    });

    describe('Obter com Restrições', function() {
        it('Obtém categorias com restrições com sucesso', function() {
            $queryParams = new QueryParams([]); // Assuma que você tem um objeto QueryParams configurado
            $resultado = [['id' => 1, 'nome' => 'Nome', 'descricao' => 'Descricao']];

            $this->bancoDados->shouldReceive('consultar')->andReturn($resultado);

            $categorias = $this->dao->obterComRestricoes($queryParams);
            expect($categorias)->toHaveLength(1);
            expect($categorias[0])->toBeAnInstanceOf(Model::class);
        });
    });

    describe('Obter Objetos', function() {
        it('Obtém objetos com sucesso', function() {
            $comando = "SELECT * FROM tabela";
            $parametros = [];
            $resultado = [['id' => 1, 'nome' => 'Nome', 'descricao' => 'Descricao']];

            $this->bancoDados->shouldReceive('consultar')->with($comando, $parametros)->andReturn($resultado);

            $objetos = $this->dao->obterObjetos($comando, [$this->dao, 'transformarEmObjeto'], $parametros);
            expect($objetos)->toHaveLength(1);
            expect($objetos[0])->toBeAnInstanceOf(Model::class); // Verifique se a classe está correta.
        });
    });
} );