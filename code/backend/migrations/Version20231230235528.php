<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230235528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE disbursement (reference VARCHAR(26) NOT NULL, merchant_reference VARCHAR(26) NOT NULL, created_at DATETIME NOT NULL, fee DOUBLE PRECISION NOT NULL, amount DOUBLE PRECISION NOT NULL, monthly_fee DOUBLE PRECISION NOT NULL, disbursed_at DATETIME NOT NULL, UNIQUE INDEX merchant_disburse_date (merchant_reference, disbursed_at), PRIMARY KEY(reference)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disbursement_line (id VARCHAR(26) NOT NULL, disbursement_reference VARCHAR(26) NOT NULL, created_at DATETIME NOT NULL, purchase_id VARCHAR(255) NOT NULL, purchase_amount DOUBLE PRECISION NOT NULL, fee_percentage INT NOT NULL, fee_amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE merchant (reference VARCHAR(36) NOT NULL, live_on DATETIME NOT NULL, disbursement_frequency VARCHAR(255) NOT NULL, minimum_monthly_fee DOUBLE PRECISION NOT NULL, PRIMARY KEY(reference)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE disbursement');
        $this->addSql('DROP TABLE disbursement_line');
        $this->addSql('DROP TABLE merchant');
    }
}
