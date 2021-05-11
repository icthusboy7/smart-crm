<?php
/**
 *  Entidad AlertasFilters
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clase AlertasFilters
 * @ORM\Entity(repositoryClass="App\Repository\ComercialAlertasFiltersRepository")
 */
class ComercialAlertasFilters
{
    /**
     * @var int
     * identificador de AlertasFilters
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * nombre de AlertasFilters
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var user
     * Relación de user con UserFilters
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="officeFilters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var string
     * query de AlertasFilters
     * @ORM\Column(type="string", length=255)
     */
    private $query;

    /**
     * obtiene el id de AlertasFilters
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * obtiene el nombre de AlertasFilters
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * establece el nombre de AlertesFilters
     * @param string $name
     *
     * @return ComercialAlertasFilters
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
     *
     * @return ComercialAlertasFilters
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * obtiene el query de OfficeFilters
     *
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * establece el query de OfficeFilters
     * @param string $query
     *
     * @return ComercialAlertasFilters
     */
    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }
}
