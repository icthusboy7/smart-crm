<?php
/**
 * Entidad User
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Clase user, FosUserBundle, set password null
 * @ORM\Entity
 *
 * @UniqueEntity("regNumber")
 *
 * @ORM\Table(name="user")
 * @ORM\AttributeOverrides({
 *     @ORM\AttributeOverride(name="password",
 *          column=@ORM\Column(
 *              name     = "password",
 *              type     = "string",
 *              nullable=true
 *          )
 *      ),
 * @ORM\AttributeOverride(name="email",
 *          column=@ORM\Column(
 *              length=180,
 *              nullable=true
 *          )
 *      ),
 * @ORM\AttributeOverride(name="emailCanonical",
 *          column=@ORM\Column(
 *              length=180,
 *              nullable=true
 *          )
 *      )
 * })
 */
class User extends BaseUser
{
    /**
     * identificador del usuario
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * regNumber del usuario
     * @var string
     *
     * @ORM\Column(type="string", length=8, unique=true)
     */
    private $regNumber;

    /**
     * regNumberCaixabank del usuario
     * @var string
     *
     * @ORM\Column(type="string", length=8)
     */
    private $regNumberCaixabank;

    /**
     * nombre del usuario
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * apellido del usuario
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * Fecha de creación del registro
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime",nullable=true)
     */
    private $createdAt;

    /**
     * Fecha de actualización del registro
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime",nullable=true)
     */
    private $updatedAt;

    /**
     * Número de veces en las que se loguea
     * @var int
     *
     * @ORM\Column(name="timesLogged", type="bigint")
     */
    private $timesLogged;

    /**
     * Notifica si se ha enviado correo de registro al usuario
     * @var bool
     *
     * @ORM\Column(name="flagNotify", type="boolean")
     */
    protected $flagNotify;

    /**
     * Empresa del usuario
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="regNumber")
     */
    private $company;

    /**
     * Relación entre User y document
     * @var mixed
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="user")
     */
    private $documents;

    /**
     * Rol del usuario
     *
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="regNumber")
     */
    private $role;

    /**
     * Orden de los widget del usuario
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\WidgetUserOrden", mappedBy="user")
     */
    private $widgetUserOrdens;

    /**
     * Grupo del usuario
     *
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Groups", mappedBy="users")
     */
    private $myGroups;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\OfficeFilters", mappedBy="user")
     */
    private $officeFilters;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComercialCotizacionFavoritos", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $cotizacionesFavoritas;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComercialExpedienteFavoritos", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $expedientesFavoritos;

    /**
     * Relación entre User y ComercialUsuarioZona
     * @var mixed
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComercialUsuarioZona", mappedBy="responsable")
     */
    private $responsableZona;

    /**
     * Relación entre User y ComercialUsuario usuario
     * @var mixed
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComercialUsuario", mappedBy="usuario")
     */
    private $comercialusuario;

