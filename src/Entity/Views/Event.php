<?php

namespace App\Entity\Views;

use DateTimeInterface;
use JsonSerializable;

use App\Entity\Status;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Offers a view of visits as calendar events.
 *
 * @ORM\Entity(
 *   readOnly=true,
 *   repositoryClass="App\Repository\EventRepository"
 * )
 * @ORM\Table(name="view_events")
 */
class Event implements JsonSerializable
{
    /**
     * Visit identifier.
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * Event title.
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;


    /**
     * Event class name.
     * @var string
     *
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $class;


    /**
     * This event's visit author.
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="events")
     */
    private $author;


    /**
     * This event's status.
     * @var Status
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="events")
     */
    private $status;


    /**
     * Date when this event starts.
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $start;


    /**
     * Date when this event ends.
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $end;


    /**
     * Obtains this event's visit identifier.
     *
     * @return int      Unique ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Obtains this event's title.
     *
     * @return string   Text of the event
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }


    /**
     * Obtains this event's class name.
     *
     * @return string   Class name
     */
    public function getClass(): ?string
    {
        return $this->class;
    }


    /**
     * Obtains this event's author.
     *
     * @return int      User
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }


    /**
     * Obtains this event's status.
     *
     * @return int      Status
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }


    /**
     * Obtains this event's start date.
     *
     * @return DateTime     Start date
     */
    public function getStart(): ?DateTimeInterface
    {
        return $this->start;
    }


    /**
     * Obtains this event's end date.
     *
     * @return DateTime     End date
     */
    public function getEnd(): ?DateTimeInterface
    {
        return $this->end;
    }


    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'className' => $this->getClass(),
            'start' => $this->getStart(),
            'end' => $this->getEnd(),
        ];
    }
}
