-- A view of users that are also managers

CREATE OR REPLACE VIEW view_managers AS
  SELECT
    id                           AS `id`,
    reg_number                   AS `code`,
    CONCAT(name, ' ', surname)   AS `name`,
    createdAt                    AS `created_at`,
    updatedAt                    AS `updated_at`
  FROM `user`
  WHERE EXISTS (
    SELECT *
    FROM `commercial_responsable`
    WHERE responsable = reg_number
  );
