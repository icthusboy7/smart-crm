# Use to join Documents with comercial_muro_adjuntos before schema update
UPDATE comercial_muro_adjuntos, document
SET comercial_muro_adjuntos.doc_id = document.id
WHERE comercial_muro_adjuntos.filename = document.name
and comercial_muro_adjuntos.createdAt = document.createdAt
