-- ======================================================================= --
-- Synchronize contacts with the master tables                             --
-- ======================================================================= --

DROP PROCEDURE IF EXISTS synch_contacts;

DELIMITER $$
  CREATE PROCEDURE synch_contacts ()
  BEGIN
    START TRANSACTION;

    CALL clear_contacts();
    CALL insert_customers();
    CALL insert_suppliers();

    COMMIT;
  END$$
DELIMITER ;
