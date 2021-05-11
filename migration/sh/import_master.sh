#IMPORTACIÓN DE MAESTROS
echo "======================================="
echo ""
echo "IMPORTACIÓN DE MAESTROS..."$DB_LOCAL
php ../bin/console app:data familiaSub ZBE_SUBFAMILIA.csv 124 W
echo "Importando datos de familiaRel ... "
php ../bin/console app:data familiaRel ZBE_RELACION_FAM.csv 124 W
echo "Importando datos de solicitudes ... "
php -d memory_limit=258M ../bin/console app:data solicitudes Solicitudes.csv 124 W
echo "Importando datos de cotizaciones ... "
php -d memory_limit=-1 ../bin/console app:data cotizaciones Cotizaciones.csv 124 W
echo "Importando datos de oficinas ... "
php ../bin/console app:data oficinas OFICINAS.csv 124 W
echo "Importando datos de proveedores ... "
php -d memory_limit=258M ../bin/console app:data proveedores PROVEEDORES.csv 124 W
echo "Importando datos de empleados ... "
php -d memory_limit=258M ../bin/console app:data empleados EMPLEADOS_CXB.csv 124 W
echo "Importando datos de clientes ... "
php -d memory_limit=-1 ../bin/console app:data clientes CLIENTES.csv 124 W ## TODO: Solucion temporal para migración....
echo "Importando datos de comerciales ... "
php ../bin/console app:data comerciales comerciales.xlsx 124 W
echo "Importando datos de gestores ... "
php ../bin/console app:data gestores gestores.xlsx 124 W

# Synchronize the database with the master tables
echo "Synchronize master customer and master provider with contacts... "
php ../bin/console app:data:synch