    /**
     * Relación entre User y ComercialUsuario responsable
     * @var mixed
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComercialUsuario", mappedBy="responsable")
     */
    private $comercialusuarioresponsable;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->createdAt             = new \DateTime('now');
        $this->updatedAt             = new \DateTime('now');
        $this->timesLogged           = 0;
        $this->widgetUserOrdens      = new ArrayCollection();
        $this->myGroups              = new ArrayCollection();
        $this->officeFilters         = new ArrayCollection();
        $this->cotizacionesFavoritas = new ArrayCollection();
        $this->expedientesFavoritos  = new ArrayCollection();
        $this->documents             = new ArrayCollection();
    }

    /**
     * obtiene la empresa
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * setea la empresa
     * @param mixed $company
     */
    public function setCompany($company): void
    {
        $this->company = $company;
    }

    /**
     * obtiene el regNumber
     * @return mixed
     */
    public function getRegNumber()
    {
        return $this->regNumber;
    }

    /**
     * setea el regNumber
     * @param mixed $regNumber
     */
    public function setRegNumber($regNumber): void
    {
        $this->setUsername(strtolower($regNumber));
        $this->regNumber = strtolower($regNumber);
    }

    /**
     * obtiene el nombre
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * setea el nombre
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * obtiene el apellido
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * setea el apellido
     * @param mixed $surname
     */
    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }

    /**
     * obtiene RegNumberCaixabank
     * @return mixed
     */
    public function getRegNumberCaixabank()
    {
        return $this->regNumberCaixabank;
    }

    /**
     * setea RegNumberCaixabank
     * @param mixed $regNumberCaixabank
     */
    public function setRegNumberCaixabank($regNumberCaixabank): void
    {
        $this->regNumberCaixabank = strtolower($regNumberCaixabank);
    }

    /**
     * obtiene el rol del usuario
     * @return array
     */
    public function getRoles(): array
    {
        return [$this->getRole()->getRoleName()];
    }

    /**
     * obtiene los roles
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * Set role
     * @param Role $role
     *
     * @return User
     */
    public function setRole(?Role $role = null): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * obtiene el identificador del usuario
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * obtiene la fecha de creación del usuario
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * setea la fecha de creación del usuario
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * obtiene la fecha de la actualización del usuario
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * setea la fecha de actualización del usuario
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * obtiene las veces que se ha logueado
     * @return int
     */
    public function getTimesLogged(): int
    {
        return $this->timesLogged;
    }

    /**
     * setea las veces que se ha logueado
     * @param int $timesLogged
     */
    public function setTimesLogged(int $timesLogged): void
    {
        $this->timesLogged = $timesLogged;
    }

    /**
     * obtiene si el usuario está permitido a usar el sistema o no
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * setea si el usuario está permitido a usar el sistema o no
     * @param bool $boolean
     *
     * @return $this
     */
    public function setEnabled($boolean): self
    {
        $this->enabled = (bool) $boolean;

        //flagNotify to send email one time
        if ($boolean && !$this->isFlagNotify()) {
            $this->setFlagNotify(true);
        }

        return $this;
    }

    /**
     * obtiene flagNotify
     * @return bool|null
     */
    public function isFlagNotify(): ?bool
    {
        return $this->flagNotify;
    }

    /**
     * setea flagNotify
     * @param bool $flagNotify
     */
    public function setFlagNotify(bool $flagNotify): void
    {
        $this->flagNotify = $flagNotify;
    }

    /**
     * Obtiene el orden de los widgets del usuario
     * @return Collection|WidgetUserOrdens[]
     */
    public function getWidgetUserOrdens(): Collection
    {
        return $this->widgetUserOrdens;
    }

    /**
     * setea el orden de los widgets del usuario
     * @param WidgetUserOrden $widgetUserOrden
     *
     * @return User
     */
    public function addWidgetUserOrden(WidgetUserOrden $widgetUserOrden): self
    {
        if (!$this->widgetUserOrdens->contains($widgetUserOrden)) {
            $this->widgetUserOrdens[] = $widgetUserOrden;
            $widgetUserOrden->setUser($this);
        }

        return $this;
    }

    /**
     * Remueve el orden de los widgets del usuario
     * @param WidgetUserOrden $widgetUserOrden
     *
     * @return User
     */
    public function removeWidgetUserOrden(WidgetUserOrden $widgetUserOrden): self
    {
        if ($this->widgetUserOrdens->contains($widgetUserOrden)) {
            $this->widgetUserOrdens->removeElement($widgetUserOrden);
            // set the owning side to null (unless already changed)
            if ($widgetUserOrden->getUser() === $this) {
                $widgetUserOrden->setUser(null);
            }
        }

        return $this;
    }

    /**
     * obtiene el grupo del usuario
     * @return Collection|Groups[]
     */
    public function getMyGroups(): Collection
    {
        return $this->myGroups;
    }

    /**
     * agrega el usuario al grupo
     * @param Groups $myGroup
     *
     * @return User
     */
    public function addMyGroup(Groups $myGroup): self
    {
        if (!$this->myGroups->contains($myGroup)) {
            $this->myGroups[] = $myGroup;
            $myGroup->addUser($this);
        }

        return $this;
    }

    /**
     * borra la relación entre el grupo y el usuario
     * @param Groups $myGroup
     *
     * @return User
     */
    public function removeMyGroup(Groups $myGroup): self
    {
        if ($this->myGroups->contains($myGroup)) {
            $this->myGroups->removeElement($myGroup);
            $myGroup->removeUser($this);
        }

        return $this;
    }

    /**
     * obtiene los filtros de la oficina
     * @return Collection|OfficeFilters[]
     */
    public function getOfficeFilters(): Collection
    {
        return $this->officeFilters;
    }

    /**
     * agrega los filtros de la oficina al usuario
     * @param OfficeFilters $officeFilter
     *
     * @return User
     */
    public function addOfficeFilter(OfficeFilters $officeFilter): self
    {
        if (!$this->officeFilters->contains($officeFilter)) {
            $this->officeFilters[] = $officeFilter;
            $officeFilter->setUser($this);
        }

        return $this;
    }

    /**
     * borra la relación entre los filtros de la oficina y el usuario
     * @param OfficeFilters $officeFilter
     *
     * @return User
     */
    public function removeOfficeFilter(OfficeFilters $officeFilter): self
    {
        if ($this->officeFilters->contains($officeFilter)) {
            $this->officeFilters->removeElement($officeFilter);
            // set the owning side to null (unless already changed)
            if ($officeFilter->getUser() === $this) {
                $officeFilter->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ComercialCotizacionFavoritos[]
     */
    public function getCotizacionesFavoritas(): Collection
    {
        return $this->cotizacionesFavoritas;
    }

    /**
     * Añadir cotizacion favorita
     *
     * @param ComercialCotizacionFavoritos $cotizacionesFavorita
     *
     * @return User
     */
    public function addCotizacionesFavorita(ComercialCotizacionFavoritos $cotizacionesFavorita): self
    {
        if (!$this->cotizacionesFavoritas->contains($cotizacionesFavorita)) {
            $this->cotizacionesFavoritas[] = $cotizacionesFavorita;
            $cotizacionesFavorita->setUser($this);
        }

        return $this;
    }

    /**
     * Eliminar Contizacion favorita
     *
     * @param ComercialCotizacionFavoritos $cotizacionesFavorita
     *
     * @return User
     */
    public function removeCotizacionesFavorita(ComercialCotizacionFavoritos $cotizacionesFavorita): self
    {
        if ($this->cotizacionesFavoritas->contains($cotizacionesFavorita)) {
            $this->cotizacionesFavoritas->removeElement($cotizacionesFavorita);
            // set the owning side to null (unless already changed)
            if ($cotizacionesFavorita->getUser() === $this) {
                $cotizacionesFavorita->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Obtener los expedientes favoritos
     *
     * @return Collection|ComercialExpedienteFavoritos[]
     */
    public function getExpedientesFavoritos(): Collection
    {
        return $this->expedientesFavoritos;
    }

    /**
     * Añadir expediente a favoritos
     *
     * @param ComercialExpedienteFavoritos $expedientesFavorito
     *
     * @return User
     */
    public function addExpedientesFavorito(ComercialExpedienteFavoritos $expedientesFavorito): self
    {
        if (!$this->expedientesFavoritos->contains($expedientesFavorito)) {
            $this->expedientesFavoritos[] = $expedientesFavorito;
            $expedientesFavorito->setUser($this);
        }

        return $this;
    }

    /**
     * Eliminar expediente de favoritos
     *
     * @param ComercialExpedienteFavoritos $expedientesFavorito
     *
     */
    public function removeExpedientesFavorito(ComercialExpedienteFavoritos $expedientesFavorito): void
    {
        if (!$this->expedientesFavoritos->contains($expedientesFavorito)) {
            return;
        }
        $this->expedientesFavoritos->removeElement($expedientesFavorito);
        // set the owning side to null (unless already changed)
        if ($expedientesFavorito->getUser() !== $this) {
            return;
        }
        $expedientesFavorito->setUser(null);
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    /**
     * agrega el usuario al document
     * @param Document $document
     *
     * @return User
     */
    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setUser($this);
        }

        return $this;
    }
}
