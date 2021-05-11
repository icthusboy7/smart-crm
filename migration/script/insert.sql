DELIMITER $$
USE `smart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `MIG_modifyColumns`(IN `name_schema_origin` VARCHAR(255) CHARSET utf8mb4, IN `name_table_origin` VARCHAR(255) CHARSET utf8mb4, OUT `columns_out` longtext)
    NO SQL
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE a1 varchar(255);
    DECLARE cur1 CURSOR FOR SELECT `column_name` FROM information_schema.columns WHERE table_schema = name_schema_origin AND table_name = name_table_origin;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    SELECT name_table_origin INTO @tbl_name;
    SET @tbl_tmp = CONCAT(name_table_origin, '_tmp');

    SET @str2 = "";
    SET @modify_text = " MODIFY COLUMN ";
    SET @type_text = " LONGTEXT NULL";

    OPEN cur1;

    read_loop: LOOP
        FETCH cur1 INTO a1;
        IF done THEN
            LEAVE read_loop;
        END IF;

        SET @str1="";
        SET @str1=CONCAT(@modify_text,a1,@type_text);
        SET @str2=CONCAT(@str2, @str1);

        SET @modify_text = ", MODIFY COLUMN ";

    END LOOP;

    CLOSE cur1;

    SET columns_out = @str2;

END
$$
DELIMITER ;

DELIMITER $$
USE `smart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `MIG_Columns`(IN `name_schema` VARCHAR(255) CHARSET utf8mb4, IN `name_table` VARCHAR(255) CHARSET utf8mb4, OUT `out_origin_columns` longtext, OUT `out_destination_columns` longtext)
    NO SQL
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE column_destination_name varchar(255);
    DECLARE column_origin_name varchar(255);
    DECLARE column_destination_size varchar(255);
    DECLARE cur1 CURSOR FOR SELECT column_name, column_name_origin FROM column_map WHERE table_name = name_table;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    SELECT name_table INTO @tbl_name;
    SET @tbl_tmp = CONCAT(name_table, '_tmp');

    SET @str2 = "";
    SET @str4 ="";
    SET @comma_text = "";

    OPEN cur1;

    read_loop: LOOP
        FETCH cur1 INTO column_destination_name, column_origin_name;
        IF done THEN
            LEAVE read_loop;
        END IF;

        SET @str1="";
        SET @str1=CONCAT(@comma_text, column_destination_name);
        SET @str2=CONCAT(@str2, @str1);

        SET @str3="";
        SET @str3=CONCAT(@comma_text, column_origin_name);
        SET @str4=CONCAT(@str4, @str3);

        SET @comma_text = ", ";

    END LOOP;

    CLOSE cur1;

    SET out_destination_columns = @str2;
    SET out_origin_columns = @str4;

END
$$
DELIMITER ;

DELIMITER $$
USE `smart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `MIG_Columns_information`(IN `name_schema` VARCHAR(255) CHARSET utf8mb4, IN `name_table` VARCHAR(255) CHARSET utf8mb4, OUT `columns_out` longtext)
    NO SQL
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE column_destination_name varchar(255);
    DECLARE column_destination_type varchar(255);
    DECLARE column_destination_size varchar(255);
    DECLARE cur1 CURSOR FOR SELECT column_name, DATA_TYPE, character_maximum_length FROM information_schema.columns WHERE table_schema = name_schema AND table_name = name_table;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    SELECT name_table INTO @tbl_name;
    SET @tbl_tmp = CONCAT(name_table, '_tmp');

    SET @str2 = "";
    SET @comma_text = "";

    OPEN cur1;

    read_loop: LOOP
        FETCH cur1 INTO column_destination_name, column_destination_type, column_destination_size;
        IF done THEN
            LEAVE read_loop;
        END IF;

        SET @str1="";
        SET @str1=CONCAT(@comma_text, column_destination_name);
        SET @str2=CONCAT(@str2, @str1);

        SET @comma_text = ", ";

    END LOOP;

    CLOSE cur1;

    SET columns_out = @str2;

END
$$
DELIMITER ;

DELIMITER $$
USE `smart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `MIG_insert`(IN `name_schema_origin` VARCHAR(255) CHARSET utf8mb4, IN `name_table_origin` VARCHAR(255) CHARSET utf8mb4, IN `name_schema_destination` VARCHAR(255) CHARSET utf8mb4, IN `name_table_destination` VARCHAR(255) CHARSET utf8mb4, IN `a` INT)
    NO SQL
