<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191029155004 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('RENAME TABLE contact TO backup_contact');
        $this->addSql('RENAME TABLE view_personas TO backup_view_personas');

        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, kind_id INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, nif VARCHAR(20) NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(512) DEFAULT NULL, phone VARCHAR(15) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_4C62E638ADE62BBB (nif), INDEX IDX_4C62E63830602CA9 (kind_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_kind (id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_133B3E265E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E63830602CA9 FOREIGN KEY (kind_id) REFERENCES contact_kind (id)');
        $this->addSql('CREATE INDEX nif_idx ON maestra_cliente (nif)');
        $this->addSql('CREATE INDEX nif_idx ON maestra_proveedor (nif)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E63830602CA9');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contact_kind');
        $this->addSql('DROP INDEX nif_idx ON maestra_cliente');
        $this->addSql('DROP INDEX nif_idx ON maestra_proveedor');

        $this->addSql('RENAME TABLE backup_contact TO contact');
        $this->addSql('RENAME TABLE backup_view_personas TO view_personas');
    }
}
