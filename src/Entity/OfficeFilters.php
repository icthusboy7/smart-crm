<?php
/**
 *  Entidad OfficeFilters
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clase OfficeFilters
 * @ORM\Entity(repositoryClass="App\Repository\OfficeFiltersRepository")
 */
class OfficeFilters
{
    /**
     * identificador de OfficeFilters
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * nombre de OfficeFilters
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * Relación de user con OfficeFilters
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="officeFilters")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * Relación de user con OfficeFilters
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin", inversedBy="officeFilters")
     * @ORM\JoinColumn(nullable=true)
     */
    private $admin;

    /**
     * query de OfficeFilters
     * @ORM\Column(type="string", length=255)
     */
    private $query;

    /**
     * obtiene el id de OfficeFilters
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * obtiene el nombre de OfficeFilters
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * establece el nombre de OfficeFilters
     * @param string $name
     * @return OfficeFilters
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * obtiene el user de OfficeFilters
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * establece el user de OfficeFilters
     * @param User|null $user
     * @return OfficeFilters
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * obtiene el admin de OfficeFilters
     * @return Admin|null
     */
    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    /**
     * establece el admin de OfficeFilters
     * @param Admin|null $admin
     *
     * @return OfficeFilters
     */
    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * obtiene el query de OfficeFilters
     * @return null|string
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * establece el query de OfficeFilters
     * @param string $query
     * @return OfficeFilters
     */
    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }
}
