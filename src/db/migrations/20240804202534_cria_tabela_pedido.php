<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CriaTabelaPedido extends AbstractMigration {

    public function up(): void {
        $sql = <<<'SQL'
            CREATE TABLE pedido (
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                idEndereco INT NOT NULL,
                idCliente INT NOT NULL,
                valorProdutos FLOAT(7,2) NOT NULL,
                valorDescontos FLOAT(7,2) NOT NULL,
                valorFrete FLOAT(7,2) NOT NULL,
                valorTotal FLOAT(7,2) NOT NULL,
                formaPagamento INT NOT NULL,
                ativo INT DEFAULT 1,
                FOREIGN KEY (idEndereco) REFERENCES endereco(id),
                FOREIGN KEY (idCliente) REFERENCES cliente(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL;
        $this->execute( $sql );
    }

    public function down(): void {
        $this->execute( 'DROP TABLE pedido' );
    }
}
