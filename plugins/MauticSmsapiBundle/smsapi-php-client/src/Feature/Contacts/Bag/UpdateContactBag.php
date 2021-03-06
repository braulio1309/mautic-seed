<?php

declare(strict_types=1);

namespace Smsapi\Client\Feature\Contacts\Bag;

/**
 * @api
 *
 * @property int    $phoneNumber
 * @property string $email
 * @property string $firstName
 * @property string $lastName
 * @property string $gender
 * @property string $birthdayDate
 * @property string $description
 * @property string $city
 * @property string $source
 */
class UpdateContactBag
{
    /** @var string */
    public $contactId;

    public function __construct(string $contactId)
    {
        $this->contactId = $contactId;
    }

    public function setName(string $firstName, string $lastName): self
    {
        $this->firstName = $firstName;
        $this->lastName  = $lastName;

        return $this;
    }
}
