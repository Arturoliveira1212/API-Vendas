<?php

use Dotenv\Dotenv;

session_start();

require_once 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();