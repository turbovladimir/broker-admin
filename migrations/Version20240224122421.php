<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240224122421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE offer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE offer (id INT NOT NULL, name VARCHAR(255) NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_active BOOLEAN NOT NULL, logo VARCHAR(255) NOT NULL, max_loan_amount INT NOT NULL, interest_rate DOUBLE PRECISION NOT NULL, min_loan_period_days INT NOT NULL, max_loan_period_days INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE offer_id_seq CASCADE');
        $this->addSql('DROP TABLE offer');
    }
}
