<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CriaTabelaCategoria extends AbstractMigration {

    public function up(): void {
        $sql = <<<'SQL'
            CREATE TABLE categoria (
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                nome VARCHAR(100) NOT NULL,
                descricao VARCHAR(1000) DEFAULT NULL,
                ativo INT DEFAULT 1
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL;
        $this->execute( $sql );
    }

    public function down(): void {
        $this->execute( 'DROP TABLE categoria' );
    }
}
