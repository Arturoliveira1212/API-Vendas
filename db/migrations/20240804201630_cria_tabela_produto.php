<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CriaTabelaProduto extends AbstractMigration {

    public function up(): void {
        $sql = <<<'SQL'
            CREATE TABLE produto (
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                referencia VARCHAR(15) NOT NULL,
                nome VARCHAR(100) NOT NULL,
                descricao VARCHAR(350) NOT NULL,
                peso FLOAT(7,2) NOT NULL,
                idCategoria INT DEFAULT NULL,
                caminhoImagem VARCHAR(200) DEFAULT NULL,
                dataCadastro DATETIME NOT NULL,
                ativo INT DEFAULT 1,
                FOREIGN KEY (idCategoria) REFERENCES categoria(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL;
        $this->execute( $sql );
    }

    public function down(): void {
        $this->execute( 'DROP TABLE produto' );
    }
}
