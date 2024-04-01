<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401061156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE push_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE push (id INT NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, uri VARCHAR(50) NOT NULL, target VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, show_delay_secs INT NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE loan_request ALTER department SET NOT NULL');
        $this->addSql('ALTER TABLE loan_request ALTER reg_place SET NOT NULL');
        $this->addSql('ALTER TABLE loan_request ALTER birth_place SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE push_id_seq CASCADE');
        $this->addSql('DROP TABLE push');
        $this->addSql('ALTER TABLE loan_request ALTER department DROP NOT NULL');
        $this->addSql('ALTER TABLE loan_request ALTER reg_place DROP NOT NULL');
        $this->addSql('ALTER TABLE loan_request ALTER birth_place DROP NOT NULL');
    }
}
