<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200303113212 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD login VARCHAR(255) NOT NULL, DROP email, DROP first_name, DROP last_name, DROP birth_date, DROP creation_date');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649AA08CB10 ON user (login)');
        $this->addSql('ALTER TABLE article ADD author VARCHAR(255) NOT NULL, ADD validated TINYINT(1) NOT NULL, ADD published TINYINT(1) NOT NULL, ADD publication_date DATETIME NOT NULL, DROP name, DROP plateform, DROP release_date, CHANGE description content VARCHAR(5000) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article ADD plateform VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD release_date DATETIME DEFAULT NULL, DROP validated, DROP published, DROP publication_date, CHANGE author name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE content description VARCHAR(5000) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('DROP INDEX UNIQ_8D93D649AA08CB10 ON user');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD last_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD birth_date DATETIME DEFAULT NULL, ADD creation_date DATETIME NOT NULL, CHANGE login email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
