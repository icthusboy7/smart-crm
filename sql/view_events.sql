-- A view of calendar events for the visits

CREATE OR REPLACE VIEW view_events AS
  SELECT
    visit.id            AS `id`,
    reason.name         AS `title`,
    (
      CASE status_id
        WHEN 1 THEN 'pending'
        WHEN 2 THEN 'done'
        WHEN 3 THEN 'canceled'
      END
    )                   AS `class`,
    visit.author_id     AS `author_id`,
    visit.status_id     AS `status_id`,
    visit.date_ini      AS `start`,
    visit.date_fin      AS `end`
  FROM `visit`
  LEFT JOIN `reason`
    ON visit.reason_id = reason.id;
