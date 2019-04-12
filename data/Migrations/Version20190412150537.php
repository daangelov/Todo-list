<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190412150537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This is a migration that creates a task table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('task');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('title', 'string', ['notnull' => true, 'length'=>128]);
        $table->addColumn('completed', 'boolean', ['notnull' => true]);
        $table->addColumn('created_on', 'datetime', ['notnull' => true]);
        $table->addColumn('updated_on', 'datetime', ['notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine', 'InnoDB');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('task');
    }
}
