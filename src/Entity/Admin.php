<?php
/**
 * Entidad Admin - Usuarios administradores
 */

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Admin
 * @ORM\Entity
 * @ORM\Table(name="admin")
 * @ORM\AttributeOverrides({
 *     @ORM\AttributeOverride(name="password",
 *          column=@ORM\Column(
 *              name     = "password",
 *              type     = "string",
 *              nullable=true
 *          )
 *      )
 * })
 */
class Admin extends BaseUser
{
    /**
     * Identificador Admin
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Numero de registro
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $regNumber;

    /**
     * Numero de registro Caixabank
     * @ORM\Column(type="string", length=8, unique=true)
     */
    private $regNumberCaixabank;

    /**
     * Nombre
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * Apellido
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * Fecha de creación de usuario
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime",nullable=true)
     */
    private $createdAt;

    /**
     * Fecha de última actualización del usuario
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime",nullable=true)
     */
    private $updatedAt;

    /**
     * Numero de veces logeado
     * @var int
     *
     * @ORM\Column(name="timesLogged", type="bigint")
     */
    private $timesLogged;

    /**
     * Flag para notificar usuario al habilitar
     * @var bool
     *
     * @ORM\Column(name="flagNotify", type="boolean")
     */
    protected $flagNotify;

    /**
     * Compañia
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="regNumber")
     */
    private $company;

    /**
     * Rol
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="regNumber")
     */
    private $role;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\OfficeFilters", mappedBy="admin")
     */
    private $officeFilters;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ContactFilters", mappedBy="admin")
     */
    private $contactFilters;

    /**
     * Admin constructor.
     */
    public function __construct()
    {
        $this->createdAt      = new \DateTime('now');
        $this->updatedAt      = new \DateTime('now');
        $this->timesLogged    = 0;
        $this->officeFilters  = new ArrayCollection();
        $this->contactFilters = new ArrayCollection();
    }

    /**
     * Funcion para saber el flagNotify de un usuario
     * @return bool
     */
    public function isFlagNotify()
    {
        return $this->flagNotify;
    }

    /**
     * Funcion para modificar el flagNotify de un usuario
     * @param bool $flagNotify
     */
    public function setFlagNotify($flagNotify)
    {
        $this->flagNotify = $flagNotify;
    }

    /**
     * Obtener compañia de un usuario
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Establecer compañia de un usuario
     * @param mixed $company
     */
    public function setCompany($company): void
    {
        $this->company = $company;
    }

    /**
     * Obtener numero de registro de un usuario
     * @return mixed
     */
    public function getRegNumber()
    {
        return $this->regNumber;
    }

    /**
     * Obtener compañia para un usuario
     * @param mixed $regNumber
     */
    public function setRegNumber($regNumber): void
    {
        $this->setUsername(strtolower($regNumber));
        $this->regNumber = strtolower($regNumber);
    }

    /**
     * Obtener nombre de un usuario
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Establecer nombre para un usuario
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * Obtener apellido de un usuario
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Establecer apellido para un usuario
     * @param mixed $surname
     */
    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }

    /**
     * Obtener numero de registro de CaixaBank de un usuario
     * @return mixed
     */
    public function getRegNumberCaixabank()
    {
        return $this->regNumberCaixabank;
    }

    /**
     * Establecer numero de registro de caixabank para un usuario
     * @param mixed $regNumberCaixabank
     */
    public function setRegNumberCaixabank($regNumberCaixabank): void
    {
        $this->regNumberCaixabank = strtolower($regNumberCaixabank);
    }

    /**
     * Devuelve roles para un usuario
     * @return array
     */
    public function getRoles()
    {
        //$roles = $this->roles;

//        $roles = [];
//
//        $roles = array_merge($roles, $this->getGroups()->getRoles());
//
//        // we need to make sure to have at least one role
//        $roles[] = static::ROLE_DEFAULT;
//
//        return array_unique($roles);

        return array($this->getRole()->getRoleName());
    }

    /**
     * Get role
     *
     * @return \App\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set role
     *
     * @param \App\Entity\Role $role
     * @return User
     */
    public function setRole(Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Obtener identificador de un usuario
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Obtener fecha de creación de un usuario
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Establecer fecha de creación para un usuario
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Obtener fecha de actualización de un usuario
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Establecer fecha de actulización para un usuario
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Obtener numero de veces que se ha logeado un usuario
     * @return int
     */
    public function getTimesLogged(): int
    {
        return $this->timesLogged;
    }

    /**
     * Establecer numero de veces logeado para un usuario
     * @param int $timesLogged
     */
    public function setTimesLogged(int $timesLogged): void
    {
        $this->timesLogged = $timesLogged;
    }

    /**
     * Establecer campo enabled para un usuario
     * @param bool $boolean
     * @return $this
     */
    public function setEnabled($boolean)
    {
        $this->enabled = (bool) $boolean;

        if ($boolean == true) {
            $this->setFlagNotify(true);
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
     * agrega los filtros de la oficina al admin
     * @param OfficeFilters $officeFilter
     *
     * @return Admin
     */
    public function addOfficeFilter(OfficeFilters $officeFilter): self
    {
        if (!$this->officeFilters->contains($officeFilter)) {
            $this->officeFilters[] = $officeFilter;
            $officeFilter->setAdmin($this);
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
            if ($officeFilter->getAdmin() === $this) {
                $officeFilter->setAdmin(null);
            }
        }

        return $this;
    }

    /**
     * obtiene los filtros del contacto
     * @return Collection|ContactFilters[]
     */
    public function getContactFilters(): Collection
    {
        return $this->contactFilters;
    }

    /**
     * agrega los filtros de la oficina al admin
     * @param ContactFilters $contactFilter
     *
     * @return Admin
     */
    public function addContactFilter(ContactFilters $contactFilter): self
    {
        if (!$this->contactFilters->contains($contactFilter)) {
            $this->contactFilters[] = $contactFilter;
            $contactFilter->setAdmin($this);
        }

        return $this;
    }

    /**
     * borra la relación entre los filtros del contacto y el usuario
     * @param ContactFilters $contactFilter
     *
     * @return Admin
     */
    public function removeContactFilter(ContactFilters $contactFilter): self
    {
        if ($this->contactFilters->contains($contactFilter)) {
            $this->contactFilters->removeElement($contactFilter);

            if ($contactFilter->getAdmin() === $this) {
                $contactFilter->setAdmin(null);
            }
        }

        return $this;
    }
}
