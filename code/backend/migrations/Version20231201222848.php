<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231201222848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disbursement_line CHANGE purchase_id purchase_id VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE disbursement_line ADD CONSTRAINT FK_5C0051FD558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5C0051FD558FBEB9 ON disbursement_line (purchase_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disbursement_line DROP FOREIGN KEY FK_5C0051FD558FBEB9');
        $this->addSql('DROP INDEX UNIQ_5C0051FD558FBEB9 ON disbursement_line');
        $this->addSql('ALTER TABLE disbursement_line CHANGE purchase_id purchase_id VARCHAR(255) NOT NULL');
    }
}