BEGIN
    -- exit if the duplicate key occurs
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN

            GET DIAGNOSTICS CONDITION 1 @p1 = RETURNED_SQLSTATE, @p2 = MESSAGE_TEXT;
#             SELECT CONCAT(@p1, ':', @p2) AS message_error;
            SET @message = CONCAT(@p1, ':', @p2);

            SELECT name_table_origin INTO @tbl_name;
            SET @tbl_name_origin = CONCAT(name_schema_origin, ".",name_table_origin);
            SET @tbl_tmp = CONCAT(name_table_origin, '_tmp');

            SET @str=CONCAT("CREATE TABLE IF NOT EXISTS ",@tbl_tmp," (SELECT *, 'error_message' as error_message FROM ",@tbl_name_origin," WHERE ",0,")");
            PREPARE stmt FROM @str;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;

            CALL MIG_modifyColumns(name_schema_origin, name_table_origin, @outColumns);

            SET @str=CONCAT("ALTER TABLE ", @tbl_tmp, @outColumns);
            PREPARE stmt FROM @str;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;

            SET @str=CONCAT("ALTER TABLE ", @tbl_tmp, " MODIFY COLUMN error_message LONGTEXT");
            PREPARE stmt FROM @str;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;

			CALL MIG_Columns_information(name_schema_origin, name_table_origin, @columnsOrigin);

            SET @insert_into_table=CONCAT("INSERT INTO ", @tbl_tmp);
			SET @field_list_destination=CONCAT(" (", @columnsOrigin, ", error_message)");
			SET @select_from_origin=CONCAT(" SELECT ", @columnsOrigin, ", \"",@message,"\" FROM ", @schema_table_origin);
			SET @where_id=CONCAT(" WHERE id = ", a);

            SET @str=CONCAT(@insert_into_table, @field_list_destination, @select_from_origin, @where_id);
			PREPARE stmt FROM @str;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;

        END;

	CALL MIG_Columns(name_schema_destination, name_table_destination, @columnsOrigin, @columnsDestination);

    SET @schema_table_origin=CONCAT(name_schema_origin, ".", name_table_origin);

    SET @insert_into_table=CONCAT("INSERT INTO ", name_table_destination);
    SET @field_list_destination=CONCAT(" (", @columnsDestination, ")");
    SET @select_from_origin=CONCAT(" SELECT ", @columnsOrigin, " FROM ", @schema_table_origin);
    SET @where_id=CONCAT(" WHERE id = ", a);

    SET @str=CONCAT(@insert_into_table, @field_list_destination, @select_from_origin, @where_id);
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

END
$$
DELIMITER ;

