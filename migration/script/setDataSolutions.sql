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

/* Borrar usuario */
DELETE FROM `smart_pro`.`user`
WHERE `regNumber` = 'u0608016';

/* Actualizar email nulos */
UPDATE `smart_pro`.`user`
SET `email` = 'dbarranco@caixabankpc.com'
where `regNumber` = 'u2108186';

UPDATE `smart_pro`.`user`
SET `email` = 'fagusti@caixabankpc.com'
where `regNumber` = 'u2109131';

UPDATE `smart_pro`.`user`
SET `email` = 'icotanoe@caixabankpc.com'
where `regNumber` = 'u2105007';

UPDATE `smart_pro`.`user`
SET `email` = 'asainz@caixabankpc.com'
where `regNumber` = 'u2108241';

UPDATE `smart_pro`.`user`
SET `email` = 'icotanoe@caixabankpc.com'
where `regNumber` = 'u2106710';
