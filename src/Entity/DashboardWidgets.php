<?php
/**
 * Entidad DashboardWidgets
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class DashboardWidget
 * @ORM\Entity(repositoryClass="App\Repository\DashboardWidgetsRepository")
 */
class DashboardWidgets
{
    /**
     * Identificador
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Nombre widgets
     * @ORM\Column(name="nombre", type="string", length=100, unique=true)
     */
    private $nombre;

    /**
     * Widget padre
     * @ORM\ManyToOne(targetEntity="App\Entity\DashboardWidgets")
     */
    private $padre;

    /**
     * Titulo widget
     * @ORM\Column(name="titulo", type="string", length=100)
     */
    private $titulo;

    /**
     * Id role
     * @ORM\ManyToOne(targetEntity="App\Entity\Role")
     */
    private $role;

    /**
     * Ruta intera widget
     * @ORM\Column(name="ruta_interna", type="string", length=100, nullable=true)
     */
    private $rutaInterna;

    /**
     * Href widget
     * @ORM\Column(name="href", type="string", length=255, nullable=true)
     */
    private $href;

    /**
     * Descripcion widget
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * Boolean isPlantilla
     * @var boolean
     *
     * @ORM\Column(name="isPlantilla", type="boolean")
     */
    private $isPlantilla = true;

    /**
     * Twig plantilla
     * @ORM\Column(name="plantilla", type="string", length=100, nullable=true)
     */
    private $plantilla;

    /**
     * Orden por defecto widget
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden;

    /**
     * Icono de widget
     * @ORM\Column(name="fa_icon", type="string", length=100, nullable=true)
     */
    private $faIcon;

    /**
     * Attributos widget
     * @ORM\Column(name="attributes", type="string", length=255, nullable=true)
     */
    private $attributes;

    /**
     * Widget activo
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive = true;

    /**
     * Fecha de creación
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * Fecha de actualización
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Relacion con widgetorden
     * @ORM\OneToMany(targetEntity="App\Entity\WidgetUserOrden", mappedBy="widget_dashboard", orphanRemoval=true)
     */
    private $widgetUserOrdens;


    /**
     * DashboardWidgets constructor.
     */
    public function __construct()
    {
        $this->widgetUserOrdens = new ArrayCollection();
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * Funcion to string
     * @return string
     */
    public function __toString()
    {
        return (string)$this->nombre;
    }

    /**
     * Obtener isPlantilla
     * @return bool|null
     */
    public function getisPlantilla(): ?bool
    {
        return $this->isPlantilla;
    }

    /**
     * Establecer isPlantilla
     * @param bool $isPlantilla
     * @return DashboardWidgets
     */
    public function setIsPlantilla(bool $isPlantilla): self
    {
        $this->isPlantilla = $isPlantilla;

        return $this;
    }

    /**
     * Obtener ID
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtener nombre
     * @return null|string
     */
    public function getNombre(): ?string
    {
           return $this->nombre;
    }

    /**
     * Establecer nombre
     * @param string $nombre
     * @return DashboardWidgets
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Obtener padre
     * @return DashboardWidgets|null
     */
    public function getPadre(): ?self
    {
        return $this->padre;
    }

    /**
     * Establecer Padre
     * @param DashboardWidgets|null $padre
     * @return DashboardWidgets
     */
    public function setPadre(?self $padre): self
    {
        $this->padre = $padre;

        return $this;
    }

    /**
     * Obtener titulo
     * @return null|string
     */
    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    /**
     * Establecer titulo
     * @param string $titulo
     * @return DashboardWidgets
     */
    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Obtener role
     * @return Role|null
     */
    public function getRole(): ?Role
    {
        return $this->role;
    }

    /**
     * Establecer role
     * @param Role|null $role
     * @return DashboardWidgets
     */
    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Obtener ruta interna
     * @return null|string
     */
    public function getRutaInterna(): ?string
    {
        return $this->rutaInterna;
    }

    /**
     * Establecer ruta interna
     * @param null|string $rutaInterna
     * @return DashboardWidgets
     */
    public function setRutaInterna(?string $rutaInterna): self
    {
        $this->rutaInterna = $rutaInterna;

        return $this;
    }

    /**
     * Obtener HREF
     * @return null|string
     */
    public function getHref(): ?string
    {
        return $this->href;
    }

    /**
     * Establecer HREF
     * @param null|string $href
     * @return DashboardWidgets
     */
    public function setHref(?string $href): self
    {
        $this->href = $href;

        return $this;
    }

    /**
     * Obtener descripcion
     * @return null|string
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * Establecer descripcion
     * @param null|string $descripcion
     * @return DashboardWidgets
     */
    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Obtener plantilla
     * @return null|string
     */
    public function getPlantilla(): ?string
    {
        return $this->plantilla;
    }

    /**
     * Establecer plantilla
     * @param null|string $plantilla
     * @return DashboardWidgets
     */
    public function setPlantilla(?string $plantilla): self
    {
        $this->plantilla = $plantilla;

        return $this;
    }

    /**
     * Obtener orden
     * @return mixed
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Establecer orden
     * @param $orden
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
    }

    /**
     * Obtener icono widget
     * @return null|string
     */
    public function getFaIcon(): ?string
    {
        return $this->faIcon;
    }

    /**
     * Establecer icono widget
     * @param null|string $faIcon
     * @return DashboardWidgets
     */
    public function setFaIcon(?string $faIcon): self
    {
        $this->faIcon = $faIcon;

        return $this;
    }

    /**
     * Obtener atributos
     * @return null|string
     */
    public function getAttributes(): ?string
    {
        return $this->attributes;
    }

    /**
     * Establecer atributos
     * @param null|string $attributes
     * @return DashboardWidgets
     */
    public function setAttributes(?string $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Obtener estado Activo
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * Establecer estado activo
     * @param bool $isActive
     * @return DashboardWidgets
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Obtener fecha de creación
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Establecer fecha de creación
     * @param \DateTimeInterface|null $createdAt
     * @return DashboardWidgets
     */
    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Obtener fecha de actualización
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Establecer fecha de actualización
     * @param \DateTimeInterface|null $updatedAt
     * @return DashboardWidgets
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Obtener orden widget relación
     * @return Collection|WidgetUserOrden[]
     */
    public function getWidgetUserOrdens(): Collection
    {
        return $this->widgetUserOrdens;
    }

    /**
     * Añadir orden widget relación
     * @param WidgetUserOrden $widgetUserOrden
     * @return DashboardWidgets
     */
    public function addWidgetUserOrden(WidgetUserOrden $widgetUserOrden): self
    {
        if (!$this->widgetUserOrdens->contains($widgetUserOrden)) {
            $this->widgetUserOrdens[] = $widgetUserOrden;
            $widgetUserOrden->setWidgetDashboardId($this);
        }

        return $this;
    }

    /**
     * Eliminar relacion orden widget
     * @param WidgetUserOrden $widgetUserOrden
     * @return DashboardWidgets
     */
    public function removeWidgetUserOrden(WidgetUserOrden $widgetUserOrden): self
    {
        if ($this->widgetUserOrdens->contains($widgetUserOrden)) {
            $this->widgetUserOrdens->removeElement($widgetUserOrden);
            // set the owning side to null (unless already changed)
            if ($widgetUserOrden->getWidgetDashboardId() === $this) {
                $widgetUserOrden->setWidgetDashboardId(null);
            }
        }

        return $this;
    }
}