DELIMITER $$
USE `smart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `MIG_map`(IN `name_schema_origin` VARCHAR(255) CHARSET utf8mb4, IN `name_schema_destination` VARCHAR(255) CHARSET utf8mb4)
    NO SQL
BEGIN

	/*
	***********************
	***** table_map *******
	***********************
	 */
    SET @str=CONCAT("CREATE TABLE ", name_schema_destination, ".","table_map SELECT table_name FROM information_schema.columns WHERE table_schema = '", name_schema_destination, "' group by table_name");
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

	SET @str=CONCAT("ALTER TABLE ", name_schema_destination, ".","table_map ADD COLUMN `table_name_origin` varchar(255), add column `table_order` int");
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

    /*
	***********************
	***** column_map ******
	***********************
	 */
    SET @str=CONCAT("CREATE TABLE ", name_schema_destination, ".","column_map SELECT table_name, column_name FROM information_schema.columns WHERE table_schema = '", name_schema_destination, "'");
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("ALTER TABLE ", name_schema_destination, ".","column_map ADD COLUMN `column_name_origin` varchar(255)");
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = column_name");
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

    /*
    *******************
    ***** TIPO CANAL ******
    *******************
     */
    SET @table_destination = 'tipo_canal';
    SET @table_origin = 'tipo_canal';
    SET @table_order = 1;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    *******************
    ***** VERTICAL ******
    *******************
     */
    SET @table_destination = 'vertical';
    SET @table_origin = 'comercial_vertical';
    SET @table_order = 2;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'nombre';
    SET @column_destination = "('name')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    *******************
    ***** REASON ******
    *******************
     */
    SET @table_destination = 'reason';
    SET @table_origin = 'comercial_visita_motivo';
    SET @table_order = 3;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'motivo';
    SET @column_destination = "('name')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    *******************
    ***** CHARGE ******
    *******************
     */
    SET @table_destination = 'charge';
    SET @table_origin = 'comercial_visita_tipo_contacto';
    SET @table_order = 4;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'tipo';
    SET @column_destination = "('name')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    *******************
    ***** COMPANY *****
    *******************
     */
    SET @table_destination = 'company';
    SET @table_origin = 'company';
    SET @table_order = 5;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    **************************
    ***** document_type ******
    **************************
     */
    SET @table_destination = 'document_type';
    SET @table_origin = 'tipo_documento';
    SET @table_order = 6;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'doc_code';
    SET @column_destination = "('code')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'doc_desc';
    SET @column_destination = "('description')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    *******************
    ***** ROLE ******
    *******************
     */
    SET @table_destination = 'role';
    SET @table_origin = 'role';
    SET @table_order = 7;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
	*******************
	***** user ******
	*******************
	 */
	SET @table_destination = 'user';
    SET @table_origin = 'user';
    SET @table_order = 8;
	SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

	SET @column_origin = 'regNumber';
    SET @column_destination = "('reg_number', 'username', 'username_canonical')";
 	SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

	SET @column_origin = 'regNumberCaixabank';
    SET @column_destination = "('reg_number_caixabank')";
 	SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

	SET @column_origin = 'email';
    SET @column_destination = "('email_canonical')";
 	SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

	SET @column_origin = 'isActive';
    SET @column_destination = "('enabled')";
 	SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

	SET @column_origin = 'lastLoginAt';
    SET @column_destination = "('last_login')";
 	SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("DELETE FROM ",name_schema_destination, ".column_map WHERE table_name = '", @table_destination, "' AND column_name IN ('area_id_new', 'preLastLoginAt_new', 'confirmation_token', 'password_requested_at', 'roles', 'updatedAt')");
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

    /*
	*******************
	***** admin ******
	*******************
	 */
	SET @table_destination = 'admin';
    SET @table_origin = 'admin';
    SET @table_order = 9;
	SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

    SET @column_origin = 'regNumber';
    SET @column_destination = "('reg_number')";
 	SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

    SET @column_origin = 'regNumberCaixabank';
    SET @column_destination = "('reg_number_caixabank')";
 	SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

    SET @column_origin = 'isActive';
    SET @column_destination = "('enabled')";
 	SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

    SET @column_origin = 'createdAt';
    SET @column_destination = "('createdAt_change')";
 	SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

    SET @column_origin = 'lastLoginAt';
    SET @column_destination = "('last_login')";
 	SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("DELETE FROM ",name_schema_destination, ".column_map WHERE table_name = '", @table_destination, "' AND column_name IN ('company_id', 'username', 'username_canonical', 'email_canonical', 'roles', 'salt', 'confirmation_token', 'password_requested_at', 'updatedAt', 'flagNotify')");
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

    /*
    ******************************
    ***** dashboard_widgets ******
    ******************************
     */
    SET @table_destination = 'dashboard_widgets';
    SET @table_origin = 'dashboard_widgets';
    SET @table_order = 10;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'style';
    SET @column_destination = "('attributes')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("DELETE FROM ",name_schema_destination, ".column_map WHERE table_name = '", @table_destination, "' AND column_name IN ('isPlantilla')");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    *******************
    ***** visit ******
    *******************
     */
    SET @table_destination = 'visit';
    SET @table_origin = 'comercial_visita';
    SET @table_order = 11;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'motivo';
    SET @column_destination = "('reason_id')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'autor';
    SET @column_destination = "('author_id')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'contacto_nif';
    SET @column_destination = "('customer_id')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'prescriptor_cif';
    SET @column_destination = "('provider_id')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'oficina';
    SET @column_destination = "('office')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'fechaVisita';
    SET @column_destination = "('date_ini')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'duracion';
    SET @column_destination = "('duration')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'observaciones';
    SET @column_destination = "('observations')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'tipoContacto';
    SET @column_destination = "('tipoContacto_new')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'status';
    SET @column_destination = "('status_new')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'createdAt';
    SET @column_destination = "('created_at')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'updatedAt';
    SET @column_destination = "('updated_at')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'cliente_tipo';
    SET @column_destination = "('cliente_tipo_new')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'proveedor_tipo';
    SET @column_destination = "('proveedor_tipo_new')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("DELETE FROM ",name_schema_destination, ".column_map WHERE table_name = '", @table_destination, "' AND column_name IN ('customer_charge_id', 'provider_charge_id', 'customer_charge_another', 'provider_charge_another', 'type', 'status_id', 'date_fin', 'type', 'hour_new','minute_new')");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

	/*
    *****************************
    ***** comercial_cotizacion **
    *****************************
     */
    SET @table_destination = 'comercial_cotizacion';
    SET @table_origin = 'comercial_cotizacion';
    SET @table_order = 12;
	SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
   	EXECUTE stmt;
   	DEALLOCATE PREPARE stmt;

	SET @column_origin = 'fechaEstado';
    SET @column_destination = "('fechaEstado_change')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("DELETE FROM ",name_schema_destination, ".column_map WHERE table_name = '", @table_destination, "' AND column_name IN ('fechaEstado')");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ***************************************
    ***** comercial_cotizacion_favoritos **
    ***************************************
     */
    SET @table_destination = 'comercial_cotizacion_favoritos';
    SET @table_origin = 'comercial_cotizacion_favoritos';
    SET @table_order = 13;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'createdAt';
    SET @column_destination = "('created_at')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ***************************************
    ***** comercial_expediente_status **
    ***************************************
     */
    SET @table_destination = 'comercial_expediente_status';
    SET @table_origin = 'comercial_expediente_status';
    SET @table_order = 14;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ********************************************
    ***** comercial_expediente_perdido_motivo **
    ********************************************
     */
    SET @table_destination = 'comercial_expediente_perdido_motivo';
    SET @table_origin = 'comercial_expediente_perdido_motivo';
    SET @table_order = 15;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ***************************************
    ***** comercial_producto **
    ***************************************
     */
    SET @table_destination = 'comercial_producto';
    SET @table_origin = 'comercial_producto';
    SET @table_order = 16;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'createdAt';
    SET @column_destination = "('created_at')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    *****************************
    ***** comercial_expediente **
    *****************************
     */
    SET @table_destination = 'comercial_expediente';
    SET @table_origin = 'comercial_expediente';
    SET @table_order = 17;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ******************************************
    ***** comercial_expediente_cotizaciones **
    ******************************************
     */
    SET @table_destination = 'comercial_expediente_cotizaciones';
    SET @table_origin = 'comercial_expediente_cotizaciones';
    SET @table_order = 18;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'expediente_id';
    SET @column_destination = "('expediente_id_id')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'createdAt';
    SET @column_destination = "('created_at')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ******************************************
    ***** comercial_expediente_favoritos **
    ******************************************
    */
    SET @table_destination = 'comercial_expediente_favoritos';
    SET @table_origin = 'comercial_expediente_favoritos';
    SET @table_order = 19;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'createdAt';
    SET @column_destination = "('created_at')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ***********************
    ***** comercial_muro **
    ***********************
    */
    SET @table_destination = 'comercial_muro';
    SET @table_origin = 'comercial_muro';
    SET @table_order = 20;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'autor';
    SET @column_destination = "('autor_id')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'responsable';
    SET @column_destination = "('responsable_id')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'expediente';
    SET @column_destination = "('expediente_id')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'cerrado_por';
    SET @column_destination = "('cerrado_por_id')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'createdAt';
    SET @column_destination = "('created_at')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'updatedAt';
    SET @column_destination = "('updated_at')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'tarea_noti_id';
    SET @column_destination = "('tarea_padre_id')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ********************************
    ***** comercial_muro_adjuntos **
    ********************************
    */
    SET @table_destination = 'comercial_muro_adjuntos';
    SET @table_origin = 'comercial_muro_adjuntos';
    SET @table_order = 21;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'doc_id';
    SET @column_destination = "('doc_id_tmp')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("DELETE FROM ",name_schema_destination, ".column_map WHERE table_name = '", @table_destination, "' AND column_name IN ('doc_id')");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ********************************
    ***** comercial_muro_estados **
    ********************************
    */
    SET @table_destination = 'comercial_muro_estados';
    SET @table_origin = 'comercial_muro_estados';
    SET @table_order = 22;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'missatge';
    SET @column_destination = "('missatge_id')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'autor';
    SET @column_destination = "('autor_id')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ************************************************
    ***** comercial_rel_comercial_prescriptor ******
    ************************************************
     */
    SET @table_destination = 'comercial_rel_comercial_prescriptor';
    SET @table_origin = 'comercial_rel_comercial_prescriptor';
    SET @table_order = 23;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ***********************************
    ***** comercial_usuario_tipo ******
    ***********************************
     */
    SET @table_destination = 'comercial_usuario_tipo';
    SET @table_origin = 'comercial_usuario_tipo';
    SET @table_order = 24;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ***********************************
    ***** comercial_usuario_zona ******
    ***********************************
     */
    SET @table_destination = 'comercial_usuario_zona';
    SET @table_origin = 'comercial_usuario_zona';
    SET @table_order = 24;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ***********************************
    ***** comercial_usuario ******
    ***********************************
     */
    SET @table_destination = 'comercial_usuario';
    SET @table_origin = 'comercial_usuario';
    SET @table_order = 25;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    /*
    ********************
    ***** contact_aux **
    ********************
    */
    SET @table_destination = 'contact_aux';
    SET @table_origin = 'persona';
    SET @table_order = 26;
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","table_map SET table_name_origin = '", @table_origin, "', table_order = ", @table_order, " WHERE table_name = '", @table_destination,"'");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'nombre';
    SET @column_destination = "('name')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'direccion';
    SET @column_destination = "('address')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'telefono';
    SET @column_destination = "('phone')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'correo';
    SET @column_destination = "('email')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @column_origin = 'createdAt';
    SET @column_destination = "('created_at')";
    SET @str=CONCAT("UPDATE ", name_schema_destination, ".","column_map SET column_name_origin = '", @column_origin, "' WHERE table_name = '", @table_destination,"' AND column_name IN ", @column_destination);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @str=CONCAT("DELETE FROM ",name_schema_destination, ".column_map WHERE table_name = '", @table_destination, "' AND column_name IN ('kind_id', 'updated_at', 'notes', 'is_active')");
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    call MIG_table(name_schema_origin,name_schema_destination);
END
$$
DELIMITER ;

DELIMITER $$
USE `smart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `MIG_table_select_id`(IN `name_schema_origin` VARCHAR(255) CHARSET utf8mb4, IN `name_schema_destination` VARCHAR(255) CHARSET utf8mb4, IN `name_table_origin` VARCHAR(255) CHARSET utf8mb4, IN `name_table_destination` VARCHAR(255) CHARSET utf8mb4)
    NO SQL
