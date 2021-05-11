/*
***********************************
***** MIGRATION SS- A DESTINO *****
***********************************
 */

SET FOREIGN_KEY_CHECKS=1;
SET SQL_SAFE_UPDATES = 0;

/*
**********************
***** TIPO CANAL *****
**********************
 */
CREATE TABLE `smart`.`tipo_canal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `canal_desc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
***********************
***** VERTICAL ********
***********************
 */
CREATE TABLE `smart`.`vertical` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A876749C5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
*******************
***** REASON ******
*******************
 */
CREATE TABLE `smart`.`reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
******************
***** CHARGE *****
******************
 */
CREATE TABLE `smart`.`charge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_556BA4345E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
*******************
***** COMPANY *****
*******************
 */
CREATE TABLE `smart`.`company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `companyShort` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4FBF094FB7894CAA` (`companyName`),
  UNIQUE KEY `UNIQ_4FBF094F50A1D553` (`companyShort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
*************************
***** document_type *****
*************************
 */
CREATE TABLE `smart`.`document_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2B6ADBBA6DE44026` (`description`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
*******************
***** ROLE *****
*******************
 */
CREATE TABLE `smart`.`role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleName` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_57698A6A7184CB0` (`roleName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
*******************
***** groups ******
********SS+********
 */
CREATE TABLE `smart`.`groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `smart`.`groups` VALUES (1,'Grupo prueba','2019-03-13 00:00:00','2019-03-13 00:00:00'),(2,'Grupo dos','2019-03-13 00:00:00','2019-03-13 00:00:00');

/*
*******************
***** USER ********
*******************
 */
CREATE TABLE `smart`.`user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
   `username_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
   `email` varchar(180) COLLATE utf8mb4_unicode_ci NULL,
  `email_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `reg_number` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_number_caixabank` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `timesLogged` bigint(20) NOT NULL,
  `flagNotify` tinyint(1) NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_8D93D6492F237733` (`reg_number`),
  UNIQUE KEY `UNIQ_8D93D649C05FB297` (`confirmation_token`),
  KEY `IDX_8D93D649979B1AD6` (`company_id`),
  KEY `IDX_8D93D649D60322AC` (`role_id`),
  CONSTRAINT `FK_8D93D649979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  CONSTRAINT `FK_8D93D649D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `smart`.`user`
MODIFY `roles` longtext NULL COMMENT '(DC2Type:array)';

/* En SS- existen estos campos y en SS+ no existen*/
ALTER TABLE `smart`.`user`
ADD COLUMN `area_id_new` int(11) DEFAULT NULL AFTER `id`,
ADD COLUMN `preLastLoginAt_new` datetime DEFAULT NULL AFTER `email`;

/*
*******************
***** ADMIN *******
*******************
 */
CREATE TABLE `smart`.`admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `reg_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_number_caixabank` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `timesLogged` bigint(20) NOT NULL,
  `flagNotify` tinyint(1) NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_880E0D7692FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_880E0D76A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_880E0D762F237733` (`reg_number`),
  UNIQUE KEY `UNIQ_880E0D762F19AB11` (`reg_number_caixabank`),
  UNIQUE KEY `UNIQ_880E0D76C05FB297` (`confirmation_token`),
  KEY `IDX_880E0D76979B1AD6` (`company_id`),
  KEY `IDX_880E0D76D60322AC` (`role_id`),
  CONSTRAINT `FK_880E0D76979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  CONSTRAINT `FK_880E0D76D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `smart`.`admin`
DROP KEY `UNIQ_880E0D7692FC23A8`,
DROP KEY `UNIQ_880E0D76A0D96FBF`,
MODIFY `username` varchar(180) NULL,
MODIFY `username_canonical` varchar(180) NULL,
MODIFY `email_canonical` varchar(180) NULL,
MODIFY `roles` longtext NULL,
MODIFY `flagNotify` tinyint(1) NULL;

/* En SS- hay datos nulos y SS+ no permite este campo nulo*/
ALTER TABLE `smart`.`admin`
MODIFY `email` varchar(180) NULL;

/*En SS- hay datos con mayor tamaño que varchar(8) */
ALTER TABLE `smart`.`admin`
MODIFY `reg_number` varchar(255) NOT NULL;

/* Tratamiento de campo createdAt */
ALTER TABLE `smart`.`admin`
ADD COLUMN `createdAt_change` varchar(255) NOT NULL;

/*
*******************************
***** dashboard_widgets *******
*******************************
 */
CREATE TABLE `smart`.`dashboard_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `padre_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruta_interna` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `href` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` longtext COLLATE utf8mb4_unicode_ci,
  `isPlantilla` tinyint(1) NOT NULL,
  `plantilla` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orden` int(11) NOT NULL,
  `fa_icon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attributes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2CBC36EC3A909126` (`nombre`),
  KEY `IDX_2CBC36EC613CEC58` (`padre_id`),
  KEY `IDX_2CBC36ECD60322AC` (`role_id`),
  CONSTRAINT `FK_2CBC36EC613CEC58` FOREIGN KEY (`padre_id`) REFERENCES `dashboard_widgets` (`id`),
  CONSTRAINT `FK_2CBC36ECD60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `smart`.`dashboard_widgets`
MODIFY `isPlantilla` tinyint(1) NULL;

/*
********************
***** STATUS SS+****
********************
 */
CREATE TABLE `smart`.`status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7B00651C5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `smart`.`status` (`id`, `name`) VALUES (3,'ANULADO'),(1,'PENDIENTE'),(2,'REALIZADO');

/*
********************
***** VISIT ********
********************
 */
CREATE TABLE `smart`.`visit` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_charge_id` int(11) DEFAULT NULL,
  `provider_charge_id` int(11) DEFAULT NULL,
  `vertical_id` int(11) DEFAULT NULL,
  `reason_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `customer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_charge_another` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_charge_another` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_ini` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `duration` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `observations` longtext COLLATE utf8mb4_unicode_ci,
  `feedback` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `IDX_437EE939FED5B10E` (`customer_charge_id`),
  KEY `IDX_437EE9398CB651A6` (`provider_charge_id`),
  KEY `IDX_437EE939607DECF7` (`vertical_id`),
  KEY `IDX_437EE93959BB1592` (`reason_id`),
  KEY `IDX_437EE9396BF700BD` (`status_id`),
  KEY `IDX_437EE939F675F31B` (`author_id`),
  CONSTRAINT `FK_437EE93959BB1592` FOREIGN KEY (`reason_id`) REFERENCES `reason` (`id`),
  CONSTRAINT `FK_437EE939607DECF7` FOREIGN KEY (`vertical_id`) REFERENCES `vertical` (`id`),
  CONSTRAINT `FK_437EE9396BF700BD` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  CONSTRAINT `FK_437EE9398CB651A6` FOREIGN KEY (`provider_charge_id`) REFERENCES `charge` (`id`),
  CONSTRAINT `FK_437EE939F675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_437EE939FED5B10E` FOREIGN KEY (`customer_charge_id`) REFERENCES `charge` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `smart`.`visit`
MODIFY `type` int(11) NULL,
MODIFY `updated_at` datetime NULL DEFAULT CURRENT_TIMESTAMP,
MODIFY `date_fin` datetime NULL;

/* En SS- existen estos campos y en SS+ no existen*/
ALTER TABLE `smart`.`visit`
ADD COLUMN `tipoContacto_new` varchar(30) DEFAULT NULL,
ADD COLUMN `status_new` varchar(20) NOT NULL,
ADD COLUMN `cliente_tipo_new` varchar(100) DEFAULT NULL,
ADD COLUMN `proveedor_tipo_new` varchar(100) DEFAULT NULL;

/*
***********************************
***** comercial_cotizacion ********
***********************************
 */
CREATE TABLE `smart`.`comercial_cotizacion` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `numCoti` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plazo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuota` DOUBLE PRECISION DEFAULT NULL,
  `inversion` double DEFAULT NULL,
  `indicaciones` longtext COLLATE utf8mb4_unicode_ci,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tareas` json DEFAULT NULL COMMENT '(DC2Type:json_array)',
  `checklist` json DEFAULT NULL COMMENT '(DC2Type:json_array)',
  `fechaEstado` datetime DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `fecha_tarea` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_tarea` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `autor` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `solicitante_nombre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `solicitante_nif` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_AF1AD0DDC9BFD988` (`numCoti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Tratamiento de fechaEstado*/
/*Incorrect datetime value: '0000-00-00 00:00:00' for column 'fechaEstado'*/
ALTER TABLE `smart`.`comercial_cotizacion`
ADD COLUMN `fechaEstado_change` varchar(255) DEFAULT NULL AFTER `fechaEstado`;

/*
*********************************************
***** comercial_cotizacion_favoritos ********
*********************************************
 */
CREATE TABLE `smart`.`comercial_cotizacion_favoritos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cotizacion` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B16AE2C6A76ED395` (`user_id`),
  CONSTRAINT `FK_B16AE2C6A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
******************************************
***** comercial_expediente_status ********
******************************************
 */
CREATE TABLE `smart`.`comercial_expediente_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
**************************************************
***** comercial_expediente_perdido_motivo ********
**************************************************
 */
CREATE TABLE `smart`.`comercial_expediente_perdido_motivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
*********************************
***** comercial_producto ********
*********************************
 */
CREATE TABLE `smart`.`comercial_producto` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
***********************************
***** comercial_expediente ********
***********************************
 */
CREATE TABLE `smart`.`comercial_expediente` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) DEFAULT NULL,
  `status_motivo_id` int(11) DEFAULT NULL,
  `producto_id` bigint(20) DEFAULT NULL,
  `canal` int(11) DEFAULT NULL,
  `expediente_padre_id` bigint(20) DEFAULT NULL,
  `vertical` int(11) DEFAULT NULL,
  `responsable_id` int(11) DEFAULT NULL,
  `responsable_gestor_interno_id` int(11) DEFAULT NULL,
  `responsable_gestor_externo_id` int(11) DEFAULT NULL,
  `responsable_riesgos_id` int(11) DEFAULT NULL,
  `titulo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cliente_nif` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prescriptor_cif` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oficina` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oficina_zona` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responsable_caixa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `importe` double DEFAULT NULL,
  `fecha_oportunidad` datetime DEFAULT NULL,
  `fecha_posible_activacion` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `porcentaje_probabilidad` int(11) DEFAULT NULL,
  `tipo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` longtext COLLATE utf8mb4_unicode_ci,
  `tin` double DEFAULT NULL,
  `eslinea` tinyint(1) NOT NULL,
  `esDisposicion` tinyint(1) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `no_report` tinyint(1) NOT NULL,
  `estado` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `importe_limite` double DEFAULT NULL,
  `importe_disponible` double DEFAULT NULL,
  `fecha_vencimiento` datetime DEFAULT NULL,
  `fecha_estado` datetime DEFAULT NULL,
  `numLinea` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alerta_disp_id` bigint(20) DEFAULT NULL,
  `alerta_venc_id` bigint(20) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3E2398CD6BF700BD` (`status_id`),
  KEY `IDX_3E2398CD717004B9` (`status_motivo_id`),
  KEY `IDX_3E2398CD7645698E` (`producto_id`),
  KEY `IDX_3E2398CDE181FB59` (`canal`),
  KEY `IDX_3E2398CDA876749C` (`vertical`),
  KEY `IDX_3E2398CD63D8C20E` (`deletedBy_id`),
  KEY `IDX_3E2398CD3174800F` (`createdBy_id`),
  KEY `IDX_3E2398CD53C59D72` (`responsable_id`),
  KEY `IDX_3E2398CDB36F8F07` (`responsable_gestor_interno_id`),
  KEY `IDX_3E2398CD76895472` (`responsable_gestor_externo_id`),
  KEY `IDX_3E2398CDBE4D516B` (`responsable_riesgos_id`),
  CONSTRAINT `FK_3E2398CD3174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3E2398CD53C59D72` FOREIGN KEY (`responsable_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3E2398CD63D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3E2398CD6BF700BD` FOREIGN KEY (`status_id`) REFERENCES `comercial_expediente_status` (`id`),
  CONSTRAINT `FK_3E2398CD717004B9` FOREIGN KEY (`status_motivo_id`) REFERENCES `comercial_expediente_perdido_motivo` (`id`),
  CONSTRAINT `FK_3E2398CD7645698E` FOREIGN KEY (`producto_id`) REFERENCES `comercial_producto` (`id`),
  CONSTRAINT `FK_3E2398CD76895472` FOREIGN KEY (`responsable_gestor_externo_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3E2398CDA876749C` FOREIGN KEY (`vertical`) REFERENCES `vertical` (`id`),
  CONSTRAINT `FK_3E2398CDB36F8F07` FOREIGN KEY (`responsable_gestor_interno_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3E2398CDBE4D516B` FOREIGN KEY (`responsable_riesgos_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3E2398CDE181FB59` FOREIGN KEY (`canal`) REFERENCES `tipo_canal` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
************************************************
***** comercial_expediente_cotizaciones ********
************************************************
 */
CREATE TABLE `smart`.`comercial_expediente_cotizaciones` (
                                                     `id` bigint(20) NOT NULL AUTO_INCREMENT,
                                                     `expediente_id_id` bigint(20) NOT NULL,
                                                     `cotizacion` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                                     `created_at` datetime NOT NULL,
                                                     PRIMARY KEY (`id`),
                                                     KEY `IDX_11C90D7A894D926F` (`expediente_id_id`),
                                                     CONSTRAINT `FK_11C90D7A894D926F` FOREIGN KEY (`expediente_id_id`) REFERENCES `comercial_expediente` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
*********************************************
***** comercial_expediente_favoritos ********
*********************************************
 */
CREATE TABLE `smart`.`comercial_expediente_favoritos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `expediente_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_20B3F7EF4BF37E4E` (`expediente_id`),
  KEY `IDX_20B3F7EFA76ED395` (`user_id`),
  CONSTRAINT `FK_20B3F7EF4BF37E4E` FOREIGN KEY (`expediente_id`) REFERENCES `comercial_expediente` (`id`),
  CONSTRAINT `FK_20B3F7EFA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
*********************************
***** comercial_task_type *******
***************SS+***************
*********************************
 */
CREATE TABLE `smart`.`comercial_task_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_special` tinyint(1) NOT NULL,
  `form` longtext COLLATE utf8mb4_unicode_ci,
  `deleted_at` datetime DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `smart`.`comercial_task_type` VALUES (1,'tarea simple',0,NULL,NULL,NULL,NULL),(2,'Petición de cotización',1,NULL,NULL,NULL,NULL);

/*
*****************************
***** comercial_muro ********
*****************************
 */
CREATE TABLE `smart`.`comercial_muro` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `autor_id` int(11) DEFAULT NULL,
  `responsable_id` int(11) DEFAULT NULL,
  `expediente_id` bigint(20) DEFAULT NULL,
  `cerrado_por_id` bigint(20) DEFAULT NULL,
  `tarea_padre_id` bigint(20) DEFAULT NULL,
  `missatge` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `visto` tinyint(1) NOT NULL,
  `nivel` int(11) NOT NULL,
  `grupo` int(11) DEFAULT NULL,
  `cotizacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motivo_canc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_74CAEA2414D45BBE` (`autor_id`),
  KEY `IDX_74CAEA2453C59D72` (`responsable_id`),
  KEY `IDX_74CAEA244BF37E4E` (`expediente_id`),
  KEY `IDX_74CAEA24F76E43E6` (`cerrado_por_id`),
  KEY `IDX_74CAEA248F3FB18B` (`tarea_padre_id`),
  CONSTRAINT `FK_74CAEA244BF37E4E` FOREIGN KEY (`expediente_id`) REFERENCES `smart`.`comercial_expediente` (`id`),
  CONSTRAINT `FK_74CAEA2414D45BBE` FOREIGN KEY (`autor_id`) REFERENCES `smart`.`user` (`id`),
  CONSTRAINT `FK_74CAEA2453C59D72` FOREIGN KEY (`responsable_id`) REFERENCES `smart`.`user` (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
**************************************
***** comercial_muro_adjuntos ********
**************************************
 */
CREATE TABLE `smart`.`comercial_muro_adjuntos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `muro` bigint(20) DEFAULT NULL,
  `doc_id` int(11) DEFAULT NULL,
  `ext` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_26BC631F7A363DC4` (`muro`),
  KEY `IDX_26BC631F895648BC` (`doc_id`),
  CONSTRAINT `FK_26BC631F7A363DC4` FOREIGN KEY (`muro`) REFERENCES `comercial_muro` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* En SS- existen estos campos y en SS+ no existen*/
ALTER TABLE `smart`.`comercial_muro_adjuntos`
ADD COLUMN `doc_id_tmp` varchar(70) COLLATE utf8_unicode_ci NOT NULL;

/*
************************
***** document *********
************************
 */
CREATE TABLE `smart`.`document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_type_id` int(11) DEFAULT NULL,
  `expedient_id` bigint(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idDoku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observations` longtext COLLATE utf8mb4_unicode_ci,
  `createdAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D8698A7661232A4F` (`document_type_id`),
  KEY `IDX_D8698A762691F4E9` (`expedient_id`),
  KEY `IDX_D8698A76A76ED395` (`user_id`),
  CONSTRAINT `FK_D8698A762691F4E9` FOREIGN KEY (`expedient_id`) REFERENCES `smart`.`comercial_expediente` (`id`),
  CONSTRAINT `FK_D8698A7661232A4F` FOREIGN KEY (`document_type_id`) REFERENCES `smart`.`document_type` (`id`),
  CONSTRAINT `FK_D8698A76A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
**************************************
***** comercial_muro_estados *********
**************************************
 */
CREATE TABLE `smart`.`comercial_muro_estados` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `missatge_id` bigint(20) DEFAULT NULL,
  `autor_id` int(11) DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
*********************************
***** comercial_task_status *****
****************SS+**************
*********************************
 */
CREATE TABLE `smart`.`comercial_task_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `smart`.`comercial_task_status` VALUES (1,'Activa'),(2,'Pendiente responsable'),(3,'Cancelada'),(4,'Done'),(5,'Closed');

/*
*********************************
***** comercial_task ************
*********************************
 */
CREATE TABLE `smart`.`comercial_task` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `responsible_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `deleted_by_id` int(11) DEFAULT NULL,
  `comercial_muro_id` bigint(20) DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `seen` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `form` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5C820CC586251BB0` (`comercial_muro_id`),
  KEY `IDX_5C820CC5602AD315` (`responsible_id`),
  KEY `IDX_5C820CC56BF700BD` (`status_id`),
  KEY `IDX_5C820CC5C54C8C93` (`type_id`),
  KEY `IDX_5C820CC5B03A8386` (`created_by_id`),
  KEY `IDX_5C820CC5C76F1F52` (`deleted_by_id`),
  CONSTRAINT `FK_5C820CC5602AD315` FOREIGN KEY (`responsible_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_5C820CC56BF700BD` FOREIGN KEY (`status_id`) REFERENCES `comercial_task_status` (`id`),
  CONSTRAINT `FK_5C820CC586251BB0` FOREIGN KEY (`comercial_muro_id`) REFERENCES `comercial_muro` (`id`),
  CONSTRAINT `FK_5C820CC5B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_5C820CC5C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `comercial_task_type` (`id`),
  CONSTRAINT `FK_5C820CC5C76F1F52` FOREIGN KEY (`deleted_by_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
******************
***** contact_kind ****
******************
 */
CREATE TABLE `smart`.`contact_kind` (
                                `id` int(11) NOT NULL,
                                `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`id`),
                                UNIQUE KEY `UNIQ_133B3E265E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `smart`.`contact_kind` VALUES (1,'Customer', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),(2,'Supplier', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),(3,'Customer and supplier', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),(4,'Temporary contact', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

/*
******************
***** contact ****
******************
 */
CREATE TABLE `smart`.`contact` (
                           `id` int(11) NOT NULL AUTO_INCREMENT,
                           `kind_id` int(11) NOT NULL,
                           `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                           `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                           `nif` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
                           `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                           `address` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                           `notes` longtext COLLATE utf8mb4_unicode_ci,
                           `is_active` tinyint(1) NOT NULL,
                           PRIMARY KEY (`id`),
                           UNIQUE KEY `UNIQ_4C62E638ADE62BBB` (`nif`),
                           KEY `IDX_4C62E63830602CA9` (`kind_id`),
                           CONSTRAINT `FK_4C62E63830602CA9` FOREIGN KEY (`kind_id`) REFERENCES `smart`.`contact_kind` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
**********************
***** contact_aux ****
**********************
 */
CREATE TABLE `smart`.`contact_aux` (
                                   `id` int(11) NOT NULL AUTO_INCREMENT,
                                   `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                   `nif` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `address` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                   PRIMARY KEY (`id`),
                                   UNIQUE KEY `UNIQ_4C62E638ADE62BBBAUX` (`nif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
