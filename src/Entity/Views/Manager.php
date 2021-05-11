<?php

namespace App\Entity\Views;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Users that are themselves managers of other users. This entity is
 * a restricted view of the users table; therefore is not updatable.
 *
 * @ORM\Entity(
 *   readOnly=true,
 *   repositoryClass="App\Repository\ManagerRepository"
 * )
 * @ORM\Table(name="view_managers")
 */
class Manager
{
    /**
     * User identifier.
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * Identifier code of the user.
     * @var string
     *
     * @ORM\Column(type="string", length=8)
     */
    private $code;


    /**
     * Full name of the user.
     * @var string
     *
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private $name;


    /**
     * This entity's creation date.
     * @var DateTime
     *
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createdAt;


    /**
     * This entity's last update date.
     * @var DateTime
     *
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $updatedAt;


    /**
     * Obtains this manager's user identifier.
     *
     * @return int      Unique ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Obtains this manager's unique registry code.
     *
     * @return string   Unique code
     */
    public function getCode(): ?string
    {
        return $this->code;
    }


    /**
     * Obtains this manager's full name.
     *
     * @return string   Name and surname
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Obtains this entity's creation date attribute.
     *
     * @return DateTime This entity's creation date
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }


    /**
     * Obtains this entity's update date attribute.
     *
     * @return DateTime This entity's last update date
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }
}
