<?php
/**
 * Entidad ContactFilters
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ContactsFilters
 * @ORM\Entity(repositoryClass="App\Repository\ContactFiltersRepository")
 */
class ContactFilters
{
    /**
     * Identificador
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Nombre
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * Búsqueda guardada (?query=)
     * @ORM\Column(type="string", length=255)
     */
    private $query;

    /**
     * Usuario propietario del filtro
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * Relación de user con ContactFilters
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin", inversedBy="contactFilters")
     * @ORM\JoinColumn(nullable=true)
     */
    private $admin;

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Establecer nombre
     * @param string $name
     * @return ContactsFilters
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Obtener query
     * @return null|string
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * Establecer query
     * @param string $query
     * @return ContactsFilters
     */
    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Obtener usuario
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Establecer usuario
     * @param User|null $user
     * @return ContactsFilters
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * obtiene el admin de ContactFilters
     * @return Admin|null
     */
    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    /**
     * establece el admin de ContactFilters
     * @param Admin|null $admin
     *
     * @return ContactFilters
     */
    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

        return $this;
    }
}
