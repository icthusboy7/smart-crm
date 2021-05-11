<?php

namespace App\Core\Entity;

use App\Core\Entity\Vocabs\ContactKind;
use App\Core\Entity\Vocabs\ContactStatus;
use Doctrine\ORM\Mapping as ORM;

/**
 * A contact is an entity or a person.
 *
 * @ORM\Entity(repositoryClass="App\Core\Repository\ContactRepository")
 */
class Contact extends AbstractEntity
{
    /**
     * This contact's Número de Identificación Fiscal (NIF).
     * @var string
     *
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $nif;

    /**
     * This contact's name.
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * This contact's postal address.
     * @var string
     *
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private $address;

    /**
     * This contact's phone number.
     * @var string
     *
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $phone;

    /**
     * This contact's email address.
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * Observations for this contact.
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * Whether this contact is active.
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * Type of this contact.
     * @var ContactKind
     *
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Vocabs\ContactKind")
     * @ORM\JoinColumn(nullable=false)
     */
    private $kind;


    /**
     * Obtains this entity's NIF attribute.
     *
     * @return This entity's NIF
     */
    public function getNif(): string
    {
        return $this->nif;
    }


    /**
     * Sets this entity's NIF attribute.
     *
     * @param User $nif New NIF value
     *
     * @return This entity
     */
    public function setNif(string $nif): self
    {
        $this->nif = $nif;

        return $this;
    }


    /**
     * Obtains this entity's name attribute.
     *
     * @return This entity's name
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Sets this entity's name attribute.
     *
     * @param string $name New name value
     *
     * @return This entity
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Obtains this entity's address attribute.
     *
     * @return This entity's address
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }


    /**
     * Sets this entity's address attribute.
     *
     * @param string $address New address value
     *
     * @return This entity
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }


    /**
     * Obtains this entity's phone attribute.
     *
     * @return This entity's phone
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }


    /**
     * Sets this entity's phone attribute.
     *
     * @param string $phone New phone value
     *
     * @return This entity
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }


    /**
     * Obtains this entity's email attribute.
     *
     * @return This entity's email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }


    /**
     * Sets this entity's email attribute.
     *
     * @param string $email New email value
     *
     * @return This entity
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }


    /**
     * Obtains this entity's notes attribute.
     *
     * @return This entity's notes
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }


    /**
     * Sets this entity's notes attribute.
     *
     * @param string $notes New notes value
     *
     * @return This entity
     */
    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }


    /**
     * Returns if this customer is an active contact.
     *
     * @return Whether it is a contact
     */
    public function getStatus(): ContactStatus
    {
        return ContactStatus::fromValue($this->isActive);
    }


    /**
     * Sets the contact status of this customer.
     *
     * @param ContactStatus $status New contact status
     *
     * @return This entity
     */
    public function setStatus(ContactStatus $status): self
    {
        $this->isActive = $status->getValue();

        return $this;
    }


    /**
     * Obtains this entity's kind attribute.
     *
     * @return This entity kind
     */
    public function getKind(): ContactKind
    {
        return $this->kind;
    }


    /**
     * Sets this entity's kind attribute.
     *
     * @param string $kind New kind value
     *
     * @return This entity
     */
    public function setKind(ContactKind $kind): self
    {
        $this->kind = $kind;

        return $this;
    }


    /**
     * @deprecated
     *
     * @return #getName()
     */
    public function getNombre(): string
    {
        return $this->getName();
    }


    /**
     * Returns if this contact is active.
     *
     * @return True if an active contact
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }


    /**
     * Checks if this contact is a customer. That is, if the kind
     * of this contact is either CUSTOMER or CUSTOMER_SUPPLIER.
     *
     * @return True if a customer
     */
    public function isCustomer(): bool
    {
        $kind = $this->kind;
        $s    = ContactKind::CUSTOMER();
        $c    = ContactKind::CUSTOMER_SUPPLIER();

        return ($s->equals($kind) || $c->equals($kind));
    }


    /**
     * Checks if this contact is a supplier. That is, if the kind
     * of this contact is either SUPPLIER or CUSTOMER_SUPPLIER.
     *
     * @return True if a supplier
     */
    public function isSupplier(): bool
    {
        $kind = $this->kind;
        $s    = ContactKind::SUPPLIER();
        $c    = ContactKind::CUSTOMER_SUPPLIER();

        return ($s->equals($kind) || $c->equals($kind));
    }

    /**
     * Remove all the additional information setted by user
     *
     * @return Contact
     */
    public function removeInformation(): self
    {
        $this->setAddress(null);
        $this->setPhone(null);
        $this->setEmail(null);
        $this->setNotes(null);

        return $this;
    }
}
