<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231219093610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cancellation_line (id VARCHAR(26) NOT NULL, disbursement_id VARCHAR(26) DEFAULT NULL, disbursement_line_id VARCHAR(26) DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_1CDF7DAF3BA61A01 (disbursement_id), INDEX IDX_1CDF7DAF78FD49FA (disbursement_line_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cancellation_line ADD CONSTRAINT FK_1CDF7DAF3BA61A01 FOREIGN KEY (disbursement_id) REFERENCES disbursement (id)');
        $this->addSql('ALTER TABLE cancellation_line ADD CONSTRAINT FK_1CDF7DAF78FD49FA FOREIGN KEY (disbursement_line_id) REFERENCES disbursement_line (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cancellation_line DROP FOREIGN KEY FK_1CDF7DAF3BA61A01');
        $this->addSql('ALTER TABLE cancellation_line DROP FOREIGN KEY FK_1CDF7DAF78FD49FA');
        $this->addSql('DROP TABLE cancellation_line');
    }
}
