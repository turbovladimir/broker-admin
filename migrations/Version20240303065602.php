<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303065602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE loan_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE loan_request (id INT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, patron VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, birth DATE NOT NULL, email VARCHAR(30) NOT NULL, pasport_series INT NOT NULL, pasport_number INT NOT NULL, department_code INT NOT NULL, issue_date DATE NOT NULL, region VARCHAR(100) NOT NULL, city VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE offer ALTER is_active SET DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE loan_request_id_seq CASCADE');
        $this->addSql('DROP TABLE loan_request');
        $this->addSql('ALTER TABLE offer ALTER is_active DROP DEFAULT');
    }
}