BEGIN
	DECLARE done INT DEFAULT FALSE;

	DECLARE a int;

	DECLARE cur1 CURSOR FOR SELECT id FROM id_tmp;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

	OPEN cur1;

    read_loop: LOOP
    FETCH cur1 INTO a;
    IF done THEN
      LEAVE read_loop;
    END IF;

	CALL MIG_insert(name_schema_origin,name_table_origin, name_schema_destination, name_table_destination, a);

  END LOOP;

  CLOSE cur1;

END
$$
DELIMITER ;

DELIMITER $$
USE `smart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `MIG_table`(IN `name_schema_origin` VARCHAR(255) CHARSET utf8mb4, IN `name_schema_destination` VARCHAR(255) CHARSET utf8mb4)
    NO SQL
BEGIN
    DECLARE done INT DEFAULT FALSE;

    DECLARE a varchar(255);
    DECLARE b varchar(255);

#     ('tipo_canal', 'vertical', 'reason', 'charge', 'company', 'document_type', 'role', 'user', 'admin', 'dashboard_widgets', 'visit', 'comercial_cotizacion', 'comercial_cotizacion_favoritos', 'comercial_expediente_status', 'comercial_expediente_perdido_motivo', 'comercial_producto', 'comercial_expediente', 'comercial_expediente_cotizaciones', 'comercial_expediente_favoritos', 'comercial_muro', 'comercial_muro_adjuntos', 'comercial_muro_estados', 'comercial_rel_comercial_prescriptor', 'comercial_usuario_tipo', 'comercial_usuario_zona', 'comercial_usuario', 'contact_aux');
    DECLARE cur1 CURSOR FOR SELECT table_name_origin, table_name FROM table_map where table_name IN ('tipo_canal', 'vertical', 'reason', 'charge', 'company', 'document_type', 'role', 'user', 'admin', 'dashboard_widgets', 'visit', 'comercial_cotizacion', 'comercial_cotizacion_favoritos', 'comercial_expediente_status', 'comercial_expediente_perdido_motivo', 'comercial_producto', 'comercial_expediente', 'comercial_expediente_cotizaciones', 'comercial_expediente_favoritos', 'comercial_muro', 'comercial_muro_adjuntos', 'comercial_muro_estados', 'comercial_rel_comercial_prescriptor', 'comercial_usuario_tipo', 'comercial_usuario_zona', 'comercial_usuario', 'contact_aux') order by table_order;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur1;

    read_loop: LOOP
        FETCH cur1 INTO a, b;
        IF done THEN
            LEAVE read_loop;
        END IF;

         DROP TABLE IF EXISTS id_tmp;
        SET @str=CONCAT("CREATE TABLE IF NOT EXISTS id_tmp SELECT id FROM ",name_schema_origin,".", a);
		PREPARE stmt FROM @str;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;

        CALL MIG_table_select_id(name_schema_origin, name_schema_destination, a, b);

    END LOOP;

    CLOSE cur1;

END
$$
DELIMITER ;

DELIMITER $$
USE `smart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `MIG_document`(IN `name_schema_origin` VARCHAR(255) CHARSET utf8mb4, IN `name_schema_destination` VARCHAR(255) CHARSET utf8mb4)
    NO SQL
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE a int;
    DECLARE cur1 CURSOR FOR SELECT `id` FROM  `smart`.`comercial_muro_adjuntos`;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur1;

    read_loop: LOOP
        FETCH cur1 INTO a;
        IF done THEN
            LEAVE read_loop;
        END IF;

        CALL MIG_insert_document(name_schema_origin, 'comercial_muro_adjuntos', name_schema_destination, 'document', a);

    END LOOP;

    CLOSE cur1;

