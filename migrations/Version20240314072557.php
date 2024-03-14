<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240314072557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'dinosaur';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE dinosaur_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE dinosaur (id INT NOT NULL, name VARCHAR(255) NOT NULL, genus VARCHAR(128) NOT NULL, length INT NOT NULL, enclosure VARCHAR(128) NOT NULL, health VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
    }
}
