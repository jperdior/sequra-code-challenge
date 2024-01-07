<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240106235955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX unique_monthly_fee ON merchant_monthly_fee');
        $this->addSql('ALTER TABLE merchant_monthly_fee CHANGE month first_day_of_month DATETIME NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX unique_monthly_fee ON merchant_monthly_fee (merchant_reference, first_day_of_month)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX unique_monthly_fee ON merchant_monthly_fee');
        $this->addSql('ALTER TABLE merchant_monthly_fee CHANGE first_day_of_month month DATETIME NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX unique_monthly_fee ON merchant_monthly_fee (merchant_reference, month)');
    }
}
