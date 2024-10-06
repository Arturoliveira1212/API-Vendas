<?php

use app\builders\ClienteBuilder;
use app\dao\DAOEmBDR;
use app\exceptions\ServiceException;
use app\models\Cliente;
use app\services\ClienteService;
use app\services\Service;

describe( 'ClienteService', function(){
    beforeEach( function(){
        $this->dao = Mockery::mock(DAOEmBDR::class);
        $this->service = new ClienteService($this->dao);
    } );

    describe( 'Salvar', function(){
        function validarMensagemAoSalvar( Service $service, Cliente $cliente, string $campoErro, string $mensagemErro ){
            try {
                $service->salvar($cliente);
                // Forcando a exceção
                throw new ServiceException('');
            } catch( ServiceException $e){
                $erro = json_decode($e->getMessage(), true);
                expect( $erro )->not->toBeEmpty();
                expect( $erro )->toContainKey( $campoErro );
                expect( $erro[ $campoErro ] )->toEqual( $mensagemErro);
            }
        }

        it('Lança exceção ao não enviar nome válido para cliente', function() {
            $this->dao->shouldReceive('existe')->andReturn(false);

            $cliente = ClienteBuilder::novo()->comId(1)->comNome('')->comCpf('354.769.850-26')->comDataNascimento( new DateTime('2000-01-01'))->build();

            validarMensagemAoSalvar( $this->service, $cliente, 'nome', 'Preencha o campo nome.' );
        });

        it('Lança exceção ao enviar nome com tamanho maior que o permitido', function() {
            $this->dao->shouldReceive('existe')->andReturn(false);

            $cliente = ClienteBuilder::novo()->comId(1)->comNome(str_repeat('a', Cliente::TAMANHO_MAXIMO_NOME + 1))->comCpf('354.769.850-26')->comDataNascimento( new DateTime('2000-01-01'))->build();

            validarMensagemAoSalvar( $this->service, $cliente, 'nome', 'O campo nome deve ter entre ' . Cliente::TAMANHO_MINIMO_NOME . ' e ' . Cliente::TAMANHO_MAXIMO_NOME . ' caracteres.' );
        });

        it('Salva categoria com sucesso ao enviar nome com tamanho igual ao permitido', function() {
            $this->dao->shouldReceive('existe')->andReturn(false);

            $cliente = ClienteBuilder::novo()->comId(1)->comNome(str_repeat('a', Cliente::TAMANHO_MAXIMO_NOME))->comCpf('354.769.850-26')->comDataNascimento( new DateTime('2000-01-01'))->build();

            $idCadastrado = 1;
            $this->dao->shouldReceive('salvar')->andReturn($idCadastrado);

            $resultado = $this->service->salvar($cliente);
            expect($resultado)->toEqual($idCadastrado);
        });

        it( 'Lança exceção ao enviar cpf vazio', function(){
            $cliente = ClienteBuilder::novo()->comId(1)->comNome('Nome válido')->comDataNascimento( new DateTime('2000-01-01'))->build();

            validarMensagemAoSalvar( $this->service, $cliente, 'cpf', 'Preencha o campo cpf.' );
        } );

        it( 'Lança exceção ao enviar cpf no formato inválido', function(){
            $cliente = ClienteBuilder::novo()->comId(1)->comNome('Nome válido')->comCpf('181')->comDataNascimento( new DateTime('2000-01-01'))->build();

            validarMensagemAoSalvar( $this->service, $cliente, 'cpf', 'Formato inválido.' );
        } );

        it( 'Lança exceção ao enviar cpf no formato válido mas com números repetidos', function(){
            $cliente = ClienteBuilder::novo()->comId(1)->comNome('Nome válido')->comCpf('111.111.111-11')->comDataNascimento( new DateTime('2000-01-01'))->build();

            validarMensagemAoSalvar( $this->service, $cliente, 'cpf', 'Cpf inválido.' );
        } );

        it( 'Lança exceção ao enviar cpf já cadastrado no sistema', function(){
            $this->dao->shouldReceive('existe')->andReturn(true);

            $cliente = ClienteBuilder::novo()->comId(1)->comNome('Nome válido')->comCpf('354.769.850-26')->comDataNascimento( new DateTime('2000-01-01'))->build();

            validarMensagemAoSalvar( $this->service, $cliente, 'cpf', 'Cpf já cadastrado no sistema.' );
        } );

        it( 'Lança exceção ao não enviar data nascimento válida para cliente', function(){
            $this->dao->shouldReceive('existe')->andReturn(false);

            $cliente = ClienteBuilder::novo()->comId(1)->comNome('Nome válido')->comCpf('354.769.850-26')->comDataNascimento( null )->build();

            validarMensagemAoSalvar( $this->service, $cliente, 'dataNascimento', 'Data de nascimento inválida.' );
        } );

        it( 'Lança exceção ao enviar data nascimento maior que a data atual', function(){
            $this->dao->shouldReceive('existe')->andReturn(false);

            $diaAtualMais1Dia = new DateTime();
            $diaAtualMais1Dia->modify( '+1 days' );
            $cliente = ClienteBuilder::novo()->comId(1)->comNome('Nome válido')->comCpf('354.769.850-26')->comDataNascimento( $diaAtualMais1Dia )->build();

            validarMensagemAoSalvar( $this->service, $cliente, 'dataNascimento', 'A data de nascimento não pode ser maior que o dia atual.' );
        } );

        it( 'Lança exceção ao enviar data nascimento que não tem idade mínima necessária', function(){
            $this->dao->shouldReceive('existe')->andReturn(false);

            $cliente = ClienteBuilder::novo()->comId(1)->comNome('Nome válido')->comCpf('354.769.850-26')->comDataNascimento( new DateTime() )->build();

            validarMensagemAoSalvar( $this->service, $cliente, 'dataNascimento', 'O cliente precisa ter no mínimo ' . Cliente::IDADE_MINIMA . ' anos para se cadastrar no sistema.' );
        } );

        it('Salva categoria com sucesso ao enviar data de nascimento com idade mínima', function() {
            $this->dao->shouldReceive('existe')->andReturn(false);

            $dataNascimentoMinima = new DateTime();
            $dataNascimentoMinima->modify( '- ' . Cliente::IDADE_MINIMA . ' years' );
            $cliente = ClienteBuilder::novo()->comId(1)->comNome('Nome válido')->comCpf('354.769.850-26')->comDataNascimento( $dataNascimentoMinima )->build();

            $idCadastrado = 1;
            $this->dao->shouldReceive('salvar')->andReturn($idCadastrado);

            $resultado = $this->service->salvar($cliente);
            expect($resultado)->toEqual($idCadastrado);
        });
    } );

    describe( 'ObterComId', function(){

    } );

    describe( 'ObterComRestricoes', function(){

    } );

    describe( 'DesativarComId', function(){

    } );
} );