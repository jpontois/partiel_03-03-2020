<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200303105137 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, birth_date DATETIME DEFAULT NULL, creation_date DATETIME NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, plateform VARCHAR(255) NOT NULL, description VARCHAR(5000) NOT NULL, release_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE article_entity');
        $this->addSql('DROP TABLE user_entity');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, plateform VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(5000) NOT NULL COLLATE utf8mb4_unicode_ci, release_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_entity (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, first_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, last_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, birth_date DATETIME DEFAULT NULL, creation_date DATETIME NOT NULL, roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:simple_array)\', UNIQUE INDEX UNIQ_6B7A5F55E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE article');
    }
}
