<?php

declare(strict_types=1);

namespace Smsapi\Client\Feature\Vms\Bag;

/**
 * @api
 *
 * @property string $from
 * @property int    $try
 * @property int    $interval
 * @property bool   $skipGsm
 * @property bool   $checkIdx
 * @property bool   $test
 */
class SendVmsBag
{
    /** @var string */
    public $to;

    /** @var string */
    public $tts;

    public function __construct(string $receiver, string $text)
    {
        $this->to  = $receiver;
        $this->tts = $text;
    }
}
