DELIMITER $$
USE `smart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `MIG_updateComercialMuroMissatge`()
    NO SQL
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE a int;
    DECLARE b longtext CHARSET utf8mb4;
    DECLARE cur1 CURSOR FOR SELECT `id`, `missatge` FROM  `smart`.`comercial_muro` WHERE `missatge` like '%<span class="hidden valorAntiguo">%';
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur1;

    read_loop: LOOP
        FETCH cur1 INTO a, b;
        IF done THEN
            LEAVE read_loop;
        END IF;

        SET @str = b;
        SET @patron1 = '<span class="hidden valorAntiguo">';
        SET @patron1Length = LENGTH(@patron1);
        SET @strLocate1 = LOCATE(@patron1, @str);
        SET @strLocate1 = @strLocate1 + @patron1Length;
        SET @strLocateFin1 = LENGTH(SUBSTRING_INDEX(@str, "</span>", 3)) - @strLocate1 + 1;
        SET @value1 = SUBSTRING(@str, @strLocate1, @strLocateFin1);

        SET @lineAnt1 = concat('<span class="hidden valorAntiguo">', @value1, '</span>');
        SET @line1 = concat('<p class="valorAntiguo"><strong>Valor antiguo: </strong>', @value1, '</p>');

        SET @patron1 = '<span class="hidden valorNuevo">';
        SET @patron1Length = LENGTH(@patron1);
        SET @strLocate1 = LOCATE(@patron1, @str);
        SET @strLocate1 = @strLocate1 + @patron1Length;
        SET @strLocateFin1 = LENGTH(SUBSTRING_INDEX(@str, "</span>", 4)) - @strLocate1 + 1;
        SET @value2 = SUBSTRING(@str, @strLocate1, @strLocateFin1);

        SET @lineAnt2 = concat('<span class="hidden valorNuevo">', @value2, '</span>');
        SET @line2 = concat('<p class="valorAntiguo"><strong>Valor antiguo: </strong>', @value2, '</p>');

        SET @val = concat('<div class="valoresOcultos"><hr>', @line1, @line2, '</div>');
        SET @valAnt = concat(@lineAnt1, @lineAnt2);

        SET @valueRep = REPLACE (@str, @valAnt, @val);

        UPDATE `smart`.`comercial_muro`
        SET
            missatge = @valueRep
        WHERE
            id = a;


    END LOOP;

    CLOSE cur1;

END
$$
DELIMITER ;
