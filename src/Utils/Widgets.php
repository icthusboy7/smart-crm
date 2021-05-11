<?php
/**
 * Widgets utils
 */
namespace App\Utils;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Widgets
 * @package App\Utils
 */
class Widgets
{
    /**
     * Inyeccion EntityManager
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * Token de usuario
     * @var null|object|string|\Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    private $user;

    /**
     * Widgets constructor.
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->user = ($tokenStorage->getToken()) ? $tokenStorage->getToken()->getUser() : $tokenStorage->getToken();
    }

    /**
     * Get active widgets
     * get parent widgets and get children widget
     * @param null $padre
     * @param null $role
     * @param null $originalRoles
     * @return array
     */
    public function getAction($padre = null, $role = null, $originalRoles = null)
    {
        /////////////
        // WIDGETS //
        /////////////
        $objWidget = $this->em->getRepository('App\Entity\DashboardWidgets');
        if (!is_null($padre)) {
            $objPadre = $objWidget->findOneBy(array(
                'isActive' => true,
                'nombre' => $padre
            ));
            $padre = $objPadre->getId();
        }

        // Get the widgets
        $return = $objWidget->findBy(
            array(
                'isActive' => true,
                'padre' => $padre
            ),
            array('orden' => 'ASC')
        );

        $plus = 0;
        foreach ($return as $key => $widget) {
            ////////////////////////
            // VARIABLES: Visitas //
            ////////////////////////
            if ($widget->getTitulo() == "visitas") {
                $dateToday = date('Y-m-d');
                $dateTomorrow = date('Y-m-d', strtotime('+1 day'));
                $hoy = 1;
                $manana = 2;
                $estaSemana = 3;
                $esteMes = 4;

                $return[$key]->widgetContent = array(
                    "hoy" => $hoy,
                    "manana" => $manana,
                    "estaSemana" => $estaSemana,
                    "esteMes" => $esteMes,
                );
            }
        }

        $return2 = [];
        foreach ($return as $key => $widget) {
            $Role = $this->em->getRepository('App\Entity\Role')->findOneBy(array('id'=>$widget->getRole()));
            if (in_array($Role, $originalRoles)) {
                    $return2[$key] = $return[$key];
            }
        }

        return $return2;
    }
}
