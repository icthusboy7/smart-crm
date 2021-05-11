/*
******************************
***** MIGRACION FINAL ********
******************************
 */
SET FOREIGN_KEY_CHECKS=1;
SET SQL_SAFE_UPDATES = 0;

ALTER TABLE `smart`.`user`
    MODIFY `roles` LONGTEXT NOT NULL COMMENT '(DC2Type:array)',
    DROP `area_id_new`,
    DROP `preLastLoginAt_new`;

ALTER TABLE `smart`.`admin`
    MODIFY `username` varchar(180) NOT NULL,
    MODIFY `username_canonical` varchar(180) NOT NULL,
    MODIFY `email` varchar(180) NOT NULL,
    MODIFY `email_canonical` varchar(180) NOT NULL,
    MODIFY `roles` LONGTEXT NOT NULL COMMENT '(DC2Type:array)',
    MODIFY `flagNotify` tinyint(1) NOT NULL,
    DROP `createdAt_change`,
    ADD UNIQUE KEY `UNIQ_880E0D7692FC23A8` (`username_canonical`),
    ADD UNIQUE KEY `UNIQ_880E0D76A0D96FBF` (`email_canonical`);

ALTER TABLE `smart`.`dashboard_widgets`
    MODIFY `isPlantilla` tinyint(1) NOT NULL;

ALTER TABLE `smart`.`visit`
    MODIFY `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    MODIFY `type` int(11) NOT NULL,
    MODIFY `date_fin` datetime NOT NULL,
    DROP `cliente_tipo_new`,
    DROP `proveedor_tipo_new`,
    DROP `hour_new`,
    DROP `minute_new`,
    DROP `tipoContacto_new`,
    DROP `status_new`;

/*
***********************************
***** comercial_expediente ********
***********************************
 */
ALTER TABLE `smart`.`comercial_expediente`
    ADD INDEX `IDX_3E2398CD9E2DAF2E` (`expediente_padre_id`),
    ADD CONSTRAINT `FK_3E2398CD9E2DAF2E` FOREIGN KEY (`expediente_padre_id`) REFERENCES `comercial_expediente` (`id`);

ALTER TABLE `smart`.`comercial_cotizacion`
    DROP `fechaEstado_change`;

ALTER TABLE `smart`.`comercial_muro`
    ADD CONSTRAINT `FK_74CAEA24F76E43E6` FOREIGN KEY (`cerrado_por_id`) REFERENCES `smart`.`comercial_muro` (`id`),
    ADD CONSTRAINT `FK_74CAEA248F3FB18B` FOREIGN KEY (`tarea_padre_id`) REFERENCES `comercial_task` (`id`);

ALTER TABLE `smart`.`comercial_muro_adjuntos`
    ADD CONSTRAINT FK_26BC631F895648BC FOREIGN KEY (doc_id) REFERENCES document (id),
    DROP `doc_id_tmp`;
