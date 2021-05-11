<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191010154631 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql(
            'CREATE OR REPLACE VIEW view_managers AS'.
            '  SELECT'.
            '    id,'.
            '    reg_number                   AS code,'.
            '    CONCAT(name, " ", surname)   AS name,'.
            '    createdAt                    AS created_at,'.
            '    updatedAt                    AS updated_at'.
            '  FROM `user`'.
            '  WHERE EXISTS ('.
            '    SELECT *'.
            '    FROM commercial_responsable'.
            '    WHERE responsable = reg_number'.
            '  )'
        );
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP VIEW IF EXISTS view_managers');
    }
}
