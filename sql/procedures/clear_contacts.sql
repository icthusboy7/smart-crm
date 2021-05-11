-- ======================================================================= --
-- Removes inactive contacts if they can't be found on the master tables   --
-- ======================================================================= --

DROP PROCEDURE IF EXISTS clear_contacts;

DELIMITER $$
  CREATE PROCEDURE clear_contacts ()
  BEGIN
    DELETE IGNORE FROM contact WHERE is_active = false
      AND nif NOT IN (SELECT nif FROM maestra_cliente)
      AND nif NOT IN (SELECT nif FROM maestra_proveedor);
  END$$
DELIMITER ;
