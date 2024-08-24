<?php

require './bootstrap.php';

use core\PDOSingleton;

$pdo = PDOSingleton::get();

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/src/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/src/db/seeds'
    ],
    'templates' => [
        'file' => '%%PHINX_CONFIG_DIR%%/src/db/templates/migracao.php',
        'seedFile' => '%%PHINX_CONFIG_DIR%%/src/db/templates/semeadura.php'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'name' => $_ENV['DB_DATABASE'],
            'connection' => $pdo
        ],
        'development' => [
            'name' => $_ENV['DB_DATABASE'],
            'connection' => $pdo
        ],
        'testing' => [
            'name' => $_ENV['DB_DATABASE'],
            'connection' => $pdo
        ]
    ],
    'version_order' => 'creation'
];
