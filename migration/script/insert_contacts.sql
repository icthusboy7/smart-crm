-- =========================================================== --
-- Inserts new contacts from contactAux, Migration process     --
-- =========================================================== --

DROP PROCEDURE IF EXISTS insert_contacts;

DELIMITER $$
  CREATE PROCEDURE insert_contacts ()
  BEGIN
    DECLARE ACTIVE_STATUS INT DEFAULT 1;
    DECLARE CONTACT_KIND INT DEFAULT 4;

    INSERT IGNORE INTO smart.contact (
      nif,
      name,
      kind_id,
      is_active,
      created_at,
      updated_at,
      address,
      phone,
      email
    )
    SELECT
      contactAux.nif,
      contactAux.name,
      CONTACT_KIND,
      ACTIVE_STATUS,
      contactAux.created_at,
      contactAux.created_at,
      contactAux.address,
      contactAux.phone,
      contactAux.email
    FROM smart.contact_aux contactAux
    ON DUPLICATE KEY UPDATE
      nif        = IF(contactAux.nif <> contact.nif, contactAux.nif, contact.nif),
      name       = IF(contactAux.name <> contact.name, contactAux.name, contact.name),
      created_at = contactAux.created_at,
      updated_at = contactAux.created_at,
      address    = IF(contactAux.address <> contact.address, contactAux.address, contact.address),
      phone      = IF(contactAux.phone <> contact.phone, contactAux.phone, contact.phone),
      email      = IF(contactAux.email <> contact.email, contactAux.email, contact.email),

      is_active = IF(contactAux.nif <> contact.nif         OR
                     contactAux.name <> contact.name       OR
                     contactAux.address <> contact.address OR
                     contactAux.phone <> contact.phone     OR
                     contactAux.email <> contact.email, ACTIVE_STATUS, contact.is_active);
  END$$
DELIMITER ;
