<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260923095400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE provincia (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE contacto ADD provincia_contacto_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contacto ADD CONSTRAINT FK_2741493C64C76A2A FOREIGN KEY (provincia_contacto_id) REFERENCES provincia (id)');
        $this->addSql('CREATE INDEX IDX_2741493C64C76A2A ON contacto (provincia_contacto_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE provincia');
        $this->addSql('ALTER TABLE contacto DROP FOREIGN KEY FK_2741493C64C76A2A');
        $this->addSql('DROP INDEX IDX_2741493C64C76A2A ON contacto');
        $this->addSql('ALTER TABLE contacto DROP provincia_contacto_id');
    }
}