END
$$
DELIMITER ;

DELIMITER $$
USE `smart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `MIG_insert_document`(IN `name_schema_origin` VARCHAR(255) CHARSET utf8mb4, IN `name_table_origin` VARCHAR(255) CHARSET utf8mb4, IN `name_schema_destination` VARCHAR(255) CHARSET utf8mb4, IN `name_table_destination` VARCHAR(255) CHARSET utf8mb4, IN `a` INT)
    NO SQL
BEGIN
    -- exit if the duplicate key occurs
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN

            GET DIAGNOSTICS CONDITION 1 @p1 = RETURNED_SQLSTATE, @p2 = MESSAGE_TEXT;
            SELECT CONCAT(@p1, ':', @p2) AS message_error;
            SET @message = CONCAT(@p1, ':', @p2);

            SELECT name_table_destination INTO @tbl_name;
            SET @tbl_name_destination = CONCAT(name_schema_destination, ".",name_table_destination);
            SET @tbl_tmp = CONCAT(name_table_destination, '_tmp');

            SET @str=CONCAT("CREATE TABLE IF NOT EXISTS ",@tbl_tmp," (SELECT *, 'error_message' as error_message FROM ",@tbl_name_destination," WHERE ",0,")");
            PREPARE stmt FROM @str;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;

            CALL MIG_modifyColumns(name_schema_destination, name_table_destination, @outColumns);

            SET @str=CONCAT("ALTER TABLE ", @tbl_tmp, @outColumns);
            PREPARE stmt FROM @str;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;

            SET @str=CONCAT("ALTER TABLE ", @tbl_tmp, " MODIFY COLUMN error_message LONGTEXT");
            PREPARE stmt FROM @str;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;

#             CALL MIG_Columns_information(name_schema_destination, name_table_destination, @columnsDestination);

            SET @columnsDestination = CONCAT("name",", ", "expedient_id",", ", "user_id",", ", "quotation",", ", "createdAt");
            select a;
            SET @filename = (SELECT `filename` FROM `smart`.`comercial_muro_adjuntos` WHERE `id` = a);
            select @filename;
            SET @createdAt = (SELECT `createdAt` FROM `smart`.`comercial_muro_adjuntos` WHERE `id` = a);
            SET @pipelineId = (SELECT m.`expediente_id` FROM `smart`.`comercial_muro` m JOIN `smart`.`comercial_muro_adjuntos` ad ON ad.`muro` = m.`id` WHERE ad.`id` = a);
            SET @userId = (SELECT m.`autor_id` FROM `smart`.`comercial_muro` m JOIN `smart`.`comercial_muro_adjuntos` ad ON ad.`muro` = m.`id` WHERE ad.`id` = a);
            SET @cotizacion = (SELECT m.`cotizacion` FROM `smart`.`comercial_muro` m JOIN `smart`.`comercial_muro_adjuntos` ad ON ad.`muro` = m.`id` WHERE ad.`id` = a);
            IF @cotizacion IS NULL THEN
                SET @cotizacion = 'NULL';
            ELSE
                SET @cotizacion = CONCAT("'",@cotizacion,"'");
            END IF;
            SET @valuesList = CONCAT("'",@filename,"', '", @pipelineId, "', '", @userId, "', ", @cotizacion, ", '", @createdAt, "'");

            SET @insert_into_table=CONCAT("INSERT INTO ", @tbl_tmp);
            SET @field_list_destination=CONCAT(" (", @columnsDestination, ")");
            SET @values=CONCAT(" VALUES (", @valuesList, ")");

            SET @str=CONCAT(@insert_into_table, @field_list_destination, @values);
            PREPARE stmt FROM @str;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;

        END;

    SET @columnsDestination = CONCAT("name, ", "expedient_id, ", "user_id, ", "quotation, ", "path, ","createdAt");

    SET @filename = (SELECT `filename` FROM `smart`.`comercial_muro_adjuntos` WHERE `id` = a);
    SET @createdAt = (SELECT `createdAt` FROM `smart`.`comercial_muro_adjuntos` WHERE `id` = a);
    SET @pipelineId = (SELECT m.`expediente_id` FROM `smart`.`comercial_muro` m JOIN `smart`.`comercial_muro_adjuntos` ad ON ad.`muro` = m.`id` WHERE ad.`id` = a);
    SET @userId = (SELECT m.`autor_id` FROM `smart`.`comercial_muro` m JOIN `smart`.`comercial_muro_adjuntos` ad ON ad.`muro` = m.`id` WHERE ad.`id` = a);
    SET @cotizacion = (SELECT m.`cotizacion` FROM `smart`.`comercial_muro` m JOIN `smart`.`comercial_muro_adjuntos` ad ON ad.`muro` = m.`id` WHERE ad.`id` = a);
    IF @cotizacion IS NULL THEN
        SET @cotizacion = 'NULL';
    ELSE
        SET @cotizacion = CONCAT("'",@cotizacion,"'");
    END IF;

    SET @path = (SELECT `doc_id_tmp` FROM `smart`.`comercial_muro_adjuntos` WHERE `id` = a);

    SET @valuesList = CONCAT("'",@filename,"', ", @pipelineId, ", ", @userId, ", ", @cotizacion, ", '", @path,"', '", @createdAt, "'");

    SET @insert_into_table=CONCAT("INSERT INTO ", name_table_destination);
    SET @field_list_destination=CONCAT(" (", @columnsDestination, ")");
    SET @values=CONCAT(" VALUES (", @valuesList, ")");

    SET @str=CONCAT(@insert_into_table, @field_list_destination, @values);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    UPDATE `smart`.`comercial_muro_adjuntos`
    SET doc_id = LAST_INSERT_ID()
    WHERE `id` = a;

END
$$
DELIMITER ;

DELIMITER $$
USE `smart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `MIG_task`(IN `name_schema_origin` VARCHAR(255) CHARSET utf8mb4, IN `name_schema_destination` VARCHAR(255) CHARSET utf8mb4)
    NO SQL
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE a int;
    DECLARE cur1 CURSOR FOR SELECT `id` FROM  `smart`.`comercial_muro_adjuntos`;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    /* INSERTAR DONDE comercial_muro sea tipo 2*/
    INSERT `smart`.`comercial_task` (  `comercial_muro_id`, `created_by_id`, `responsible_id`, `type_id`, `description`, `seen`,`created_at`, `updated_at`)
    SELECT `id`, `autor_id`, `responsable_id`, 1, `missatge`, `visto`, `created_at`, `updated_at`
    FROM `smart`.`comercial_muro` WHERE tipo = 2;

