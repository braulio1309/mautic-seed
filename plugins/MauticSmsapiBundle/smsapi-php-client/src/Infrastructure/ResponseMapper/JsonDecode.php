<?php

declare(strict_types=1);

namespace Smsapi\Client\Infrastructure\ResponseMapper;

use stdClass;

/**
 * @internal
 */
class JsonDecode
{
    /**
     * @throws JsonException
     */
    public function decode(string $json): stdClass
    {
        $decoded      = json_decode($json);
        $errorMessage = json_last_error_msg();
        $errorCode    = json_last_error();

        if (JSON_ERROR_NONE !== $errorCode) {
            throw new JsonException($errorMessage, $errorCode, $json);
        }

        return $decoded;
    }
}
