<?php

declare(strict_types=1);

namespace Smsapi\Client\Feature\Contacts\Fields;

use Smsapi\Client\Feature\Contacts\Fields\Bag\CreateContactFieldBag;
use Smsapi\Client\Feature\Contacts\Fields\Bag\DeleteContactFieldBag;
use Smsapi\Client\Feature\Contacts\Fields\Bag\FindContactFieldOptionsBag;
use Smsapi\Client\Feature\Contacts\Fields\Bag\UpdateContactFieldBag;
use Smsapi\Client\Feature\Contacts\Fields\Data\ContactField;
use Smsapi\Client\Feature\Contacts\Fields\Data\ContactFieldOption;

/**
 * @api
 */
interface ContactsFieldsFeature
{
    /**
     * @return ContactField[]
     */
    public function findFields(): array;

    public function createField(CreateContactFieldBag $createContactFieldBag): ContactField;

    public function updateField(UpdateContactFieldBag $updateContactFieldBag): ContactField;

    public function deleteField(DeleteContactFieldBag $deleteContactFieldBag);

    /**
     * @return ContactFieldOption[]
     */
    public function findFieldOptions(FindContactFieldOptionsBag $findContactFieldOptionsBag): array;
}
