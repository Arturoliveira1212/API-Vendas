<?php

namespace app\services;

use app\dao\BancoDadosRelacional;
use app\exceptions\ServiceException;
use app\models\Cliente;
use app\models\Endereco;

class EnderecoService extends Service {
    public function __construct(){
        parent::__construct();
    }

    public function validar( $endereco, array &$erro = [] ){
        $this->validarCliente( $endereco, $erro );
        $this->validarCep( $endereco, $erro );
        $this->validarLogradouro( $endereco, $erro );
        $this->validarCidade( $endereco, $erro );
        $this->validarBairro( $endereco, $erro );
        $this->validarNumero( $endereco, $erro );
        $this->validarComplemento( $endereco, $erro );

        if( ! empty( $erro ) ){
            throw new ServiceException( 'Houve um erro ao salvar.' );
        }
    }

    private function validarCliente( $endereco, array &$erro ){
        if( ! $endereco->getCliente() instanceof Cliente ){
            $erro['cliente'] = 'Cliente inválido.';
        } else if( $endereco->getId() != BancoDadosRelacional::ID_INEXISTENTE && $this->enderecoPertenceAOutroCliente( $endereco ) ){
            $erro['cliente'] = 'Não é possível alterar o endereço pois ele não pertence ao cliente informado.';
        }
    }

    private function enderecoPertenceAOutroCliente( Endereco $endereco ){
        $enderecoBanco = $this->obterComId( $endereco->getId() );
        return( $enderecoBanco instanceof Endereco && $enderecoBanco->getCliente()->getId() != $endereco->getCliente()->getId() );
    }

    private function validarCep( Endereco $endereco, array &$erro ){
        if( mb_strlen( $endereco->getCep() ) == 0 ){
            $erro['cep'] = 'Preencha o campo cep.';
        } else if( ! $this->cepTemFormatoValido( $endereco->getCep() ) ){
            $erro['cep'] = 'Formato inválido.';
        }
    }

    function cepTemFormatoValido( string $cep ){
        $formatoValido = preg_match( "/^[0-9]{5}-?[0-9]{3}$/", $cep );
        return ( $formatoValido == 1 );
    }

    private function validarLogradouro( Endereco $endereco, array &$erro ){
        $this->validarTexto( $endereco->getLogradouro(), Endereco::TAMANHO_MINIMO_LOGRADOURO, Endereco::TAMANHO_MAXIMO_LOGRADOURO, 'logradouro', $erro );
    }

    private function validarCidade( Endereco $endereco, array &$erro ){
        $this->validarTexto( $endereco->getCidade(), Endereco::TAMANHO_MINIMO_CIDADE, Endereco::TAMANHO_MAXIMO_CIDADE, 'cidade', $erro );
    }

    private function validarBairro( Endereco $endereco, array &$erro ){
        $this->validarTexto( $endereco->getBairro(), Endereco::TAMANHO_MINIMO_BAIRRO, Endereco::TAMANHO_MAXIMO_BAIRRO, 'bairro', $erro );
    }

    private function validarNumero( Endereco $endereco, array &$erro ){
        if( ! is_numeric( $endereco->getNumero() ) ){
            $erro['numero'] = 'O campo numero precisa ser numérico.';
        }
    }

    private function validarComplemento( Endereco $endereco, array &$erro ){
        if( $endereco->getComplemento() != '' ){
            $this->validarTexto( $endereco->getComplemento(), Endereco::TAMANHO_MINIMO_COMPLEMENTO, Endereco::TAMANHO_MAXIMO_COMPLEMENTO, 'complemento', $erro );
        }
    }
}