<?php

namespace App\Core\Entity\Vocabs;

use App\Core\Entity\AbstractBoolean;

/**
 * Contact status values.
 */
class ContactStatus extends AbstractBoolean
{
    /** If it is an active contact */
    private const ACTIVE = [true, 'Active contact'];

    /** If the contact is not active */
    private const INACTIVE = [false, 'Inactive contact'];
}
