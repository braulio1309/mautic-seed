<?php

declare(strict_types=1);

namespace Smsapi\Client\Feature\ShortUrl;

use Smsapi\Client\Feature\ShortUrl\Bag\CreateShortUrlLinkBag;
use Smsapi\Client\Feature\ShortUrl\Data\ShortUrlLink;
use Smsapi\Client\SmsapiClientException;

/**
 * @api
 */
interface ShortUrlFeature
{
    /**
     * @throws SmsapiClientException
     */
    public function createShortUrlLink(CreateShortUrlLinkBag $createShortUrlLink): ShortUrlLink;
}
