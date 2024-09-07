<?php

declare(strict_types=1);

namespace db\seeds;

use Phinx\Seed\AbstractSeed;

class Categoria extends AbstractSeed {

    public function getDependencies(): array {
        return [];
    }

    public function run(): void {
        $sql = <<<'SQL'
            INSERT INTO categoria ( nome, descricao ) VALUES
                ( 'Legging', 'Calças leggings modeladoras.' ),
                ( 'Calcinha', 'Calcinhas modeladoras.' ),
                ( 'Sutiã', 'Sutiã modeladores.' ),
                ( 'Blusa', 'blusas modeladoras.' );
        SQL;
        $this->execute( $sql );
    }
}