<?php

namespace App\DataFixtures;

use App\Entity\DashboardWidgets;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DashBoardWidgetsFixture extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $count             = 0;
        $columnMapFunction = [
            'padre_id'     => 'setPadre',
            'role_id'      => 'setRole',
            'nombre'       => 'setNombre',
            'titulo'       => 'setTitulo',
            'ruta_interna' => 'setRutaInterna',
            'href'         => 'setHref',
            'descripcion'  => 'setDescripcion',
            'isPlantilla'  => 'setIsPlantilla',
            'plantilla'    => 'setPlantilla',
            'orden'        => 'setOrden',
            'fa_icon'      => 'setFaIcon',
            'attributes'   => 'setAttributes',
            'isActive'     => 'setIsActive',
        ];

        $dashboardWidgets = [
            ['padre_id' => null, 'role_id' => '20', 'nombre' => 'Informes', 'titulo' => 'informes', 'ruta_interna' => 'ultimos_reportes', 'href' => null, 'descripcion' => 'informes.description', 'isPlantilla' => '0', 'plantilla' => null, 'orden' => '2', 'fa_icon' => 'fas fa-clipboard-list', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => null, 'role_id' => '20', 'nombre' => 'Comites', 'titulo' => 'comites', 'ruta_interna' => 'detalle_comite', 'href' => null, 'descripcion' => null, 'isPlantilla' => '0', 'plantilla' => null, 'orden' => '1', 'fa_icon' => 'fas fa-chart-bar', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => null, 'role_id' => '20', 'nombre' => 'Operaciones', 'titulo' => 'operaciones', 'ruta_interna' => 'indice_operaciones', 'href' => null, 'descripcion' => null, 'isPlantilla' => '0', 'plantilla' => null, 'orden' => '3', 'fa_icon' => 'fas fa-desktop', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => null, 'role_id' => '20', 'nombre' => 'AlertasInforma', 'titulo' => 'alertas-informa', 'ruta_interna' => 'alertas_informa', 'href' => null, 'descripcion' => null, 'isPlantilla' => '0', 'plantilla' => null, 'orden' => '4', 'fa_icon' => 'fas fa-exclamation-triangle', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => null, 'role_id' => '20', 'nombre' => 'AlertasAsnef', 'titulo' => 'alertas-asnef', 'ruta_interna' => 'homepage_equifax', 'href' => null, 'descripcion' => null, 'isPlantilla' => '0', 'plantilla' => null, 'orden' => '5', 'fa_icon' => 'fas fa-exclamation-triangle', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => null, 'role_id' => '20', 'nombre' => 'Buscar', 'titulo' => 'buscar-en-la-red', 'ruta_interna' => null, 'href' => null, 'descripcion' => null, 'isPlantilla' => '0', 'plantilla' => null, 'orden' => '6', 'fa_icon' => 'fas fa-search-plus', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => null, 'role_id' => '20', 'nombre' => 'Incidencias', 'titulo' => 'gestor-incidencias', 'ruta_interna' => null, 'href' => 'ticket', 'descripcion' => null, 'isPlantilla' => '0', 'plantilla' => null, 'orden' => '7', 'fa_icon' => 'fas fa-ticket-alt', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => null, 'role_id' => '20', 'nombre' => 'ControlDoc', 'titulo' => 'control-documental', 'ruta_interna' => 'documental_listado_peticiones', 'href' => null, 'descripcion' => null, 'isPlantilla' => '0', 'plantilla' => null, 'orden' => '8', 'fa_icon' => 'fas fa-file', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => null, 'role_id' => '20', 'nombre' => 'AreaComercial', 'titulo' => 'area-comercial', 'ruta_interna' => 'dashboard_comerciales', 'href' => null, 'descripcion' => null, 'isPlantilla' => '0', 'plantilla' => null, 'orden' => '9', 'fa_icon' => 'fas fa-money-check', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => null, 'role_id' => '20', 'nombre' => 'AreaRiesgos', 'titulo' => 'area-riesgos', 'ruta_interna' => 'dashboard_riesgos', 'href' => null, 'descripcion' => null, 'isPlantilla' => '0', 'plantilla' => null, 'orden' => '10', 'fa_icon' => 'fas fa-balance-scale', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => '9', 'role_id' => '20', 'nombre' => 'ComercialVisitas', 'titulo' => 'visitas', 'ruta_interna' => 'comercial_visitas', 'href' => null, 'descripcion' => null, 'isPlantilla' => '1', 'plantilla' => 'area-comercial/widgets/visitas.html.twig', 'orden' => '1', 'fa_icon' => 'fas fa-users', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => '9', 'role_id' => '20', 'nombre' => 'ComercialPipelines', 'titulo' => 'pipelines', 'ruta_interna' => 'comercial_expedientes', 'href' => null, 'descripcion' => null, 'isPlantilla' => '1', 'plantilla' => 'area-comercial/widgets/pipelines.html.twig', 'orden' => '2', 'fa_icon' => 'fas fa-id-card', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => '9', 'role_id' => '20', 'nombre' => 'ComercialSolicitudes', 'titulo' => 'Agenda de Tareas', 'ruta_interna' => 'tasks', 'href' => null, 'descripcion' => null, 'isPlantilla' => '1', 'plantilla' => 'area-comercial/widgets/tasks.html.twig', 'orden' => '3', 'fa_icon' => 'fas fa-handshake', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => '9', 'role_id' => '20', 'nombre' => 'ComercialOperaciones', 'titulo' => 'operaciones', 'ruta_interna' => 'comercial_operaciones', 'href' => null, 'descripcion' => null, 'isPlantilla' => '0', 'plantilla' => null, 'orden' => '4', 'fa_icon' => 'fas fa-desktop', 'attributes' => null, 'isActive' => '0'],
            ['padre_id' => '9', 'role_id' => '20', 'nombre' => 'ComercialContactos', 'titulo' => 'contactos', 'ruta_interna' => 'gestion_contactos', 'href' => null, 'descripcion' => null, 'isPlantilla' => '1', 'plantilla' => 'area-comercial/widgets/contactos.html.twig', 'orden' => '5', 'fa_icon' => 'fas fa-list-ul', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => '9', 'role_id' => '20', 'nombre' => 'ComercialOficinas', 'titulo' => 'oficinas', 'ruta_interna' => 'gestion_oficinas', 'href' => null, 'descripcion' => null, 'isPlantilla' => '1', 'plantilla' => 'area-comercial/widgets/oficinas.html.twig', 'orden' => '5', 'fa_icon' => 'fas fa-building', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => '9', 'role_id' => '21', 'nombre' => 'ComercialTaskType', 'titulo' => 'taskType', 'ruta_interna' => 'task_types', 'href' => null, 'descripcion' => null, 'isPlantilla' => '1', 'plantilla' => 'area-comercial/widgets/task_types.html.twig', 'orden' => '8', 'fa_icon' => 'fas fa-exclamation-triangle', 'attributes' => null, 'isActive' => '1'],
            ['padre_id' => '9', 'role_id' => '11', 'nombre' => 'ComarcialAlertas', 'titulo' => 'alertas', 'ruta_interna' => 'gestion_alertas', 'href' => null, 'descripcion' => null, 'isPlantilla' => '1', 'plantilla' => 'area-comercial/widgets/alertas.html.twig', 'orden' => '7', 'fa_icon' => 'fas fa-exclamation-triangle', 'attributes' => null, 'isActive' => '1'],
        ];
        foreach ($dashboardWidgets as $key => $row) {
            $count++;
            $content = new DashboardWidgets();
            foreach ($row as $column => $value) {
                if ('role_id' === $column) {
                    $content->setRole(
                        $this->getReference(Role::class.'_'.$value)
                    );
                } elseif (('padre_id' === $column) && isset($value)) {
                    $content->setPadre(
                        $this->getReference(DashboardWidgets::class.'_'.$value)
                    );
                } elseif (isset($columnMapFunction[$column])) {
                    $content->{$columnMapFunction[$column]}($value);
                }
            }
            $manager->persist($content);
            $this->addReference(DashboardWidgets::class.'_'.$count, $content);
        }
        $manager->flush();
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        // TODO: Implement getGroups() method.
        return [
            'dev',
            'pre',
            'test',
        ];
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            RoleFixture::class,
        ];
    }
}
