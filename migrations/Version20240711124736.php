<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240711124736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE distribution_job_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE distribution_job (id INT NOT NULL, queue_id INT DEFAULT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, settings JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2B6979B5477B5BAE ON distribution_job (queue_id)');
        $this->addSql('ALTER TABLE distribution_job ADD CONSTRAINT FK_2B6979B5477B5BAE FOREIGN KEY (queue_id) REFERENCES sms_queue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE distribution_job_id_seq CASCADE');
        $this->addSql('ALTER TABLE distribution_job DROP CONSTRAINT FK_2B6979B5477B5BAE');
        $this->addSql('DROP TABLE distribution_job');
    }
}
