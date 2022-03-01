<?php

namespace MauticPlugin\MauticSmsapiBundle\Api;

use Mautic\LeadBundle\Entity\Lead;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Mautic\SmsBundle\Sms\TransportInterface;
use MauticPlugin\MauticSmsapiBundle\Core\SmsapiGateway;
use MauticPlugin\MauticSmsapiBundle\Core\SmsapiPluginInterface;
use Monolog\Logger;
use Smsapi\Client\SmsapiClientException;

class SmsapiApi implements TransportInterface
{
    private $logger;
    private $smsApiGateway;
    private $smsApiPlugin;
    private $integrationHelper;

    public function __construct(
        SmsapiPluginInterface $smsApiPlugin,
        SmsapiGateway $smsApiGateway,
        Logger $logger,
        IntegrationHelper $integrationHelper
    ) {
        $this->logger            = $logger;
        $this->smsApiGateway     = $smsApiGateway;
        $this->smsApiPlugin      = $smsApiPlugin;
        $this->integrationHelper = $integrationHelper;
    }

    public function sendSms(Lead $lead, $content)
    {
        $isPublished = $this->smsApiPlugin->isPublished();
        if (!$isPublished) {
            return false;
        }

        $sendername  = $this->smsApiPlugin->getSendername();
        $phoneNumber = $lead->getLeadPhoneNumber();
        if (null === $phoneNumber) {
            return false;
        }

        $integration = $this->integrationHelper->getIntegrationObject('Smsapi');
        $keys        = $integration->getDecryptedApiKeys();

        $token       = $keys['client_id'];
        $secret      = $keys['client_secret'];
        $campaign_id = $keys['campaign_id'];

        try {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL            => 'https://cloud.go4clients.com:8580/api/campaigns/sms/v1.0/'.$campaign_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_POSTFIELDS     => '{
                "message":"'.$content.'",
                "priority":"HIGH",
                "destinationsList": ["'.$phoneNumber.'"]
            }',
                CURLOPT_HTTPHEADER => [
                    'apiKey: '.$token,
                    'apiSecret: '.$secret,
                    'Content-Type: application/json',
                ],
            ]);

            curl_exec($curl);

            curl_close($curl);

            $this->logger->notice('Send SMS to '.$lead->getName().' on number '.$phoneNumber);
        } catch (SmsapiClientException $clientException) {
            $this->logger->error('Send SMS to '.$lead->getName().' fail'.$clientException->getMessage());

            return $clientException->getMessage();
        }

        return true;
    }
}
