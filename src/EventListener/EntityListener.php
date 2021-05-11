<?php

namespace App\EventListener;

use DateTime;

use App\Entity\Concerns\HasAuthor;
use App\Entity\Concerns\HasTimestamps;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

/**
 * Listens to lifecycle events of entities and updates their concern
 * attributes if any are defined on them (ie. updates the values for
 * the columns 'created_at', 'updated_at', 'author_id', etc).
 *
 * Notice that for the 'author_id' field to be set automatically, the
 * user must be authenticated and thus the route require security.
 */
class EntityListener
{

    /**
     * Security service instance.
     * @var Security
     */
    private $security;


    /**
     * Constructs an instance of this listener.
     *
     * @param Security $security Security service
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    /**
     * Invoked for the initial database persist operation of an entity.
     *
     * @param LifecycleEventArgs $args Event arguments
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        // Set the entity author to the current logged in user
        // unless the author was already set

        if ($entity instanceof HasAuthor) {
            if (is_null($entity->getAuthor())) {
                $user = $this->security->getUser();
                $entity->setAuthor($user);
            }
        }

        // Set the entity's creation and last update dates to now

        if ($entity instanceof HasTimestamps) {
            $now = new DateTime();
            $entity->setCreatedAt($now);
            $entity->setUpdatedAt($now);
        }
    }


    /**
     * Invoked before each database updat of an entity.
     *
     * @param LifecycleEventArgs $args Event arguments
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        // Set the entity's last update date to now

        if ($entity instanceof HasTimestamps) {
            $now = new DateTime();
            $entity->setUpdatedAt($now);

            if (is_null($entity->getCreatedAt())) {
                $entity->setCreatedAt($now);
            }
        }
    }
}
