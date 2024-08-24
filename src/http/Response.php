<?php

namespace http;

class Response {

    public static function json( array $data = [], int $status = HttpStatusCode::OK ){
        http_response_code( $status );
        header( 'Content-Type: application/json' );
        echo json_encode( $data );
    }
}