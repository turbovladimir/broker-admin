<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240320084919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE loan_request ADD added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE loan_request ADD passport_series INT NOT NULL');
        $this->addSql('ALTER TABLE loan_request ADD passport_number INT NOT NULL');
        $this->addSql('ALTER TABLE loan_request DROP pasport_series');
        $this->addSql('ALTER TABLE loan_request DROP pasport_number');
        $this->addSql('alter table loan_request drop column city;');
        $this->addSql('alter table loan_request drop column region;');
        $this->addSql('alter table loan_request add column department varchar(255);');
        $this->addSql('alter table loan_request add column birth_place varchar(100);');
        $this->addSql('alter table loan_request add column reg_place varchar(255);');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE loan_request ADD pasport_series INT NOT NULL');
        $this->addSql('ALTER TABLE loan_request ADD pasport_number INT NOT NULL');
        $this->addSql('ALTER TABLE loan_request DROP added_at');
        $this->addSql('ALTER TABLE loan_request DROP passport_series');
        $this->addSql('ALTER TABLE loan_request DROP passport_number');
    }
}
