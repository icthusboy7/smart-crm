<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191119152416 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comercial_alertas (id BIGINT AUTO_INCREMENT NOT NULL, expediente_id BIGINT DEFAULT NULL, horizontal_id INT DEFAULT NULL, vertical_id INT DEFAULT NULL, missatge LONGTEXT NOT NULL, nivel INT NOT NULL, active TINYINT(1) NOT NULL, is_alert TINYINT(1) NOT NULL, deleted INT DEFAULT NULL, deletedby VARCHAR(255) DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, cotizacion VARCHAR(20) DEFAULT NULL, personanif VARCHAR(20) DEFAULT NULL, oficina VARCHAR(255) DEFAULT NULL, INDEX IDX_6AFD40894BF37E4E (expediente_id), INDEX IDX_6AFD4089E120F117 (horizontal_id), INDEX IDX_6AFD4089607DECF7 (vertical_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comercial_alertas_filters (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, query VARCHAR(255) NOT NULL, INDEX IDX_7ECE0853A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commercial_office (office VARCHAR(255) NOT NULL, commercial VARCHAR(8) NOT NULL, horizontal VARCHAR(255) NOT NULL, PRIMARY KEY(office, commercial, horizontal)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commercial_responsable (commercial VARCHAR(8) NOT NULL, responsable VARCHAR(8) NOT NULL, UNIQUE INDEX UNIQ_5C935E867653F3AE (commercial), PRIMARY KEY(commercial, responsable)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_filters (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, query VARCHAR(255) NOT NULL, INDEX IDX_E258460CA76ED395 (user_id), INDEX IDX_E258460C642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gestor_horizontal (gestor VARCHAR(8) NOT NULL, horizontal VARCHAR(255) NOT NULL, PRIMARY KEY(gestor, horizontal)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gestor_responsable (gestor VARCHAR(8) NOT NULL, responsable VARCHAR(8) NOT NULL, PRIMARY KEY(gestor, responsable)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groups_user (groups_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F0F44878F373DCF (groups_id), INDEX IDX_F0F44878A76ED395 (user_id), PRIMARY KEY(groups_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE horizontal (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5303044E5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maestra_cliente (id INT AUTO_INCREMENT NOT NULL, comunidadAutonoma VARCHAR(255) DEFAULT NULL, codigoLinea BIGINT DEFAULT NULL, codigoOficinaProcedencia INT DEFAULT NULL, codigoPostal INT DEFAULT NULL, disponibleInversionLinea INT DEFAULT NULL, dispuestoInversionLinea INT DEFAULT NULL, estadoLinea VARCHAR(255) DEFAULT NULL, grupo VARCHAR(255) DEFAULT NULL, grupoJuridico VARCHAR(255) DEFAULT NULL, limiteInversionLinea INT DEFAULT NULL, nif VARCHAR(255) DEFAULT NULL, nombre VARCHAR(255) DEFAULT NULL, poblacion VARCHAR(255) DEFAULT NULL, provincia VARCHAR(255) DEFAULT NULL, tipo_cartera_cxb VARCHAR(255) DEFAULT NULL, tipo_gestor_cxb INT DEFAULT NULL, createdAt DATETIME DEFAULT NULL, INDEX nif_idx (nif), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maestra_empleados_cxb (id INT AUTO_INCREMENT NOT NULL, apellido1 VARCHAR(255) DEFAULT NULL, apellido2 VARCHAR(255) DEFAULT NULL, cargo VARCHAR(255) DEFAULT NULL, codigoOficina VARCHAR(255) DEFAULT NULL, idCargo VARCHAR(255) DEFAULT NULL, idResp VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, nombre VARCHAR(255) DEFAULT NULL, nombreGestor VARCHAR(255) DEFAULT NULL, numEmpleado VARCHAR(255) DEFAULT NULL, resp VARCHAR(255) DEFAULT NULL, tipoCartera VARCHAR(255) DEFAULT NULL, tipoGestor VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maestra_familia (id INT AUTO_INCREMENT NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, id_familia VARCHAR(255) DEFAULT NULL, man_dt VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maestra_familia_rel (id INT AUTO_INCREMENT NOT NULL, derf VARCHAR(255) DEFAULT NULL, id_familia VARCHAR(255) DEFAULT NULL, id_producto VARCHAR(255) DEFAULT NULL, man_dt VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maestra_subfamilia (id INT AUTO_INCREMENT NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, grupo VARCHAR(255) DEFAULT NULL, id_subfamilia VARCHAR(255) DEFAULT NULL, id_tipo_maquina VARCHAR(255) DEFAULT NULL, tipo_vehiculo_ind VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maestra_oficina (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(255) NOT NULL, nombre VARCHAR(255) DEFAULT NULL, tipo VARCHAR(255) DEFAULT NULL, dt VARCHAR(255) DEFAULT NULL, dg VARCHAR(255) DEFAULT NULL, dan VARCHAR(255) DEFAULT NULL, domicilio VARCHAR(255) DEFAULT NULL, cp VARCHAR(255) DEFAULT NULL, poblacion VARCHAR(255) DEFAULT NULL, provincia VARCHAR(255) DEFAULT NULL, comunidad VARCHAR(255) DEFAULT NULL, pais VARCHAR(255) DEFAULT NULL, telefono1 VARCHAR(255) DEFAULT NULL, telefono2 VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, categoria VARCHAR(255) DEFAULT NULL, centroActualActivoCounter INT DEFAULT NULL, centroActualActivo INT DEFAULT NULL, codigoSubtipo VARCHAR(255) DEFAULT NULL, codigoTipo VARCHAR(255) DEFAULT NULL, directorActual VARCHAR(255) DEFAULT NULL, gestorActual VARCHAR(255) DEFAULT NULL, fechaAlta VARCHAR(255) DEFAULT NULL, fechaBaja VARCHAR(255) DEFAULT NULL, segmentoComercial VARCHAR(255) DEFAULT NULL, subddirectorActual VARCHAR(255) DEFAULT NULL, observaciones VARCHAR(1500) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maestra_proveedor (id INT AUTO_INCREMENT NOT NULL, nif VARCHAR(255) DEFAULT NULL, nombre VARCHAR(255) DEFAULT NULL, codigo_acreedor INT DEFAULT NULL, codigo_comercial_oficina_actual INT DEFAULT NULL, fecha_carterizacion VARCHAR(255) DEFAULT NULL, grupo_empresas VARCHAR(255) DEFAULT NULL, oficina_prescriptor VARCHAR(255) DEFAULT NULL, prescriptor_primario VARCHAR(255) DEFAULT NULL, prescriptor_primario_nombre VARCHAR(255) DEFAULT NULL, tipo_prescriptor VARCHAR(255) DEFAULT NULL, tipo_prescriptor_detalle VARCHAR(255) DEFAULT NULL, carterizado VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, INDEX nif_idx (nif), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maestra_cotizacion (id INT AUTO_INCREMENT NOT NULL, canal VARCHAR(255) DEFAULT NULL, cuotas VARCHAR(255) DEFAULT NULL, codigoCoti INT NOT NULL, codigoCampanya VARCHAR(255) DEFAULT NULL, codigoOficinaAlta VARCHAR(255) DEFAULT NULL, dictamenTecnico VARCHAR(255) DEFAULT NULL, estado VARCHAR(255) DEFAULT NULL, estadoSIA VARCHAR(255) DEFAULT NULL, fecha VARCHAR(255) DEFAULT NULL, financiacionOperacion DOUBLE PRECISION DEFAULT NULL, financiacionServicios DOUBLE PRECISION DEFAULT NULL, gestionInventario VARCHAR(1) DEFAULT NULL, idFamiliaBBEE VARCHAR(255) DEFAULT NULL, idSubfamiliaBBEE VARCHAR(255) DEFAULT NULL, idSolicitud VARCHAR(255) DEFAULT NULL, inversion DOUBLE PRECISION DEFAULT NULL, lineaProv VARCHAR(255) DEFAULT NULL, margenFinanciero DOUBLE PRECISION DEFAULT NULL, matriculado VARCHAR(1) DEFAULT NULL, matriculadoCXRProveedor VARCHAR(255) DEFAULT NULL, nifCliente VARCHAR(255) DEFAULT NULL, nifPactoRecompra VARCHAR(255) DEFAULT NULL, nifPrescriptor VARCHAR(255) DEFAULT NULL, nombreCliente VARCHAR(255) DEFAULT NULL, contrato VARCHAR(255) DEFAULT NULL, plazo INT DEFAULT NULL, producto VARCHAR(255) DEFAULT NULL, riesgoTecnico VARCHAR(255) DEFAULT NULL, sia VARCHAR(255) DEFAULT NULL, tir DOUBLE PRECISION DEFAULT NULL, totalCuota VARCHAR(255) DEFAULT NULL, valorResidual VARCHAR(255) DEFAULT NULL, version VARCHAR(255) DEFAULT NULL, vertical VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_5F4CC9EAB8BFFB9 (codigoCoti), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maestra_solicitud (id INT AUTO_INCREMENT NOT NULL, apoderado1 VARCHAR(255) DEFAULT NULL, apoderado2 VARCHAR(255) DEFAULT NULL, aval_prescriptor VARCHAR(1) DEFAULT NULL, avales VARCHAR(1) DEFAULT NULL, canal_solicitud VARCHAR(255) DEFAULT NULL, centro_sia VARCHAR(255) DEFAULT NULL, check_opcion_compra VARCHAR(1) DEFAULT NULL, check_pacto_recompra VARCHAR(1) DEFAULT NULL, circuito_operacion_detalle VARCHAR(255) DEFAULT NULL, circuito_operacion VARCHAR(255) DEFAULT NULL, confort_letter VARCHAR(1) DEFAULT NULL, contrato_telefonica VARCHAR(255) DEFAULT NULL, contrato_tfn VARCHAR(255) DEFAULT NULL, codigo_cotizacion VARCHAR(255) DEFAULT NULL, codigo_oficina_alta VARCHAR(255) DEFAULT NULL, motivo_cancelacion VARCHAR(255) DEFAULT NULL, submotivo_cancelacion VARCHAR(255) DEFAULT NULL, detalle_estado VARCHAR(255) DEFAULT NULL, detalle_forma VARCHAR(255) DEFAULT NULL, doc_padre VARCHAR(255) DEFAULT NULL, entrega_inicial VARCHAR(1) DEFAULT NULL, estado_actual_general VARCHAR(255) DEFAULT NULL, estado_actual_general_ajustado VARCHAR(255) DEFAULT NULL, estado_actual VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications_to (id BIGINT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, notification_id INT DEFAULT NULL, seen TINYINT(1) NOT NULL, flag_seen TINYINT(1) NOT NULL, group_message TINYINT(1) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_65F55515A76ED395 (user_id), INDEX IDX_65F55515EF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE office_filters (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, query VARCHAR(255) NOT NULL, INDEX IDX_C2B04CFA76ED395 (user_id), INDEX IDX_C2B04CF642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oficina_contactos (id INT AUTO_INCREMENT NOT NULL, codigo_oficina VARCHAR(255) NOT NULL, nif_contacto VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status_quotation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CFDF0DF85E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8CDE57295E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE widget_user_orden (widget_dashboard_id INT NOT NULL, user_id INT NOT NULL, orden INT NOT NULL, INDEX IDX_771763685DE098AA (widget_dashboard_id), INDEX IDX_77176368A76ED395 (user_id), PRIMARY KEY(widget_dashboard_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comercial_alertas ADD CONSTRAINT FK_6AFD40894BF37E4E FOREIGN KEY (expediente_id) REFERENCES comercial_expediente (id)');
        $this->addSql('ALTER TABLE comercial_alertas ADD CONSTRAINT FK_6AFD4089E120F117 FOREIGN KEY (horizontal_id) REFERENCES horizontal (id)');
        $this->addSql('ALTER TABLE comercial_alertas ADD CONSTRAINT FK_6AFD4089607DECF7 FOREIGN KEY (vertical_id) REFERENCES vertical (id)');
        $this->addSql('ALTER TABLE comercial_alertas_filters ADD CONSTRAINT FK_7ECE0853A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contact_filters ADD CONSTRAINT FK_E258460CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contact_filters ADD CONSTRAINT FK_E258460C642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE groups_user ADD CONSTRAINT FK_F0F44878F373DCF FOREIGN KEY (groups_id) REFERENCES groups (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groups_user ADD CONSTRAINT FK_F0F44878A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notifications_to ADD CONSTRAINT FK_65F55515A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notifications_to ADD CONSTRAINT FK_65F55515EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notifications (id)');
        $this->addSql('ALTER TABLE office_filters ADD CONSTRAINT FK_C2B04CFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE office_filters ADD CONSTRAINT FK_C2B04CF642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE widget_user_orden ADD CONSTRAINT FK_771763685DE098AA FOREIGN KEY (widget_dashboard_id) REFERENCES dashboard_widgets (id)');
        $this->addSql('ALTER TABLE widget_user_orden ADD CONSTRAINT FK_77176368A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
//        $this->addSql('DROP TABLE admin_tmp');
//        $this->addSql('DROP TABLE column_map');
//        $this->addSql('DROP TABLE comercial_muro_tmp');
//        $this->addSql('DROP TABLE contact_aux');
//        $this->addSql('DROP TABLE id_tmp');
//        $this->addSql('DROP TABLE table_map');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comercial_alertas DROP FOREIGN KEY FK_6AFD4089E120F117');
        $this->addSql('ALTER TABLE notifications_to DROP FOREIGN KEY FK_65F55515EF1A9D84');
//        $this->addSql('CREATE TABLE admin_tmp (id LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, role_id LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, regNumber LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, regNumberCaixabank LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, password LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, name LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, surname LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, salt LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, isActive LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, timesLogged LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, createdAt LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, lastLoginAt LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, preLastLoginAt LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, email LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, error_message LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = MyISAM COMMENT = \'\' ');
//        $this->addSql('CREATE TABLE column_map (table_name VARCHAR(64) DEFAULT \'\' NOT NULL COLLATE utf8_general_ci, column_name VARCHAR(64) DEFAULT \'\' NOT NULL COLLATE utf8_general_ci, column_name_origin VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = MyISAM COMMENT = \'\' ');
//        $this->addSql('CREATE TABLE comercial_muro_tmp (id LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, autor LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, responsable LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, tipo LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, missatge LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, visto LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, nivel LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, grupo LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, expediente LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, cotizacion LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, cerrado_por LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, createdAt LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, updatedAt LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, tarea_noti_id LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, motivo_canc LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, pet_linea_id LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci, error_message LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = MyISAM COMMENT = \'\' ');
//        $this->addSql('CREATE TABLE contact_aux (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, nif VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, address VARCHAR(512) DEFAULT NULL COLLATE utf8mb4_unicode_ci, phone VARCHAR(15) DEFAULT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_4C62E638ADE62BBBAUX (nif), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
//        $this->addSql('CREATE TABLE id_tmp (id INT DEFAULT 0 NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = MyISAM COMMENT = \'\' ');
//        $this->addSql('CREATE TABLE table_map (table_name VARCHAR(64) DEFAULT \'\' NOT NULL COLLATE utf8_general_ci, table_name_origin VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, table_order INT DEFAULT NULL) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('DROP TABLE comercial_alertas');
        $this->addSql('DROP TABLE comercial_alertas_filters');
        $this->addSql('DROP TABLE commercial_office');
        $this->addSql('DROP TABLE commercial_responsable');
        $this->addSql('DROP TABLE contact_filters');
        $this->addSql('DROP TABLE gestor_horizontal');
        $this->addSql('DROP TABLE gestor_responsable');
        $this->addSql('DROP TABLE groups_user');
        $this->addSql('DROP TABLE horizontal');
        $this->addSql('DROP TABLE maestra_cliente');
        $this->addSql('DROP TABLE maestra_empleados_cxb');
        $this->addSql('DROP TABLE maestra_familia');
        $this->addSql('DROP TABLE maestra_familia_rel');
        $this->addSql('DROP TABLE maestra_subfamilia');
        $this->addSql('DROP TABLE maestra_oficina');
        $this->addSql('DROP TABLE maestra_proveedor');
        $this->addSql('DROP TABLE maestra_cotizacion');
        $this->addSql('DROP TABLE maestra_solicitud');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE notifications_to');
        $this->addSql('DROP TABLE office_filters');
        $this->addSql('DROP TABLE oficina_contactos');
        $this->addSql('DROP TABLE status_quotation');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE widget_user_orden');
    }
}
