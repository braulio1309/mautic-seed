<?php

declare(strict_types=1);

namespace Smsapi\Client\Feature\Bag;

/**
 * @api
 *
 * @property int $offset
 * @property int $limit
 */
trait PaginationBag
{
    public function setPaging(int $offset, int $limit): self
    {
        $this->offset = $offset;
        $this->limit  = $limit;

        return $this;
    }
}
