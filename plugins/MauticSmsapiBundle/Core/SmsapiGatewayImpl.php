<?php

namespace MauticPlugin\MauticSmsapiBundle\Core;

use Exception;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Smsapi\Client\Feature\Sms\Bag\SendSmsBag;
use Throwable;

class SmsapiGatewayImpl implements SmsapiGateway
{
    private $connection;

    /**
     * @var IntegrationHelper
     */
    private $integrationHelper;

    public function __construct(
        Connection $connection,
        IntegrationHelper $integrationHelper
        ) {
        $this->connection        = $connection;
        $this->integrationHelper = $integrationHelper;
    }

    public function isConnected(): bool
    {
        $integration = $this->integrationHelper->getIntegrationObject('Smsapi');

        if (!$integration || !$integration->getIntegrationSettings()->getIsPublished()) {
            throw new Exception();
        }

        $service = $this->connection->smsapiClient();

        try {
            $service->profileFeature()->findProfile();
        } catch (Throwable $apiErrorException) {
            return false;
        }

        return true;
    }

    public function getSendernames(): array
    {
        $sendernames = $this->connection->smsapiClient()->smsFeature()->sendernameFeature()->findSendernames();
        $array       = [];
        foreach ($sendernames as $sendername) {
            $array[$sendername->sender] = $sendername->sender;
        }

        return $array;
    }

    public function sendSms(string $phoneNumber, string $content, string $sendername)
    {
        $service       = $this->connection->smsapiClient();
        $sms           = new SendSmsBag();
        $sms->to       = $phoneNumber;
        $sms->from     = $sendername;
        $sms->encoding = 'utf-8';
        $sms->message  = $content;

        $service->smsFeature()->sendSms($sms);
    }

    public function getProfile(): Profile
    {
        $service = $this->connection->smsapiClient();
        $profile = $service->profileFeature()->findProfile();

        return new Profile($profile->points);
    }
}
