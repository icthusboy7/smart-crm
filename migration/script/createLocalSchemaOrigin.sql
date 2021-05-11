DELIMITER $$
USE `smart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `MIG_createLocalSchemaOrigin`(IN `name_schema_origin` VARCHAR(255) CHARSET utf8mb4)
    NO SQL
BEGIN

    /*
    *****************************
    *** CREATE ORIGIN SCHEMA ****
    *****************************
     */
    SET @str=CONCAT("CREATE DATABASE IF NOT EXISTS ",name_schema_origin);
    PREPARE stmt FROM @str;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;


END
$$
DELIMITER ;
