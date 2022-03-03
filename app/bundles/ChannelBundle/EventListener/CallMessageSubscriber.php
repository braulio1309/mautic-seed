<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ChannelBundle\EventListener;

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\Event\PendingEvent;
use Mautic\ChannelBundle\ChannelEvents;
use Mautic\ChannelBundle\Form\Type\CallMessageSendType;
use Mautic\ChannelBundle\Model\CallMessageModel;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Mautic\SmsBundle\Model\SmsModel;
use Mautic\SmsBundle\Sms\TransportChain;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CallMessageSubscriber implements EventSubscriberInterface
{
    /**
     * @var SmsModel
     */
    private $smsModel;

    /**
     * @var TransportChain
     */
    private $transportChain;

    /**
     * @var IntegrationHelper
     */
    private $integrationHelper;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $token;

    const CALL_URI = 'https://cloud.go4clients.com:8580/api/campaigns/voice/v1.0/';

    public function __construct(
        CallMessageModel $callModel,
        TransportChain $transportChain,
        IntegrationHelper $integrationHelper
    ) {
        $this->callModel          = $callModel;
        $this->transportChain     = $transportChain;
        $this->integrationHelper  = $integrationHelper;
        $integration              = $this->integrationHelper->getIntegrationObject('Smsapi');

        $keys = $integration->getDecryptedApiKeys();

        $this->token       = $keys['client_id'];
        $this->secret      = $keys['client_secret'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            CampaignEvents::CAMPAIGN_ON_BUILD     => ['onCampaignBuild', 0],
            ChannelEvents::ON_CALL_VOICE          => ['onCallVoice', 0],
        ];
    }

    public function onCampaignBuild(CampaignBuilderEvent $event)
    {
        $event->addAction(
            'call.send_call_voice',
            [
                'label'            => 'Call Voice',
                'description'      => 'Send a call voice to your contacts',
                'batchEventName'   => ChannelEvents::ON_CALL_VOICE,
                'formType'         => CallMessageSendType::class,
                'formTypeOptions'  => ['update_select' => 'campaignevent_properties_sms'],
                'formTheme'        => 'MauticChannelBundle:Calls\CallMessageSend',
                'channel'          => 'call',
                'channelIdField'   => 'call',
            ]
        );
    }

    /**
     * @return $this
     */
    public function onCallVoice(PendingEvent $event)
    {
        $config      = $event->getEvent()->getProperties();
        $callId      = (int) $config['call'];
        $call        = $this->callModel->getEntity($callId);
        $triggerDate = ($event->getEvent()->getTriggerDate()) ? $event->getEvent()->getTriggerDate()->format('Y-m-d\TH:i:s.\0\0\0\Z') : null;
        $contacts    = $event->getContacts();
        $phones      = '';

        foreach ($contacts as $logId => $contact) {
            $phone = $contact->getMobile();
            $phones .= '"'.$phone.'",';
        }
        $phones = substr($phones, 0, -1);

        //Create campaign
        $campaign = $this->createCampaign($call->getName());

        //Add calls in campaign
        $this->addCalls($call->getMessage(), $phones, $triggerDate, $campaign['id']);

        $event->setChannel('call', $call->getId());
        //$event->setResult(json_decode($response, true));
    }

    public function createCampaign(string $name)
    {
        //Create campaign for this send
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => self::CALL_URI,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => '{
                "name":"'.$name.'",
                "sender":"573112233445"

            }',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'apiKey:'.$this->token.'',
                'apiSecret:'.$this->secret.'',
            ],
        ]);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }

    public function addCalls(string $message, string $phones, $triggerDate, string $campaignID)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => self::CALL_URI.$campaignID,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => '{
                "destinationsList": ['.$phones.'],
                "scheduledDate": "'.$triggerDate.'",
                "stepList":[
                    {
                    "id":"1",
                    "rootStep":true,
                    "nextStepId":"2",
                    "stepType":"CALL"
                    },
                    {
                    "id":"2",
                    "rootStep":false,
                    "text":"'.$message.'",
                    "voice":"PEDRO",
                    "speed":100,
                    "stepType": "SAY"
                    }
                ]
            }',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'apiKey:'.$this->token.'',
                'apiSecret:'.$this->secret.'',
            ],
        ]);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        curl_close($curl);
    }
}
