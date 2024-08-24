<?php

namespace http;

abstract class HttpStatusCode {
    const OK = 200;
    const RECURSO_CRIADO = 201;
    const ERRO_CLIENTE = 400;
    const NAO_EXISTENTE = 404;
    const ERRO_SERVIDOR = 500;
}