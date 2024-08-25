<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CriaTabelaEndereco extends AbstractMigration {

    public function up(): void {
        $sql = <<<'SQL'
            CREATE TABLE endereco (
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                idCliente INT NOT NULL,
                cep VARCHAR(9) NOT NULL,
                cidade VARCHAR(100) NOT NULL,
                bairro VARCHAR(100) DEFAULT NULL,
                logradouro VARCHAR(500) DEFAULT NULL,
                numero VARCHAR(1000) NOT NULL,
                complemento VARCHAR(100) DEFAULT NULL,
                ativo INT DEFAULT 1,
                FOREIGN KEY (idCliente) REFERENCES cliente(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL;
        $this->execute( $sql );
    }

    public function down(): void {
        $this->execute( 'DROP TABLE endereco' );
    }
}
