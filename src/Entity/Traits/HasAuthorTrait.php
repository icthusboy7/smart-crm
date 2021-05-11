<?php

namespace App\Entity\Traits;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * This trait defines a column where an entity's author can be stored
 * and implements the interface {@code HasAuthor} to provide access to
 * it. Notice that it is expected for the author to be automatically
 * set by an entity listener to the current user value.
 *
 * @see App\Entity\Concerns\HasAuthor
 */
trait HasAuthorTrait
{
    /**
     * The user that authored this entity.
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $author = null;


    /**
     * Obtains this entity's author attribute.
     *
     * @return This entity's author
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }


    /**
     * Sets this entity's author attribute.
     *
     * @param User $author New author value
     *
     * @return This entity
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
