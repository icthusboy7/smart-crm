DELIMITER $$
USE `smart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `MIG_count`(IN `name_schema_origin` VARCHAR(255) CHARSET utf8mb4, IN `name_schema_destination` VARCHAR(255) CHARSET utf8mb4)
    NO SQL
BEGIN

    /*
    *******************
    *** Tipo Canal ****
    *******************
     */
    SET @table_origin = 'tipo_canal';
    SET @table_destination = 'tipo_canal';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`tipo_canal_tmp`;
    END IF;

    /*
    *****************
    *** Vertical ****
    *****************
     */
    SET @table_origin = 'comercial_vertical';
    SET @table_destination = 'vertical';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_vertical_tmp`;
    END IF;

    /*
    *****************
    *** reason ****
    *****************
     */
    SET @table_origin = 'comercial_visita_motivo';
    SET @table_destination = 'reason';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff <> 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_visita_motivo_tmp`;
    END IF;

    /*
    *****************
    *** charge ****
    *****************
     */
    SET @table_origin = 'comercial_visita_tipo_contacto';
    SET @table_destination = 'charge';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_visita_tipo_contacto_tmp`;
    END IF;

    /*
    *****************
    *** company ****
    *****************
     */
    SET @table_origin = 'company';
    SET @table_destination = 'company';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`company_tmp`;
    END IF;

    /*
    *****************
    *** document_type ****
    *****************
     */
    SET @table_origin = 'tipo_documento';
    SET @table_destination = 'document_type';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`tipo_documento_tmp`;
    END IF;

    /*
    *****************
    *** role ****
    *****************
     */
    SET @table_origin = 'role';
    SET @table_destination = 'role';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`role_tmp`;
    END IF;

    /*
    *****************
    *** user ****
    *****************
     */
    SET @table_origin = 'user';
    SET @table_destination = 'user';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`user_tmp`;
    END IF;

    /*
    *****************
    *** admin ****
    *****************
     */
    SET @table_origin = 'admin';
    SET @table_destination = 'admin';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`admin_tmp`;
    END IF;

    /*
    **************************
    *** dashboard_widgets ****
    **************************
     */
    SET @table_origin = 'dashboard_widgets';
    SET @table_destination = 'dashboard_widgets';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`dashboard_widgets_tmp`;
    END IF;

    /*
    *****************
    *** visit ****
    *****************
     */
    SET @table_origin = 'comercial_visita';
    SET @table_destination = 'visit';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_visita_tmp`;
    END IF;

    /*
    *****************
    *** comercial_cotizacion ****
    *****************
     */
    SET @table_origin = 'comercial_cotizacion';
    SET @table_destination = 'comercial_cotizacion';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_cotizacion_tmp`;
    END IF;

    /*
    *****************
    *** comercial_cotizacion_favoritos ****
    *****************
     */
    SET @table_origin = 'comercial_cotizacion_favoritos';
    SET @table_destination = 'comercial_cotizacion_favoritos';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_cotizacion_favoritos_tmp`;
    END IF;

    /*
    ************************************
    *** comercial_expediente_status ****
    ************************************
     */
    SET @table_origin = 'comercial_expediente_status';
    SET @table_destination = 'comercial_expediente_status';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_expediente_status_tmp`;
    END IF;

    /*
    ************************************
    *** comercial_producto ****
    ************************************
     */
    SET @table_origin = 'comercial_producto';
    SET @table_destination = 'comercial_producto';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_producto_tmp`;
    END IF;

    /*
************************************
*** comercial_expediente ****
************************************
 */
    SET @table_origin = 'comercial_expediente';
    SET @table_destination = 'comercial_expediente';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_expediente_tmp`;
    END IF;

    /*
******************************************
*** comercial_expediente_cotizaciones ****
******************************************
 */
    SET @table_origin = 'comercial_expediente_cotizaciones';
    SET @table_destination = 'comercial_expediente_cotizaciones';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_expediente_cotizaciones_tmp`;
    END IF;

    /*
************************************
*** comercial_expediente_favoritos ****
************************************
 */
    SET @table_origin = 'comercial_expediente_favoritos';
    SET @table_destination = 'comercial_expediente_favoritos';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_expediente_favoritos_tmp`;
    END IF;

    /*
************************************
*** comercial_expediente_motivo_perdido ****
************************************
*/
    SET @table_origin = 'comercial_expediente_perdido_motivo';
    SET @table_destination = 'comercial_expediente_perdido_motivo';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_expediente_favoritos_tmp`;
    END IF;

    /*
    ************************************
    *** comercial_muro ****
    ************************************
     */
    SET @table_origin = 'comercial_muro';
    SET @table_destination = 'comercial_muro';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_muro_tmp`;
    END IF;

    /*
    *****************
    *** document ****
    *****************
     */
    SET @table_origin = 'comercial_muro_adjuntos';
    SET @table_destination = 'document';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_destination,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`document_tmp`;
    END IF;

    /*
    ***********************
    *** comercial_task ****
    ***********************
     */
    SET @table_origin = 'comercial_muro';
    SET @table_destination = 'comercial_task';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_destination,"`.`",@table_origin,"` WHERE `tipo` in (2))");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    /*
    ********************************************
    *** comercial_rel_comercial_prescriptor ****
    ********************************************
     */
    SET @table_origin = 'comercial_rel_comercial_prescriptor';
    SET @table_destination = 'comercial_rel_comercial_prescriptor';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_destination,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_rel_comercial_prescriptor_tmp`;
    END IF;

    /*
    *******************************
    *** comercial_usuario_tipo ****
    *******************************
     */
    SET @table_origin = 'comercial_usuario_tipo';
    SET @table_destination = 'comercial_usuario_tipo';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_destination,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_usuario_tipo_tmp`;
    END IF;

    /*
    *******************************
    *** comercial_usuario_zona ****
    *******************************
     */
    SET @table_origin = 'comercial_usuario_zona';
    SET @table_destination = 'comercial_usuario_zona';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_destination,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_usuario_zona_tmp`;
    END IF;

    /*
    **************************
    *** comercial_usuario ****
    **************************
     */
    SET @table_origin = 'comercial_usuario';
    SET @table_destination = 'comercial_usuario';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (select count(id) FROM `",name_schema_destination,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("SET @count_destination = (select count(id) FROM `",name_schema_destination,"`.`",@table_destination,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`comercial_usuario_tmp`;
    END IF;

    /*
    *****************
    *** contact ****
    *****************
     */
    SET @table_origin = 'persona';
    SET @table_destination = 'contact';
    SET @title = CONCAT('Table origin: ',@table_origin);
    SET @titleDestination = CONCAT('Table Destination: ',@table_destination);
    SET @str=CONCAT("SET @count_origin = (SELECT count(*) FROM `",name_schema_origin,"`.`",@table_origin,"`)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @table_destination1 = 'contact_aux';
    SET @table_destination2 = 'contact';
    SET @str=CONCAT("SET @count_destination1 = (SELECT count(*) FROM `",name_schema_destination,"`.`",@table_destination1,"` as a JOIN `",name_schema_destination,"`.`",@table_destination2,"` as c ON a.nif = c.nif WHERE (c.kind_id = 1 OR c.kind_id = 2 OR c.kind_id = 3))");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @table_destination1 = 'contact_aux';
    SET @table_destination2 = 'contact';
    SET @str=CONCAT("SET @count_destination2 = (SELECT count(*) FROM `",name_schema_destination,"`.`",@table_destination1,"` as a JOIN `",name_schema_destination,"`.`",@table_destination2,"` as c ON a.nif = c.nif WHERE c.kind_id = 4)");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @count_destination = @count_destination1 + @count_destination2;

    SELECT '***********';
    SELECT @title as '';
    SELECT @titleDestination as '';
    SELECT CONCAT("SS-.   El total de registros es: ", @count_origin) as '';
    SELECT CONCAT("SMART. El total de registros es: ", @count_destination) as '';

    SET @diff = @count_destination-@count_origin;
    SELECT IF(@diff= 0, "No hay diferencias", CONCAT("La diferencia es: ", @diff)) AS '';

    IF @diff < 0 THEN
        SELECT id, error_message FROM `smart`.`persona_tmp`;
    END IF;

END
$$
DELIMITER ;
