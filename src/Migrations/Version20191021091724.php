<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191021091724 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE notifications ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE comercial_muro_estados DROP FOREIGN KEY FK_30AE2E7D31075EBA');
        $this->addSql('ALTER TABLE comercial_muro_estados DROP FOREIGN KEY FK_30AE2E7DC420BE18');
        $this->addSql('DROP INDEX IDX_30AE2E7D31075EBA ON comercial_muro_estados');
        $this->addSql('DROP INDEX IDX_30AE2E7DC420BE18 ON comercial_muro_estados');
        $this->addSql('ALTER TABLE comercial_muro_estados CHANGE missatge missatge_id BIGINT DEFAULT NULL, CHANGE autor autor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comercial_muro_estados ADD CONSTRAINT FK_30AE2E7D355C94C2 FOREIGN KEY (missatge_id) REFERENCES comercial_muro (id)');
        $this->addSql('ALTER TABLE comercial_muro_estados ADD CONSTRAINT FK_30AE2E7D14D45BBE FOREIGN KEY (autor_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_30AE2E7D355C94C2 ON comercial_muro_estados (missatge_id)');
        $this->addSql('CREATE INDEX IDX_30AE2E7D14D45BBE ON comercial_muro_estados (autor_id)');
        $this->addSql('ALTER TABLE notifications_to DROP FOREIGN KEY FK_65F55515FE54D947');
        $this->addSql('DROP INDEX IDX_65F55515FE54D947 ON notifications_to');
        $this->addSql('ALTER TABLE notifications_to ADD group_message TINYINT(1) NOT NULL, DROP group_id, CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE notification_id notification_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('DROP INDEX nif_contacto_2 ON oficina_contactos');
        $this->addSql('DROP INDEX nif_contacto ON oficina_contactos');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comercial_muro_estados DROP FOREIGN KEY FK_30AE2E7D355C94C2');
        $this->addSql('ALTER TABLE comercial_muro_estados DROP FOREIGN KEY FK_30AE2E7D14D45BBE');
        $this->addSql('DROP INDEX IDX_30AE2E7D355C94C2 ON comercial_muro_estados');
        $this->addSql('DROP INDEX IDX_30AE2E7D14D45BBE ON comercial_muro_estados');
        $this->addSql('ALTER TABLE comercial_muro_estados CHANGE missatge_id missatge BIGINT DEFAULT NULL, CHANGE autor_id autor INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comercial_muro_estados ADD CONSTRAINT FK_30AE2E7D31075EBA FOREIGN KEY (autor) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comercial_muro_estados ADD CONSTRAINT FK_30AE2E7DC420BE18 FOREIGN KEY (missatge) REFERENCES comercial_muro (id)');
        $this->addSql('CREATE INDEX IDX_30AE2E7D31075EBA ON comercial_muro_estados (autor)');
        $this->addSql('CREATE INDEX IDX_30AE2E7DC420BE18 ON comercial_muro_estados (missatge)');
        $this->addSql('ALTER TABLE notifications DROP updated_at, CHANGE created_at created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE notifications_to ADD group_id INT DEFAULT NULL, DROP group_message, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE notification_id notification_id INT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE notifications_to ADD CONSTRAINT FK_65F55515FE54D947 FOREIGN KEY (group_id) REFERENCES groups (id)');
        $this->addSql('CREATE INDEX IDX_65F55515FE54D947 ON notifications_to (group_id)');
        $this->addSql('CREATE INDEX nif_contacto_2 ON oficina_contactos (nif_contacto)');
        $this->addSql('CREATE INDEX nif_contacto ON oficina_contactos (nif_contacto)');
    }
}
