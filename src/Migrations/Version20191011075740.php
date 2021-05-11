<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191011075740 extends AbstractMigration
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
            'CREATE OR REPLACE VIEW view_events AS'.
            '  SELECT'.
            '    visit.id            AS `id`,'.
            '    reason.name         AS `title`,'.
            '    ('.
            '      CASE status_id'.
            '        WHEN 1 THEN \'pending\''.
            '        WHEN 2 THEN \'done\''.
            '        WHEN 3 THEN \'canceled\''.
            '      END'.
            '    )                   AS `class`,'.
            '    visit.author_id     AS `author_id`,'.
            '    visit.status_id     AS `status_id`,'.
            '    visit.date_ini      AS `start`,'.
            '    visit.date_fin      AS `end`'.
            '  FROM `visit`'.
            '  LEFT JOIN `reason`'.
            '    ON visit.reason_id = reason.id;'
        );
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP VIEW IF EXISTS view_events');
    }
}
