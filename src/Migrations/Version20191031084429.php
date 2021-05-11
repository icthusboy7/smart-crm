<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191031084429 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE comercial_muro_estados');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comercial_muro_estados (id BIGINT AUTO_INCREMENT NOT NULL, missatge_id BIGINT DEFAULT NULL, autor_id INT DEFAULT NULL, estado VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, createdAt DATETIME NOT NULL, INDEX IDX_30AE2E7D14D45BBE (autor_id), INDEX IDX_30AE2E7D355C94C2 (missatge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comercial_muro_estados ADD CONSTRAINT FK_30AE2E7D14D45BBE FOREIGN KEY (autor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comercial_muro_estados ADD CONSTRAINT FK_30AE2E7D355C94C2 FOREIGN KEY (missatge_id) REFERENCES comercial_muro (id)');
    }
}
