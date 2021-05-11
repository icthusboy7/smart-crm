<?php

namespace App\Core\Entity\Vocabs;

use App\Core\Entity\AbstractVocabulary;
use Doctrine\ORM\Mapping as ORM;

/**
 * Types of contacts.
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class ContactKind extends AbstractVocabulary
{
    /** If only a customer */
    private const CUSTOMER = [1, 'Customer'];

    /** If only a supplier */
    private const SUPPLIER = [2, 'Supplier'];

    /** If a supplier and also a customer */
    private const CUSTOMER_SUPPLIER = [3, 'Customer and supplier'];

    /** If the contact is temporary */
    private const TEMPORARY = [4, 'Temporary contact'];
}
