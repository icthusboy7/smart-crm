<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191203121104 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comercial_usuario (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, tipo_id INT DEFAULT NULL, responsable_id INT DEFAULT NULL, equipo_id INT DEFAULT NULL, INDEX IDX_F1F58D1CDB38439E (usuario_id), INDEX IDX_F1F58D1CA9276E6C (tipo_id), INDEX IDX_F1F58D1C53C59D72 (responsable_id), INDEX IDX_F1F58D1C23BFBED (equipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comercial_usuario ADD CONSTRAINT FK_F1F58D1CDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comercial_usuario ADD CONSTRAINT FK_F1F58D1CA9276E6C FOREIGN KEY (tipo_id) REFERENCES comercial_usuario_tipo (id)');
        $this->addSql('ALTER TABLE comercial_usuario ADD CONSTRAINT FK_F1F58D1C53C59D72 FOREIGN KEY (responsable_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comercial_usuario ADD CONSTRAINT FK_F1F58D1C23BFBED FOREIGN KEY (equipo_id) REFERENCES comercial_usuario_zona (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE comercial_usuario');
    }
}
