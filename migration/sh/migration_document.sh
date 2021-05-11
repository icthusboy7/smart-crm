#!/usr/bin/env bash
#DOCUMENT FILES
echo "======================================="
echo ""
echo "Ejecutando cambio de estructura de documentos..."
php ../bin/console app:document-migration $UPLOAD_ORIGIN $UPLOAD_DESTINATION
echo "======================================="
echo "Pasos a seguir si hay documentos con error."
echo ""
echo "1) Ver los documentos que están en la carpeta: "$UPLOAD_ORIGIN
echo "2) Ejecutar el siguiente script para obtener las filas a editar:"
echo "SELECT * FROM smart.document WHERE path is not NULL;"
echo "3) Editar la fila copiando el nombre del archivo después de _-_ y poniendo la extensión y teniendo en cuenta el valor que se encuentra en la columna PATH"
echo "UPDATE smart.document SET name = 'NOMBRE_ARCHIVO.EXTENSION' WHERE path = 'PATH';"
echo "4) Procesar nuevamante el proceso:"
echo "php ../bin/console app:document-migration 'PATH ORIGIN' 'PATH DESTINATION'"
