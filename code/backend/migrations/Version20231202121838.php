<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231202121838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE disbursement (id VARCHAR(26) NOT NULL, merchant_id VARCHAR(36) DEFAULT NULL, reference VARCHAR(26) NOT NULL, fees DOUBLE PRECISION NOT NULL, amount DOUBLE PRECISION NOT NULL, monthly_fee DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, disbursed_at DATE NOT NULL, INDEX IDX_7C96A2A16796D554 (merchant_id), UNIQUE INDEX merchant_disburse_date (merchant_id, disbursed_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disbursement_line (id VARCHAR(26) NOT NULL, purchase_id VARCHAR(36) DEFAULT NULL, disbursement_id VARCHAR(26) DEFAULT NULL, purchase_amount DOUBLE PRECISION NOT NULL, amount DOUBLE PRECISION NOT NULL, fee_percentage DOUBLE PRECISION NOT NULL, fee_amount DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5C0051FD558FBEB9 (purchase_id), INDEX IDX_5C0051FD3BA61A01 (disbursement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE merchant (id VARCHAR(36) NOT NULL, reference VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, live_on DATETIME NOT NULL, disbursement_frequency INT NOT NULL, minimum_monthly_fee DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase (id VARCHAR(36) NOT NULL, merchant_id VARCHAR(36) DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_6117D13B6796D554 (merchant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE disbursement ADD CONSTRAINT FK_7C96A2A16796D554 FOREIGN KEY (merchant_id) REFERENCES merchant (id)');
        $this->addSql('ALTER TABLE disbursement_line ADD CONSTRAINT FK_5C0051FD558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id)');
        $this->addSql('ALTER TABLE disbursement_line ADD CONSTRAINT FK_5C0051FD3BA61A01 FOREIGN KEY (disbursement_id) REFERENCES disbursement (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B6796D554 FOREIGN KEY (merchant_id) REFERENCES merchant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disbursement DROP FOREIGN KEY FK_7C96A2A16796D554');
        $this->addSql('ALTER TABLE disbursement_line DROP FOREIGN KEY FK_5C0051FD558FBEB9');
        $this->addSql('ALTER TABLE disbursement_line DROP FOREIGN KEY FK_5C0051FD3BA61A01');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B6796D554');
        $this->addSql('DROP TABLE disbursement');
        $this->addSql('DROP TABLE disbursement_line');
        $this->addSql('DROP TABLE merchant');
        $this->addSql('DROP TABLE purchase');
    }
}
