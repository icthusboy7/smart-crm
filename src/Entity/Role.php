<?php
/**
 * Entidad Role
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Util\UserManipulator;

/**
 * Clase Role
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role
{
    /**
     * identificador del role
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * nombre del role
     * @var string
     *
     * @ORM\Column(name="roleName", type="string", length=50, unique=true)
     */
    private $roleName;

    /**
     * Relación del role con el usuario
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="role")
     */
    private $regNumber;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->regNumber = new ArrayCollection();
    }

    /**
     * Retorna el nombre del role
     * @return string
     */
    public function __toString()
    {
        return (string)$this->roleName;
    }

    /**
     * obtiene el identificador del role
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * establece el identificador del role
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * obtiene el nombre del role
     * @return string
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * establece el nombre del role
     * @param string $roleName
     */
    public function setRoleName(string $roleName): void
    {
        $this->roleName = $roleName;
    }

    /**
     * Agrega la relación entre el Role y el usuario
     * @param \App\Entity\User $regNumber
     * @return Role
     */
    public function addRegNumber(User $regNumber)
    {
        $this->regNumber[] = $regNumber;

        return $this;
    }

    /**
     * Remueve la relación entre role y usuario
     * @param \App\Entity\User $regNumber
     */
    public function removeRegNumber(User $regNumber)
    {
        $this->regNumber->removeElement($regNumber);
    }
}
