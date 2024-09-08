<?php

namespace core;

class QueryParams {
    private array $restricoes = [];
    private ?int $limit = null;
    private ?int $offset = null;
    private string $orderBy = '';

    public function __construct( array $parametrosRequisicao ){
        // TO DO => Sanitização dos dados.
        $this->setLimit( isset( $parametrosRequisicao['limit'] ) ? intval( $parametrosRequisicao['limit'] ) : null );
        $this->setOffset( isset( $parametrosRequisicao['ofset'] ) ? intval( $parametrosRequisicao['ofset'] ) : null );
        $this->setOrderBy( $parametrosRequisicao['orderBy'] ?? '' );
        $this->setRestricoes( array_diff_key( $parametrosRequisicao, array_flip( [ 'url', 'limit', 'ofset', 'orderBy' ] ) ) );
    }

    public function getRestricoes(){
        return $this->restricoes;
    }

    public function setRestricoes( array $restricoes ){
        $this->restricoes = $restricoes;
    }

    public function getLimit(){
        return $this->limit;
    }

    public function setLimit(?int $limit){
        $this->limit = $limit;
    }

    public function getOffset(){
        return $this->offset;
    }

    public function setOffset( ?int $offset ){
        $this->offset = $offset;
    }

    public function getOrderBy(){
        return $this->orderBy;
    }

    public function setOrderBy(string $orderBy){
        $this->orderBy = $orderBy;
    }
}