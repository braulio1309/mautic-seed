<?php

namespace MauticPlugin\MauticSmsapiBundle\Integration;

use Aws\Credentials\Credentials;
use Aws\Exception\AwsException;
use Aws\Sns\SnsClient;
use Doctrine\ORM\EntityManager;
use Mautic\CoreBundle\Helper\CacheStorageHelper;
use Mautic\CoreBundle\Helper\EncryptionHelper;
use Mautic\CoreBundle\Helper\PathsHelper;
use Mautic\CoreBundle\Model\NotificationModel;
use Mautic\IntegrationsBundle\Integration\ConfigurationTrait;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormInterface;
use Mautic\LeadBundle\Model\CompanyModel;
use Mautic\LeadBundle\Model\DoNotContact as DoNotContactModel;
use Mautic\LeadBundle\Model\FieldModel;
use Mautic\LeadBundle\Model\LeadModel;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Mautic\PluginBundle\Integration\AbstractIntegration;
use Mautic\PluginBundle\Model\IntegrationEntityModel;
use MauticPlugin\MauticSmsapiBundle\Core\SmsapiGateway;
use MauticPlugin\MauticSmsapiBundle\Core\SmsapiPluginInterface;
use MauticPlugin\MauticSmsapiBundle\MauticSmsapiConst;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

class SmsapiIntegration extends AbstractIntegration implements ConfigFormInterface
{
    use ConfigurationTrait;
    /**
     * @var SmsapiGateway
     */
    private $gateway;
    private $integrationHelper;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        CacheStorageHelper $cacheStorageHelper,
        EntityManager $entityManager,
        Session $session,
        RequestStack $requestStack,
        Router $router,
        TranslatorInterface $translator,
        Logger $logger,
        EncryptionHelper $encryptionHelper,
        LeadModel $leadModel,
        CompanyModel $companyModel,
        PathsHelper $pathsHelper,
        NotificationModel $notificationModel,
        FieldModel $fieldModel,
        IntegrationEntityModel $integrationEntityModel,
        DoNotContactModel $doNotContact,
        SmsapiGateway $gateway,
        IntegrationHelper $integrationHelper
    ) {
        parent::__construct(
            $eventDispatcher,
            $cacheStorageHelper,
            $entityManager,
            $session,
            $requestStack,
            $router,
            $translator,
            $logger,
            $encryptionHelper,
            $leadModel,
            $companyModel,
            $pathsHelper,
            $notificationModel,
            $fieldModel,
            $integrationEntityModel,
            $doNotContact
        );
        $this->gateway           = $gateway;
        $this->integrationHelper = $integrationHelper;
    }

    public function getName(): string
    {
        return MauticSmsapiConst::SMSAPI_INTEGRATION_NAME;
    }

    public function getDisplayName(): string
    {
        return 'Destiny Amazon SNS';
    }

    public function getIcon(): string
    {
        return 'plugins/MauticSmsapiBundle/Assets/img/destiny.png';
    }

    public function adapter(): SmsapiPluginInterface
    {
        return $this->factory->get('mautic.sms.smsapi.plugin');
    }

    public function getSecretKeys(): array
    {
        return ['password'];
    }

    public function getAuthenticationUrl(): string
    {
        return MauticSmsapiConst::OAUTH_AUTHENTICATION_URL;
    }

    public function getAccessTokenUrl(): string
    {
        return MauticSmsapiConst::OAUTH_API_TOKEN_URL;
    }

    public function getAuthScope(): string
    {
        return MauticSmsapiConst::OAUTH_SCOPES;
    }

    public function getBearerToken($inAuthorization = false)
    {
        if (!$inAuthorization && isset($this->keys[$this->getAuthTokenKey()])) {
            return $this->keys[$this->getAuthTokenKey()];
        }

        return false;
    }

    public function getFormSettings(): array
    {
        return [
            'requires_callback'      => false,
            'requires_authorization' => true,
        ];
    }

    public function getAuthenticationType(): string
    {
        return 'oauth2';
    }

    public function appendToForm(&$builder, $data, $formArea)
    {
        $isConnected = $this->gateway->isConnected();

        $integration = $this->integrationHelper->getIntegrationObject('Smsapi');

        $keys = $integration->getDecryptedApiKeys();

        $token  = $keys['client_id'];
        $secret = $keys['client_secret'];

        try {
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
        } catch (AwsException $e) {
            // error_log($e->getMessage());
        }

        $message = 'This message is sent from a Amazon SNS code sample.';
        $phone   = '+573054417046';
        try {
            $result = $client->publish([
                'Message'     => $message,
                'PhoneNumber' => $phone,
            ]);
        } catch (AwsException $e) {
            // error_log($e->getMessage());
        }

        if ('keys' == $formArea) {
            $builder->add(
                'connection_status',
                TextType::class,
                [
                    'label'      => 'mautic.sms.config.form.sms.smsapi.connection_status',
                    'label_attr' => ['class' => 'control-label'],
                    'disabled'   => true,
                    'required'   => false,
                    'attr'       => [
                        'class' => 'form-control',
                    ],
                    'data' => $isConnected ?
                        $this->translator->trans('mautic.sms.config.form.sms.smsapi.success_authenticated')
                        : $this->translator->trans('mautic.sms.config.form.sms.smsapi.no_authentication'),
                ]
            );
        }
        if (!$isConnected) {
            return null;
        }

        // $sendernames = $this->gateway->getSendernames();
        $sendernames['joe'] = 'joe';
        $profile            = $this->gateway->getProfile();

        if ('features' == $formArea) {
            $builder->add(
                'available_points',
                TextType::class,
                [
                    'label'      => 'mautic.sms.config.form.sms.smsapi.available_points',
                    'label_attr' => ['class' => 'control-label'],
                    'disabled'   => true,
                    'required'   => false,
                    'attr'       => [
                        'class' => ' form-control',
                    ],
                    'data' => 11,
                ]
            );
            $builder->add(
                'sendername',
                ChoiceType::class,
                [
                    'label'      => 'mautic.sms.config.form.sms.smsapi.sendername',
                    'label_attr' => ['class' => 'control-label'],
                    'required'   => false,
                    'attr'       => [
                        'class' => 'form-control',
                    ],
                    'choices' => $sendernames,
                ]
            );
        }
    }

    public function getConfigFormName(): ?string
    {
        return null;
    }

    public function getConfigFormContentTemplate(): ?string
    {
        return null;
    }
}
