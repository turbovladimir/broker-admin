<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424083949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE offer_checker_relation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE offer_checker_relation (id INT NOT NULL, offer_id INT DEFAULT NULL, external_offer_id INT NOT NULL, checker VARCHAR(50) NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1BB8690953C674EE ON offer_checker_relation (offer_id)');
        $this->addSql('ALTER TABLE offer_checker_relation ADD CONSTRAINT FK_1BB8690953C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE offer_checker_relation_id_seq CASCADE');
        $this->addSql('ALTER TABLE offer_checker_relation DROP CONSTRAINT FK_1BB8690953C674EE');
        $this->addSql('DROP TABLE offer_checker_relation');
    }
}
