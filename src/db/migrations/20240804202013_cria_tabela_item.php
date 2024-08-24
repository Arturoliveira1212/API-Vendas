<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CriaTabelaItem extends AbstractMigration {

    public function up(): void {
        $sql = <<<'SQL'
            CREATE TABLE item (
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                idProduto INT NOT NULL,
                tamanho VARCHAR(5) NOT NULL,
                estoque INT NOT NULL DEFAULT 0,
                ativo INT DEFAULT 1,
                FOREIGN KEY (idProduto) REFERENCES produto(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL;
        $this->execute( $sql );
    }

    public function down(): void {
        $this->execute( 'DROP TABLE item' );
    }
}
