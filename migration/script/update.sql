/*
******************************
***** MIGRACION FINAL ********
******************************
 */
SET FOREIGN_KEY_CHECKS=1;
SET SQL_SAFE_UPDATES = 0;

/*
*******************
***** USER ********
*******************
 */

UPDATE `smart`.`user`
SET
    `email_canonical`    = `email`,
    `roles`              = 'N;';

/* Actualizar email nulos */
UPDATE `smart`.`admin`
SET `email` = CONCAT(`reg_number`, '@caixabankconsumer.com'),
    `email_canonical` = CONCAT(`reg_number`, '@caixabankconsumer.com')
where `email` is null;

/* Actualizar updateAt nulos */
UPDATE `smart`.`user`
SET `updatedAt` = `createdAt`
where `updatedAt` is null;

/*
*******************
***** ADMIN *******
*******************
 */
UPDATE `smart`.`admin`
SET `username`       = `reg_number`,
    `username_canonical` = `reg_number`,
    `email_canonical`    = `email`,
    `roles`              = 'N;',
    `flagNotify`         = 1;

/*Tratamiento de createdAt*/
UPDATE `smart`.`admin`
SET `createdAt_change` = CASE
                             WHEN `createdAt_change` = '0000-00-00 00:00:00' THEN NULL
                             ELSE
                                 `createdAt_change`
                         END;

UPDATE `smart`.`admin`
SET `createdAt` = `createdAt_change`;
/*Tratamiento de createdAt*/

/*
*******************************
***** dashboard_widgets *******
*******************************
 */
UPDATE `smart`.`dashboard_widgets`
SET `isPlantilla` = CASE
                        WHEN `plantilla` IS NULL THEN 0
                        ELSE 1
                    END;

 /*
********************
***** VISIT ********
********************
 */

 /* En SS- existen estos campos y en SS+ no existen*/
ALTER TABLE `smart`.`visit`
ADD COLUMN `hour_new` int(2) NULL,
ADD COLUMN `minute_new` int(2) NULL;

 UPDATE `smart`.`visit`
SET `type` = CASE
WHEN `tipoContacto_new` = 'visita' THEN 1
ELSE 0
END;

UPDATE `smart`.`visit`
SET `status_id` = CASE
WHEN `status_new` = 'PENDIENTE' THEN 1
WHEN `status_new` = 'REALIZADA' THEN 2
WHEN `status_new` = 'ANULADA'   THEN 3
END;

UPDATE `smart`.`visit`
SET `hour_new` = SUBSTRING_INDEX(duration, ":", 1);

UPDATE `smart`.`visit`
SET `minute_new` = SUBSTRING_INDEX(duration, ":", -1);

UPDATE `smart`.`visit`
SET `date_fin` = DATE_ADD(`date_ini`, INTERVAL `hour_new` hour);

UPDATE `smart`.`visit`
SET `date_fin` = DATE_ADD(`date_fin`, INTERVAL `minute_new` minute);

UPDATE `smart`.`visit`
SET `customer_charge_id` = CASE
WHEN `cliente_tipo_new` = 'Director/a Financiero/a' THEN 1
WHEN `cliente_tipo_new` = 'Director/a Compras'      THEN 2
WHEN `cliente_tipo_new` = 'Director/a Comercial'    THEN 3
WHEN `cliente_tipo_new` = 'Gerente'                 THEN 4
WHEN `cliente_tipo_new` = 'Comercial'               THEN 5
END;

UPDATE `smart`.`visit`
SET `provider_charge_id` = CASE
WHEN `proveedor_tipo_new` = 'Director/a Financiero/a' THEN 1
WHEN `proveedor_tipo_new` = 'Director/a Compras'      THEN 2
WHEN `proveedor_tipo_new` = 'Director/a Comercial'    THEN 3
WHEN `proveedor_tipo_new` = 'Gerente'                 THEN 4
WHEN `proveedor_tipo_new` = 'Comercial'               THEN 5
END;

UPDATE `smart`.`visit`
SET updated_at = created_at
where  updated_at is null;

/*
***********************************
***** comercial_cotizacion ********
***********************************
 */

/*Tratamiento de fechaEstado*/
UPDATE `smart`.`comercial_cotizacion`
SET `fechaEstado_change` = CASE
                               WHEN `fechaEstado_change` = '0000-00-00 00:00:00' THEN NULL
                               ELSE
                                   `fechaEstado_change`
    END;

ALTER TABLE `smart`.`comercial_cotizacion`
    MODIFY `fechaEstado_change` datetime DEFAULT NULL;

UPDATE `smart`.`comercial_cotizacion`
SET `fechaEstado` = `fechaEstado_change`;

/*Tratamiento de fechaEstado*/

 /*
*****************************
***** comercial_muro ********
*****************************
 */

 UPDATE `smart`.`comercial_muro`
 SET updated_at = created_at
 where updated_at is null;
