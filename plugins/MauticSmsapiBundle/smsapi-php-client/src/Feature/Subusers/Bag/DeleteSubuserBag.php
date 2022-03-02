<?php

declare(strict_types=1);

namespace Smsapi\Client\Feature\Subusers\Bag;

/**
 * @api
 */
class DeleteSubuserBag
{
    public $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
