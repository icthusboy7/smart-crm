<?php

namespace App\Entity\Concerns;

use App\Entity\User;

/**
 * An entity that has an author may implement this interface to provide
 * access to the underlying user object.
 */
interface HasAuthor
{
    /**
     * Obtains this entity's author attribute.
     *
     * @return This entity's author
     */
    public function getAuthor(): ?User;


    /**
     * Sets this entity's author attribute.
     *
     * @param User $author New author value
     */
    public function setAuthor(?User $author);
}
