<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231201003058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE disbursement (id VARCHAR(26) NOT NULL, merchant_id VARCHAR(36) DEFAULT NULL, fees DOUBLE PRECISION NOT NULL, amount DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, disbursed_at DATETIME NOT NULL, INDEX IDX_7C96A2A16796D554 (merchant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disbursement_line (id VARCHAR(26) NOT NULL, disbursement_id VARCHAR(26) DEFAULT NULL, purchase_id VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, fee_percentage DOUBLE PRECISION NOT NULL, fee_amount DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_5C0051FD3BA61A01 (disbursement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE merchant (id VARCHAR(36) NOT NULL, reference VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, live_on DATETIME NOT NULL, disbursement_frequency INT NOT NULL, minimum_monthly_fee DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE disbursement ADD CONSTRAINT FK_7C96A2A16796D554 FOREIGN KEY (merchant_id) REFERENCES merchant (id)');
        $this->addSql('ALTER TABLE disbursement_line ADD CONSTRAINT FK_5C0051FD3BA61A01 FOREIGN KEY (disbursement_id) REFERENCES disbursement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disbursement DROP FOREIGN KEY FK_7C96A2A16796D554');
        $this->addSql('ALTER TABLE disbursement_line DROP FOREIGN KEY FK_5C0051FD3BA61A01');
        $this->addSql('DROP TABLE disbursement');
        $this->addSql('DROP TABLE disbursement_line');
        $this->addSql('DROP TABLE merchant');
    }
}
