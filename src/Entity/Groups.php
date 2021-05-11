<?php
/**
 * Entidad Grupos
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Clase Grupos
 * @ORM\Entity(repositoryClass="App\Repository\GroupsRepository")
 */
class Groups
{
    /**
     * Identificador de Groups
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * nombre de Groups
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * fecha de creaciòn de Groups
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * fecha de actualización de Groups
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Relación entre Users y Groups
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="myGroups")
     */
    private $users;

    /**
     * Groups constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * obtiene el id de Groups
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * obtiene el nombre de Groups
     * @return null|string
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * establece el nombre de Groups
     * @param string $nombre
     * @return Groups
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * obtiene la fecha de creación de Groups
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * establece la fecha de creación de Groups
     * @param \DateTimeInterface $createdAt
     * @return Groups
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * obtiene la fecha de actualización de groups
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * establece la fecha de actualización de Groups
     * @param \DateTimeInterface|null $updatedAt
     * @return Groups
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * obtiene el usuario
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * añade la relación usuario
     * @param User $user
     * @return Groups
     */
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    /**
     * remueve la relación usuario
     * @param User $user
     * @return Groups
     */
    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }
}
