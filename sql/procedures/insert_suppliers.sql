-- ======================================================================= --
-- Inserts new suppliers from the master table into the contacts table     --
-- ======================================================================= --

DROP PROCEDURE IF EXISTS insert_suppliers;

DELIMITER $$
  CREATE PROCEDURE insert_suppliers ()
  BEGIN
    DECLARE ACTIVE_STATUS INT DEFAULT 0;
    DECLARE CUSTOMER_KIND INT DEFAULT 1;
    DECLARE SUPPLIER_KIND INT DEFAULT 2;
    DECLARE CUSTOMER_SUPPLIER_KIND INT DEFAULT 3;

    INSERT IGNORE INTO contact (
      nif,
      name,
      kind_id,
      is_active,
      created_at,
      updated_at
    )
    SELECT
      supplier.nif,
      supplier.nombre,
      SUPPLIER_KIND,
      ACTIVE_STATUS,
      CURRENT_TIMESTAMP,
      CURRENT_TIMESTAMP
    FROM maestra_proveedor supplier
    ON DUPLICATE KEY UPDATE
      nif =        IF(is_active, contact.nif, supplier.nif),
      name =       IF(is_active, contact.name, supplier.nombre),
      updated_at = IF(is_active, contact.updated_at, CURRENT_TIMESTAMP),
      address =    IF(is_active, contact.address, null),
      phone =      IF(is_active, contact.phone, null),
      email =      IF(is_active, contact.email, null),
      notes =      IF(is_active, contact.notes, null),
      kind_id =    IF(is_active, contact.kind_id, IF(kind_id = CUSTOMER_KIND OR kind_id = CUSTOMER_SUPPLIER_KIND,
                      CUSTOMER_SUPPLIER_KIND, SUPPLIER_KIND)
                   );
  END$$
DELIMITER ;
