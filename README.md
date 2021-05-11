
INSTALACIÓN DE LA APLICACIÓN
========================================================================================================
Requisitos

    apache2             - Servidor web
    composer            - Gestor de paquetes para Symfony
    mysql-server        - Servidor de base de datos
    php7.2              - Intérprete de php
    rabbitMQ            - Gestor datos en memoria (colas)


Instalación de bundles

    composer require

Modificar .env con variables de entorno

    DATABASE_URL=mysql://user:@host:3306/database
    MAILER_URL=smtp://host:25?encryption=ssl&auth_mode=login&username=username&password=password
    LDAP_HOST=172.18.255.205
    LDAP_PORT=389
    LDAP_ENCRYPTION=tls
    LDAP_SEARCH_DN="cn=read-only-admin,dc=dacfi,dc=com"
    LDAP_SEARCH_PASSWORD=password
    FILES_DIRECTORY=path/to/files/from/app/root/
    TRANSLATIONS_DIRECTORY=translations/   
    RABBIT_HOST=host
    RABBIT_PORT=port
    RABBIT_USER=username
    RABBIT_PASSWORD=password
    RABBIT_VHOST=virtualhost

Creamos estructura de base de datos con doctrine

    php bin/console doctrine:schema:update --force

Ejecutamos script de creación de datos base, se encuentra en sql/deploy.sql

El usuario que se crea por defecto es:

    User:       admin
    Password:   12345


Variables de PHP que deben modificarse:

    memory_limit = 512MB
    post_max_size = 128MB
    upload_max_filesize = 128MB

### Coding Standards
Se aplica el estandard especificado en la documentación de Symfony.
https://symfony.com/doc/current/contributing/code/standards.html#symfony-coding-standards-in-detail

## Fixtures

Los Fixtures nos ayudan a testear la aplicación y permiten crear pruebas unitarias con datos lo más parecidos a los reales posibles

####Nuevos Fixtures

    php bin/console make:fixtures "NombreContentidoFixture"

####Inicializar la BBDD
Para crear contenido dummy es necesario tener importados todos los maestros, por este motivo se recomienda lanzar.
Teniendo en cuenta que los maestros tiene que estar situados en la carpeta files/ del proyecto y que són los siguientes:
Maestros:
CLIENTES.csv, Control.csv, Cotizaciones.csv, EMPLEADOS_CXB.csv, OFICINAS.csv,
PROVEEDORES.csv, Solicitudes.csv, ZBE_Familia.csv, ZBE_RELACION_FAM.csv, ZBE.SUBFAMILIA.csv
Contenido enriquecido en formato Excel:
gestores.xlsx, comerciales.xlsx


    php bin/console doctrine:schema:update --force
    php bin/console doctrine:migrations:execute --up 20190930160545
    sh migration/migration.sh

####Borrar contenido dummy
Dependiendo del estado de la BBDD puede ser necesario lanzar el script siguiente
Script: Comando SQL de utilidad para borrar contenido y hacer pruebas

    mysql -u root -p database < sql/remove_dummy.sql

####Crear contenido dummy

    php bin/console doctrine:fixtures:load --append --group=dev
