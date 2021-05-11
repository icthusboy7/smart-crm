<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190930160545 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comercial_task DROP FOREIGN KEY FK_5C820CC586251BB0');
        $this->addSql('DROP INDEX UNIQ_5C820CC586251BB0 ON comercial_task');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comercial_task ADD CONSTRAINT FK_5C820CC586251BB0 FOREIGN KEY (comercial_muro_id) REFERENCES comercial_muro (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5C820CC586251BB0 ON comercial_task (comercial_muro_id)');
    }
}
