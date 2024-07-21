<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240629155849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE contact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sending_sms_job_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sms_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sms_queue_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contact (id INT NOT NULL , queue_id INT DEFAULT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(100) DEFAULT NULL, phone VARCHAR(20) NOT NULL, contact_id VARCHAR(50) NOT NULL, source VARCHAR(20) not null, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E638E7A1254A ON contact (contact_id)');
        $this->addSql('CREATE INDEX IDX_4C62E638477B5BAE ON contact (queue_id)');
        $this->addSql('CREATE TABLE sending_sms_job (id INT NOT NULL, contact_id INT NOT NULL, sms_queue_id INT NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sending_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(10) NOT NULL, error_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_71128A5E7A1254A ON sending_sms_job (contact_id)');
        $this->addSql('CREATE INDEX IDX_71128A5218D7AAF ON sending_sms_job (sms_queue_id)');
        $this->addSql('CREATE TABLE sms (id INT NOT NULL, job_id INT NOT NULL, sms_queue_id INT NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B0A93A77BE04EA9 ON sms (job_id)');
        $this->addSql('CREATE INDEX IDX_B0A93A77218D7AAF ON sms (sms_queue_id)');
        $this->addSql('CREATE TABLE sms_queue (id INT NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(20) NOT NULL, file_path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638477B5BAE FOREIGN KEY (queue_id) REFERENCES sms_queue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sending_sms_job ADD CONSTRAINT FK_71128A5E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sending_sms_job ADD CONSTRAINT FK_71128A5218D7AAF FOREIGN KEY (sms_queue_id) REFERENCES sms_queue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sms ADD CONSTRAINT FK_B0A93A77BE04EA9 FOREIGN KEY (job_id) REFERENCES sending_sms_job (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sms ADD CONSTRAINT FK_B0A93A77218D7AAF FOREIGN KEY (sms_queue_id) REFERENCES sms_queue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE loan_request DROP department_code');
        $this->addSql('ALTER TABLE loan_request DROP issue_date');
        $this->addSql('ALTER TABLE loan_request DROP passport_series');
        $this->addSql('ALTER TABLE loan_request DROP passport_number');
        $this->addSql('ALTER TABLE loan_request DROP department');
        $this->addSql('ALTER TABLE loan_request DROP reg_place');
        $this->addSql('ALTER TABLE loan_request ALTER birth_place SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE contact_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sending_sms_job_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sms_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sms_queue_id_seq CASCADE');
        $this->addSql('ALTER TABLE contact DROP CONSTRAINT FK_4C62E638477B5BAE');
        $this->addSql('ALTER TABLE sending_sms_job DROP CONSTRAINT FK_71128A5E7A1254A');
        $this->addSql('ALTER TABLE sending_sms_job DROP CONSTRAINT FK_71128A5218D7AAF');
        $this->addSql('ALTER TABLE sms DROP CONSTRAINT FK_B0A93A77BE04EA9');
        $this->addSql('ALTER TABLE sms DROP CONSTRAINT FK_B0A93A77218D7AAF');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE sending_sms_job');
        $this->addSql('DROP TABLE sms');
        $this->addSql('DROP TABLE sms_queue');
        $this->addSql('ALTER TABLE loan_request ADD department_code INT  NULL');
        $this->addSql('ALTER TABLE loan_request ADD issue_date DATE NULL');
        $this->addSql('ALTER TABLE loan_request ADD passport_series INT  NULL');
        $this->addSql('ALTER TABLE loan_request ADD passport_number INT  NULL');
        $this->addSql('ALTER TABLE loan_request ADD department VARCHAR(255)  NULL');
        $this->addSql('ALTER TABLE loan_request ADD reg_place VARCHAR(255)  NULL');
        $this->addSql('ALTER TABLE loan_request ALTER birth_place DROP NOT NULL');
    }
}
