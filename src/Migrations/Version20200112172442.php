<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\Uuid;

final class Version20200112172442 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->createTable('article');
        $table->addColumn('id', UuidType::NAME);
        $table->addColumn('title', Types::STRING, ['length' => 100]);
        $table->addColumn('content', Types::TEXT, ['notnull' => true]);

        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('article');
    }
}
