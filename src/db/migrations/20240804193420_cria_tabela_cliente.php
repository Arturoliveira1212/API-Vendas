<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CriaTabelaCliente extends AbstractMigration {

    public function up(): void {
        $sql = <<<'SQL'
            CREATE TABLE cliente (
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                nome VARCHAR(200) NOT NULL,
                cpf VARCHAR(14) DEFAULT NULL,
                dataCadastro DATETIME NOT NULL,
                ativo INT DEFAULT 1
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL;
        $this->execute( $sql );
    }

    public function down(): void {
        $this->execute( 'DROP TABLE cliente' );
    }
}
