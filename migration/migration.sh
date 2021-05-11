#!/usr/bin/env bash

## Import config
. config/config.sh

 #Show configuration parameters
echo "Configuracion de la migracion:"
echo ""
echo "HOST: "$SERVER
echo "DB: "$DB
echo "======================================="
echo "HOST origin: "$SERVER_ORIGIN
echo "DB origin: "$DB_ORIGIN
echo ""
echo "======================================="

##Generando dump Origen
#echo "======================================="
#echo ""
#echo "Generando dump Origen..."$DB
#mysqldump --user=$USER_ORIGIN --password=$PASSWORD_ORIGIN --host=$SERVER_ORIGIN --port=$PORT_ORIGIN --compress --skip-lock-tables --result-file=dumps/dump_origin.sql $DB

##Crear DB con la estructura a migrar
echo "Crear schema local con la estructura a migrar..."$DB_LOCAL
php ../bin/console doctrine:database:create

##Creando schema origen en local
echo "======================================="
echo ""
echo "Creando schema origen en local..."$DB_LOCAL_ORIGIN
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 < ./script/createLocalSchemaOrigin.sql
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL -e "CALL MIG_createLocalSchemaOrigin('$DB_LOCAL_ORIGIN');"
#
##Ejecutando dump Origen
echo "======================================="
echo ""
echo "Ejecutando dump Origen..."$DB_LOCAL_ORIGIN
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL_ORIGIN < ./dumps/dump_origin.sql

#Creando la estructura de datos
echo "======================================="
echo ""
echo "Creando la estructura de datos..."$DB_LOCAL
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ./script/create.sql

#MIGRATION SYMFONY comercialUsuarios
echo "======================================="
echo ""
echo "ejecutando archivos de migración symfony ComercialUsuarios..."$DB_LOCAL
php ../bin/console doctrine:migrations:execute --up 20191203095235
php ../bin/console doctrine:migrations:execute --up 20191203100133
php ../bin/console doctrine:migrations:execute --up 20191203110045
php ../bin/console doctrine:migrations:execute --up 20191203121104


#Adaptación de datos de la tabla origen a la tabla destino
echo "======================================="
echo ""
echo "Adaptación de datos de la tabla origen a la tabla destino"
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ./script/insert.sql
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL -e "CALL MIG_map('$DB_LOCAL_ORIGIN', '$DB_LOCAL');"
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL -e "CALL MIG_document('$DB__LOCAL_ORIGIN', '$DB_LOCAL');"
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL -e "CALL MIG_task('$DB__LOCAL_ORIGIN', '$DB_LOCAL');"

#Adaptación de datos que no se pueden adaptar en el insert
echo "======================================="
echo ""
echo "Adaptación de datos que no se pueden adaptar en el insert "$DB
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ./script/update.sql

#Adaptación de datos. Agregar index, constraints y otros elementos que se quitaron para la migración
echo "======================================="
echo ""
echo "Adaptación de datos. Agregar index, constraints y otros elementos que se quitaron para la migración "$DB
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ./script/alter.sql

#Setear datos de uso
echo "======================================="
echo ""
echo "Setear datos de uso..."$DB_LOCAL
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ./script/setData.sql

#MIGRATION DIFF SYMFONY
echo "======================================="
echo ""
echo "ejecutando creación de tablas nuevas..."$DB_LOCAL
php ../bin/console doctrine:migrations:execute --up 20191119152416

##CREATE STORED PROCEDURES
echo "======================================="
echo ""
echo "CREAR STORED PROCEDURES..."$DB_LOCAL
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ../sql/procedures/clear_contacts.sql
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ../sql/procedures/insert_customers.sql
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ../sql/procedures/insert_suppliers.sql
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ../sql/procedures/synch_contacts.sql
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ./script/insert_contacts.sql

##CREATE VIEWS
echo "======================================="
echo ""
echo "CREAR VIEWS..."$DB_LOCAL
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ../sql/view_events.sql
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ../sql/view_managers.sql

#MASTER IMPORT
. sh/import_master.sh

#TRATAMIENTO DE CONTACTOS
echo "======================================="
echo ""
echo "Sincronizar Contactos..."$DB_LOCAL
##INSERT DE contact_aux to contact
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL -e "CALL insert_contacts();"

#TRATAMIENTO DE MITSSAGE MURO, REEMPLAZAR HTML
echo "======================================="
echo ""
echo "Tratamiento de mitssage muro, reemplazar HTML..."$DB_LOCAL
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ./script/updateComercialMuroMissatge.sql
mysql --host=$SERVER --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL -e "CALL MIG_updateComercialMuroMissatge();"

#GENERAR SMART DUMP
echo "======================================="
echo ""
echo "Generando SMART dump..."$DB_LOCAL
mysqldump --user=$USER_LOCAL --password=$PASSWORD_LOCAL --host=$SERVER_LOCAL --port=$PORT_LOCAL --compress --skip-lock-tables --result-file=dumps/dump.sql $DB_LOCAL
echo ""
##EJECUTAR DUMP EN SERVER DESTINO
#mysql --host=$SERVER --port=$PORT --user=$USER --password=$PASSWORD $DB_LOCAL < ./dumps/dump.sql

#DOCUMENT FILES
. sh/migration_document.sh
echo ""
VERIFICAR SERVER DESTINO
echo "======================================="
echo ""
echo "Verificar la migración SMART..."$DB_LOCAL
mysql --host=$SERVER_LOCAL --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL < ./script/count.sql
mysql --host=$SERVER --port=$PORT_LOCAL --user=$USER_LOCAL --password=$PASSWORD_LOCAL --default-character-set=utf8 $DB_LOCAL -e "CALL MIG_count('$DB_ORIGIN', '$DB_LOCAL');"
echo ""
echo "======================================="
echo "Proceso de migración finalizado!"
echo "======================================="
