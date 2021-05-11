<?php
/**
 *  Entidad WidgetUserOrden
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clase WidgetUserOrden
 * @ORM\Entity(repositoryClass="App\Repository\WidgetUserOrdenRepository")
 */
class WidgetUserOrden
{

    /**
     * Identificador del widget
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\DashboardWidgets", inversedBy="widgetUserOrdens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $widget_dashboard;

    /**
     * Identificador del usuario
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="widgetUserOrdens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Ordena los widgets
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden;

    /**
     * Obtiene el WidgetDashboard
     * @return DashboardWidgets|null
     */
    public function getWidgetDashboard(): ?DashboardWidgets
    {
        return $this->widget_dashboard;
    }

    /**
     * Setea WidgetDashboard
     * @param DashboardWidgets|null $widget_dashboard
     * @return WidgetUserOrden
     */
    public function setWidgetDashboard(?DashboardWidgets $widget_dashboard): self
    {
        $this->widget_dashboard = $widget_dashboard;

        return $this;
    }

    /**
     * Obtiene el usuario
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setea el usuario
     * @param User|null $user
     * @return WidgetUserOrden
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Obtiene el orden de los widgets
     * @return int|null
     */
    public function getOrden(): ?int
    {
        return $this->orden;
    }

    /**
     * setea el orden de los widgets
     * @param int $Orden
     * @return WidgetUserOrden
     */
    public function setOrden(int $Orden): self
    {
        $this->orden = $Orden;

        return $this;
    }
}
