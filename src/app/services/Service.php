<?php

namespace app\services;

use app\dao\DAO;
use core\ClassFactory;

abstract class Service {
    protected string $classe;
    protected DAO $dao;

    public function __construct(){
        $this->setClasse( str_replace( 'Service','', basename( str_replace( '\\', '/', get_class( $this ) ) ) ) );
        $this->setDao( ClassFactory::makeDAO( $this->getClasse() ) );
    }

    public function getClasse(){
        return $this->classe;
    }

    public function setClasse( string $classe ){
        $this->classe = $classe;
    }

    protected function getDao(){
        return $this->dao;
    }

    protected function setDao( DAO $dao ){
        $this->dao = $dao;
    }

    abstract protected function validar( $objeto, array &$erro = [] );

    public function salvar( $objeto, array &$erro = [] ){
        $this->validar( $objeto, $erro );
        return $this->getDao()->salvar( $objeto );
    }

    public function desativarComId( int $id ){
        return $this->getDao()->desativarComId( $id );
    }

    public function obterComId( int $id ){
        return $this->getDao()->obterComId( $id );
    }

    public function obterComRestricoes( array $restricoes = [] ){
        return $this->getDao()->obterComRestricoes( $restricoes );
    }
}