<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408073342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE phone_verify_job_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE phone_verify_job (id INT NOT NULL, session_id VARCHAR(50) NOT NULL, phone INT NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_active BOOLEAN NOT NULL, code INT DEFAULT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE loan_request ALTER phone TYPE INT USING (phone::integer)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE phone_verify_job_id_seq CASCADE');
        $this->addSql('DROP TABLE phone_verify_job');
        $this->addSql('ALTER TABLE loan_request ALTER phone TYPE VARCHAR(20)');
    }
}
