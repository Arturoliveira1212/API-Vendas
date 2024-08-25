<?php

namespace app\services;

use app\exceptions\ServiceException;
use app\models\Cliente;
use DateTime;

class ClienteService extends Service {
    public function __construct(){
        parent::__construct();
    }

    public function validar( $cliente, array &$erro = [] ){
        $this->validarNome( $cliente, $erro );
        $this->validarCpf( $cliente, $erro );
        $this->validarDataNascimento( $cliente, $erro );

        if( ! empty( $erro ) ){
            throw new ServiceException( 'Houve um erro ao salvar.' );
        }
    }

    private function validarNome( Cliente $cliente, array &$erro ){
        $this->validarTexto( $cliente->getNome(), Cliente::TAMANHO_MINIMO_NOME, Cliente::TAMANHO_MAXIMO_NOME, 'nome', $erro );
    }

    private function validarCpf( Cliente $cliente, array &$erro ){
        if( mb_strlen( $cliente->getCpf() ) == 0 ){
            $erro['cpf'] = 'Preencha o campo cpf.';
        } else if( ! $this->cpfTemFormatoValido( $cliente->getCpf() ) ) {
            $erro['cpf'] = 'Formato inválido.';
        } else if( $this->cpfEhFormadoPorNumerosRepetidos( $cliente->getCpf() ) ){
            $erro['cpf'] = 'Cpf inválido.';
        } else if( $this->existe( 'cpf', $cliente->getCpf() ) ){
            $erro['cpf'] = 'Cpf já cadastrado no sistema.';
        }
    }

    private function cpfTemFormatoValido( string $cpf ){
        $formatoValido = preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $cpf);
        return ( $formatoValido == 1 );
    }

    private function cpfEhFormadoPorNumerosRepetidos( string $cpf ){
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
        $numerosRepetidos = preg_match('/(\d)\1{10}/', $cpf);
        return ( $numerosRepetidos == 1 );
    }

    private function validarDataNascimento( Cliente $cliente, array &$erro ){
        if( $this->dataNascimentoEhMaiorQueDataAtual( $cliente->getDataNascimento() ) ){
            $erro['dataNascimento'] = 'O campo dataNascimento não pode ser maior que o dia atual.';
        } else if( ! $this->clienteTemIdadeMinima( $cliente->getDataNascimento(), Cliente::IDADE_MINIMA ) ){
            $erro['dataNascimento'] = 'O cliente precisa ter no mínimo ' . Cliente::IDADE_MINIMA . ' anos para se cadastrar no sistema.';
        }
    }

    private function dataNascimentoEhMaiorQueDataAtual( DateTime $dataNascimento ){
        return $dataNascimento > new DateTime();
    }

    private function clienteTemIdadeMinima( DateTime $dataNascimento, int $idadeMinima ){
        $dataMinima = ( new DateTime() )->modify( "- $idadeMinima years" );
        return $dataNascimento < $dataMinima;
    }
}