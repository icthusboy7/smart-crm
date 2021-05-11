/*
***********************
***** SET DATA ********
***********************
 */
SET FOREIGN_KEY_CHECKS=1;
SET SQL_SAFE_UPDATES = 0;

UPDATE `smart`.`admin`
SET password = '$2y$13$bNeFeXGTle6QfW3lsfV7BOe9HdgD4isoXQLshH6pfiPg5wZJqCHl.'
where reg_number = 'lacucaracha';

UPDATE `smart`.`user`
set `password` = '$2y$13$bNeFeXGTle6QfW3lsfV7BOe9HdgD4isoXQLshH6pfiPg5wZJqCHl.'
where username = 'u2108881';

UPDATE `smart`.`dashboard_widgets`
SET role_id = 2
where nombre = 'AreaComercial';

UPDATE `smart`.`dashboard_widgets`
SET isPlantilla = 1,
    plantilla = 'area-comercial/widgets/visitas.html.twig'
where nombre = 'ComercialVisitas';

UPDATE `smart`.`dashboard_widgets`
SET isPlantilla = 1,
    plantilla = 'area-comercial/widgets/contactos.html.twig'
where nombre = 'ComercialContactos';

UPDATE `smart`.`dashboard_widgets`
SET isPlantilla = 1,
    plantilla = 'area-comercial/widgets/tasks.html.twig',
    ruta_interna = 'tasks'
where nombre = 'ComercialSolicitudes';

UPDATE `smart`.`dashboard_widgets`
SET isPlantilla = 1,
    plantilla = 'area-comercial/widgets/task_types.html.twig'
where nombre = 'ComercialTaskType';

UPDATE `smart`.`dashboard_widgets`
SET isPlantilla = 1,
    plantilla = 'area-comercial/widgets/alertas.html.twig'
where nombre = 'ComercialAlertas';

UPDATE `smart`.`dashboard_widgets`
SET isPlantilla = 1,
    plantilla = 'area-comercial/widgets/oficinas.html.twig'
where nombre = 'ComercialOficinas';

UPDATE `smart`.`dashboard_widgets`
SET isPlantilla = 1,
    plantilla = 'area-comercial/widgets/pipelines.html.twig'
where nombre = 'ComercialPipelines';

UPDATE `smart`.`dashboard_widgets`
SET isPlantilla = 1,
    plantilla = 'area-comercial/widgets/pipelines.html.twig'
where nombre = 'ComercialPipelines';

UPDATE `smart`.`dashboard_widgets`
SET ruta_interna = 'uploads_list'
where nombre = 'mantenimiento-ssales';

UPDATE `smart`.`dashboard_widgets`
SET ruta_interna = 'peticion_cotizacion_form'
where nombre = 'PeticionCotizacion';

UPDATE `smart`.`dashboard_widgets`
SET ruta_interna = 'peticion_cotizacion_form'
where nombre = 'formulario_pet_coti';

UPDATE `smart`.`dashboard_widgets`
SET ruta_interna = 'uploads_list'
where nombre = 'mantenimiento-ssales';

INSERT INTO `smart`.`dashboard_widgets` (`padre_id`, `nombre`, `titulo`, `ruta_interna`, `href`, `descripcion`, `isPlantilla`, `plantilla`, `orden`, `fa_icon`, `attributes`, `isActive`, `createdAt`, `updatedAt`) VALUES (9, 'ComercialAlertas', 'alertas', 'gestion_alertas', NULL, NULL, 1, 'area-comercial/widgets/alertas.html.twig', 7, 'fas fa-exclamation-triangle', 'style=\"background-color: grey;\"', 1, CURDATE(), NULL);
INSERT INTO `smart`.`dashboard_widgets` (`padre_id`, `nombre`, `titulo`, `ruta_interna`, `href`, `descripcion`, `isPlantilla`, `plantilla`, `orden`, `fa_icon`, `attributes`, `isActive`, `createdAt`, `updatedAt`) VALUES (9, 'ComercialTaskType', 'taskType', 'task_types', NULL, NULL, 1, 'area-comercial/widgets/task_types.html.twig', 8, 'fas fa-exclamation-triangle', 'style=\"background-color: grey;\"', 1, CURDATE(), NULL);

UPDATE `smart`.`dashboard_widgets`
SET role_id = 2
where padre_id = 9 and role_id is null;

/* document_type */
INSERT INTO `smart`.`document_type` (`code`, `description`)
VALUES ('ANOTHER','OTRO');

/* document */
UPDATE `smart`.`document`
SET `document_type_id` = (SELECT t.`id` FROM `smart`.`document_type` as t WHERE t.`code` = 'ANOTHER')
WHERE `document_type_id` IS NULL;

/* charge */
INSERT INTO `smart`.`charge` (`name`)
VALUES ('Otro');