/* INSERTAR DONDE comercial_muro sea tipo 2 o 4*/
    INSERT `smart`.`comercial_task` (  `comercial_muro_id`, `created_by_id`, `responsible_id`, `type_id`, `description`, `seen`,`created_at`, `updated_at`)
    SELECT `id`, `autor_id`, `responsable_id`, 2, `missatge`, `visto`, `created_at`, `updated_at`
    FROM `smart`.`comercial_muro` WHERE tipo = 4;

    UPDATE `smart`.`comercial_muro`
    SET `tipo` = 2
    where `tipo` = 4;

/* Actualizar MURO los tipo 3 status a tipo 4 */
    UPDATE `smart`.`comercial_muro`
    SET `tipo` = 4
    where `tipo` = 3;

/*Actualizar estado en task */
    UPDATE `smart`.`comercial_task` AS t
        JOIN `smart`.`comercial_muro` AS m
        ON m.id = t.comercial_muro_id
        JOIN `smart`.`comercial_muro_estados` AS e
        ON e.missatge_id = m.id
    SET t.status_id = CASE
                          WHEN e.`estado` = 'ABIERTO'   THEN 1
                          WHEN e.`estado` = 'ACEPTADA'  THEN 4
                          WHEN e.`estado` = 'CERRADO'   THEN 5
                          WHEN e.`estado` = 'CANCELADO' THEN 3
        END
    WHERE
        e.id IN (SELECT MAX(id) FROM `smart`.`comercial_muro_estados` where missatge_id = m.id)
    ;

    /* Borrar tabla comercial_muro_estados */
    DROP TABLE `smart`.`comercial_muro_estados`;

END
$$
DELIMITER ;
