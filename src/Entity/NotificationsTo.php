<?php
/**
 * Entidad NotificationsTo
 */
namespace App\Entity;

use App\Entity\Concerns\HasTimestamps;
use App\Entity\Traits\HasTimestampsTrait;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clase NotificationsTo
 * @ORM\Entity(repositoryClass="App\Repository\NotificationsToRepository")
 */
class NotificationsTo implements HasTimestamps
{
    use HasTimestampsTrait;

    /**
     * Identificador Notification
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * User to notificate
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * Notification Id
     *
     * @var Notification
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Notifications")
     */
    private $notification;

    /**
     * Seen
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $seen;

    /**
     * flag seen
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $flagSeen;

    /**
     * is a group message
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $groupMessage;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Notifications
     */
    public function getNotification(): Notifications
    {
        return $this->notification;
    }

    /**
     * @return bool
     */
    public function isSeen(): bool
    {
        return $this->seen;
    }

    /**
     * @return bool
     */
    public function isFlagSeen(): bool
    {
        return $this->flagSeen;
    }

    /**
     * @return bool
     */
    public function isGroup(): bool
    {
        return $this->isGroup;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @param bool $flagSeen
     */
    public function setFlagSeen(bool $flagSeen): void
    {
        $this->flagSeen = $flagSeen;
    }

    /**
     * @param bool $groupMessage
     */
    public function setGroupMessage(bool $groupMessage): void
    {
        $this->groupMessage = $groupMessage;
    }

    /**
     * @param Notifications $notification
     */
    public function setNotification(Notifications $notification): void
    {
        $this->notification = $notification;
    }

    /**
     * @param bool $seen
     */
    public function setSeen(bool $seen): void
    {
        $this->seen = $seen;
    }
}
