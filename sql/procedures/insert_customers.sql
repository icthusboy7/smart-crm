-- ======================================================================= --
-- Inserts new customers from the master table into the contacts table     --
-- ======================================================================= --

DROP PROCEDURE IF EXISTS insert_customers;

DELIMITER $$
  CREATE PROCEDURE insert_customers ()
  BEGIN
    DECLARE ACTIVE_STATUS INT DEFAULT 0;
    DECLARE CUSTOMER_KIND INT DEFAULT 1;

    INSERT IGNORE INTO contact (
      nif,
      name,
      kind_id,
      is_active,
      created_at,
      updated_at
    )
    SELECT
      customer.nif,
      customer.nombre,
      CUSTOMER_KIND,
      ACTIVE_STATUS,
      CURRENT_TIMESTAMP,
      CURRENT_TIMESTAMP
    FROM maestra_cliente customer
    ON DUPLICATE KEY UPDATE
      nif =        IF(is_active, contact.nif, customer.nif),
      name =       IF(is_active, contact.name, customer.nombre),
      updated_at = IF(is_active, contact.updated_at, CURRENT_TIMESTAMP),
      kind_id =    IF(is_active, contact.kind_id, CUSTOMER_KIND),
      address =    IF(is_active, contact.address, null),
      phone =      IF(is_active, contact.phone, null),
      email =      IF(is_active, contact.email, null),
      notes =      IF(is_active, contact.notes, null);
  END$$
DELIMITER ;
