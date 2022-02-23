<?php

namespace MauticPlugin\MauticSmsapiBundle\Api;

use Aws\Credentials\Credentials;
use Aws\Sns\SnsClient;
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

        try {
            $integration = $this->integrationHelper->getIntegrationObject('Smsapi');
            $keys        = $integration->getDecryptedApiKeys();

            $token  = $keys['client_id'];
            $secret = $keys['client_secret'];

            $client = new SnsClient(
                [
                    'credentials' => new Credentials(
                        $token,
                        $secret
                    ),
                    'region'  => 'us-east-1',
                    'version' => 'latest',
                ]
            );

            $client->publish([
                'Message'     => $content,
                'PhoneNumber' => $phoneNumber,
            ]);

            // $this->smsApiGateway->sendSms($phoneNumber, $content, $sendername);
            $this->logger->notice('Send SMS to '.$lead->getName().' on number '.$phoneNumber);
        } catch (SmsapiClientException $clientException) {
            $this->logger->error('Send SMS to '.$lead->getName().' fail'.$clientException->getMessage());

            return $clientException->getMessage();
        }

        return true;
    }
}
