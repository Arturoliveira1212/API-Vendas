<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CriaTabelaItemPedido extends AbstractMigration {

    public function up(): void {
        $sql = <<<'SQL'
            CREATE TABLE item_pedido (
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                idItem INT NOT NULL,
                valorVenda FLOAT(7,2) NOT NULL,
                quantidade INT NOT NULL DEFAULT 0,
                ativo INT DEFAULT 1,
                FOREIGN KEY (idItem) REFERENCES item(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL;
        $this->execute( $sql );
    }

    public function down(): void {
        $this->execute( 'DROP TABLE item_pedido' );
    }
}
