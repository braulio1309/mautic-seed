<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'routes' => [
        'main' => [
            'mautic_message_index' => [
                'path'       => '/messages/{page}',
                'controller' => 'MauticChannelBundle:Message:index',
            ],
            'mautic_message_contacts' => [
                'path'       => '/messages/contacts/{objectId}/{channel}/{page}',
                'controller' => 'MauticChannelBundle:Message:contacts',
            ],
            'mautic_message_action' => [
                'path'       => '/messages/{objectAction}/{objectId}',
                'controller' => 'MauticChannelBundle:Message:execute',
            ],
            'mautic_channel_batch_contact_set' => [
                'path'       => '/channels/batch/contact/set',
                'controller' => 'MauticChannelBundle:BatchContact:set',
            ],
            'mautic_channel_batch_contact_view' => [
                'path'       => '/channels/batch/contact/view',
                'controller' => 'MauticChannelBundle:BatchContact:index',
            ],
            //Products
            'products_list' => [
                'path'       => '/products/{page}',
                'controller' => 'MauticChannelBundle:Product:index',
            ],
            'products_create' => [
                'path'       => '/products/{objectAction}/{objectId}',
                'controller' => 'MauticChannelBundle:Product:new',
            ],
            'product_delete' => [
                'path'       => '/products/delete/{objectId}',
                'controller' => 'MauticChannelBundle:Product:delete',
            ],
            'product_import_action' => [
                'path'       => '/product/import/{objectAction}/{objectId}',
                'controller' => 'MauticChannelBundle:Import:execute',
            ],
            'product_import_index' => [
                'path'       => '/product/import/{page}',
                'controller' => 'MauticChannelBundle:Import:index',
            ],
            'variant_import_action' => [
                'path'       => '/variant/import/{objectAction}/{objectId}',
                'controller' => 'MauticChannelBundle:ImportVariant:execute',
            ],
            'customer_import_action' => [
                'path'       => '/customer/import/{objectAction}/{objectId}',
                'controller' => 'MauticChannelBundle:ImportCustomer:execute',
            ],
            'customer_import_index' => [
                'path'       => '/customer/import/{page}',
                'controller' => 'MauticChannelBundle:ImportCustomer:index',
            ],
            'order_import_action' => [
                'path'       => '/order/import/{objectAction}/{objectId}',
                'controller' => 'MauticChannelBundle:ImportOrder:execute',
            ],
            'order_import_index' => [
                'path'       => '/order/import/{page}',
                'controller' => 'MauticChannelBundle:ImportOrder:index',
            ],
            'customer_import_index' => [
                'path'       => '/customer/import/{page}',
                'controller' => 'MauticChannelBundle:ImportCustomer:index',
            ],
            'variant_import_index' => [
                'path'       => '/variant/import/{page}',
                'controller' => 'MauticChannelBundle:ImportVariant:index',
            ],
            'category_create' => [
                'path'       => '/category/{objectAction}/{objectId}',
                'controller' => 'MauticChannelBundle:Category:new',
            ],
            'category_list' => [
                'path'       => '/category/{page}',
                'controller' => 'MauticChannelBundle:Category:index',
            ],
            'category_import_action' => [
                'path'       => '/category/import/{objectAction}/{objectId}',
                'controller' => 'MauticChannelBundle:ImportCategory:execute',
            ],
            'category_import_index' => [
                'path'       => '/category/import/{page}',
                'controller' => 'MauticChannelBundle:ImportCategory:index',
            ],
            'variant_create' => [
                'path'       => '/variant/{objectAction}/{objectId}',
                'controller' => 'MauticChannelBundle:Variant:new',
            ],
            'variant_list' => [
                'path'       => '/variant/{page}',
                'controller' => 'MauticChannelBundle:Variant:index',
            ],
            'order_list' => [
                'path'       => '/order/{page}',
                'controller' => 'MauticChannelBundle:Order:index',
            ],
            'order_create' => [
                'path'       => '/order/{objectAction}/{objectId}',
                'controller' => 'MauticChannelBundle:Order:new',
            ],
            'customer_create' => [
                'path'       => '/customer/{objectAction}/{objectId}',
                'controller' => 'MauticChannelBundle:Customer:new',
            ],
            'customer_list' => [
                'path'       => '/customer/{page}',
                'controller' => 'MauticChannelBundle:Customer:index',
            ],
        ],
        'api' => [
            'mautic_api_messagetandard' => [
                'standard_entity' => true,
                'name'            => 'messages',
                'path'            => '/messages',
                'controller'      => 'MauticChannelBundle:Api\MessageApi',
            ],
            'mautic_api_products'      => [
                'path'       => '/products/{objectId}',
                'controller' => 'MauticChannelBundle:Api\ProductApi:getAll',
                'method'     => 'GET',
            ],
            'mautic_api_products_new'      => [
                'path'       => '/products/new/{objectId}',
                'controller' => 'MauticChannelBundle:Api\ProductApi:new',
                'method'     => 'POST',
            ],
            'mautic_api_product_categories'      => [
                'path'       => '/products/categories/{objectId}',
                'controller' => 'MauticChannelBundle:Api\CategoryApi:getAll',
                'method'     => 'GET',
            ],
            'mautic_api_product_categories_new'      => [
                'path'       => '/products/categories/new/{objectId}',
                'controller' => 'MauticChannelBundle:Api\CategoryApi:new',
                'method'     => 'POST',
            ],
            'mautic_api_variant'      => [
                'path'       => '/products/variant/{objectId}',
                'controller' => 'MauticChannelBundle:Api\VariantApi:getAll',
                'method'     => 'GET',
            ],
            'mautic_api_variant_new'      => [
                'path'       => '/products/variant/new/{objectId}',
                'controller' => 'MauticChannelBundle:Api\VariantApi:new',
                'method'     => 'POST',
            ],
            'mautic_api_order'      => [
                'path'       => '/orders/{objectId}',
                'controller' => 'MauticChannelBundle:Api\OrderApi:getAll',
                'method'     => 'GET',
            ],
            'mautic_api_order_new'      => [
                'path'       => '/order/new/{objectId}',
                'controller' => 'MauticChannelBundle:Api\OrderApi:new',
                'method'     => 'POST',
            ],
            'mautic_api_customer'      => [
                'path'       => '/customers/{objectId}',
                'controller' => 'MauticChannelBundle:Api\CustomerApi:getAll',
                'method'     => 'GET',
            ],
            'mautic_api_categories_new'      => [
                'path'       => '/customer/new/{objectId}',
                'controller' => 'MauticChannelBundle:Api\CustomerApi:new',
                'method'     => 'POST',
            ],
        ],
        'public' => [
        ],
    ],

    'menu' => [
        'main' => [
            'mautic.channel.messages' => [
                'route'    => 'mautic_message_index',
                'access'   => ['channel:messages:viewown', 'channel:messages:viewother'],
                'parent'   => 'mautic.core.channels',
                'priority' => 110,
            ],
            //Products
            'Products' => [
                'priority'  => 90,
                'iconClass' => 'fa-th-large',
            ],
            'Product list' => [
                'priority' => 90,
                'parent'   => 'Products',
                'access'   => ['channel:product:index', 'channel:messages:viewother'],
                'route'    => 'products_list',
            ],
            'Categories' => [
                'priority' => 90,
                'parent'   => 'Products',
                'access'   => ['channel:product:index', 'channel:messages:viewother'],
                'route'    => 'category_list',
            ],
            'Variants' => [
                'priority' => 90,
                'parent'   => 'Products',
                'route'    => 'variant_list',
            ],
            //Orders
            'Orders' => [
                'priority'  => 90,
                'iconClass' => 'fa-th-large',
            ],
            'Customers' => [
                'priority' => 90,
                'parent'   => 'Orders',
                'route'    => 'customer_list',
            ],
            'Order list' => [
                'priority' => 90,
                'parent'   => 'Orders',
                'route'    => 'order_list',
            ],
        ],
        'admin' => [
        ],
        'profile' => [
        ],
        'extra' => [
        ],
    ],

    'categories' => [
        'messages' => null,
    ],

    'services' => [
        'events' => [
            'mautic.channel.campaignbundle.subscriber' => [
                'class'     => Mautic\ChannelBundle\EventListener\CampaignSubscriber::class,
                'arguments' => [
                    'mautic.channel.model.message',
                    'mautic.campaign.dispatcher.action',
                    'mautic.campaign.event_collector',
                    'monolog.logger.mautic',
                    'translator',
                ],
            ],
            'mautic.channel.channelbundle.subscriber' => [
                'class'     => \Mautic\ChannelBundle\EventListener\MessageSubscriber::class,
                'arguments' => [
                    'mautic.core.model.auditlog',
                ],
            ],
            'mautic.channel.channelbundle.lead.subscriber' => [
                'class'     => Mautic\ChannelBundle\EventListener\LeadSubscriber::class,
                'arguments' => [
                    'translator',
                    'router',
                    'mautic.channel.repository.message_queue',
                ],
            ],
            'mautic.channel.reportbundle.subscriber' => [
                'class'     => Mautic\ChannelBundle\EventListener\ReportSubscriber::class,
                'arguments' => [
                    'mautic.lead.model.company_report_data',
                    'router',
                ],
            ],
            'mautic.channel.button.subscriber' => [
                'class'     => \Mautic\ChannelBundle\EventListener\ButtonSubscriber::class,
                'arguments' => [
                    'router',
                    'translator',
                ],
            ],
        ],
        'forms' => [
            'mautic.channel.type.product'                 => [
                'class'     => 'Mautic\ChannelBundle\Form\Type\ProductForm',
                'arguments' => 'mautic.security',
            ],
            'mautic.channel.type.listcustomer' => [
                'class'     => 'Mautic\ChannelBundle\Form\Type\CustomerListType',
                'arguments' => [
                    'mautic.channel.model.customer',
                    'translator',
                    'mautic.security',
                ],
            ],
            'mautic.channel.type.categorylist'         => [
                'class'     => 'Mautic\ChannelBundle\Form\Type\CategoryListType',
                'arguments' => [
                    'mautic.channel.model.category',
                    'translator',
                    'mautic.security',
                ],
            ],
            'mautic.channel.type.category'                 => [
                'class'     => 'Mautic\ChannelBundle\Form\Type\CategoryForm',
                'arguments' => 'mautic.security',
            ],
            'mautic.channel.type.variant'                 => [
                'class'     => 'Mautic\ChannelBundle\Form\Type\VariantForm',
                'arguments' => 'mautic.security',
            ],
            'mautic.channel.type.order'                 => [
                'class'     => 'Mautic\ChannelBundle\Form\Type\OrderForm',
                'arguments' => 'mautic.security',
            ],
            'mautic.channel.type.customer'                 => [
                'class'     => 'Mautic\ChannelBundle\Form\Type\CustomerForm',
                'arguments' => 'mautic.security',
            ],
            \Mautic\ChannelBundle\Form\Type\MessageType::class => [
                'class'       => \Mautic\ChannelBundle\Form\Type\MessageType::class,
                'methodCalls' => [
                    'setSecurity' => ['mautic.security'],
                ],
                'arguments' => [
                    'mautic.channel.model.message',
                ],
            ],
            'mautic.form.type.product_import' => [
                'class' => \Mautic\ChannelBundle\Form\Type\ProductImportType::class,
            ],
            'mautic.form.type.product_field_import' => [
                'class'     => \Mautic\ChannelBundle\Form\Type\ProductImportFieldType::class,
                'arguments' => ['translator', 'doctrine.orm.entity_manager'],
            ],
            'mautic.form.type.message_list' => [
                'class' => \Mautic\ChannelBundle\Form\Type\MessageListType::class,
            ],
            'mautic.form.type.message_send' => [
                'class'     => \Mautic\ChannelBundle\Form\Type\MessageSendType::class,
                'arguments' => ['router', 'mautic.channel.model.message'],
            ],
        ],
        'helpers' => [
            'mautic.channel.helper.channel_list' => [
                'class'     => \Mautic\ChannelBundle\Helper\ChannelListHelper::class,
                'arguments' => [
                    'event_dispatcher',
                    'translator',
                ],
                'alias' => 'channel',
            ],
        ],
        'models' => [
            'mautic.channel.model.product' => [
                'class'     => \Mautic\ChannelBundle\Model\ProductModel::class,
                'arguments' => [
                    'mautic.lead.model.list',
                    'mautic.form.model.form',
                    'mautic.campaign.event_collector',
                    'mautic.campaign.membership.builder',
                    'mautic.tracker.contact',
                ],
            ],
            'mautic.channel.model.category' => [
                'class'     => \Mautic\ChannelBundle\Model\CategoryModel::class,
            ],
            'mautic.channel.model.variant' => [
                'class'     => \Mautic\ChannelBundle\Model\VariantModel::class,
            ],
            'mautic.channel.model.customer' => [
                'class'     => \Mautic\ChannelBundle\Model\CustomerModel::class,
            ],
            'mautic.channel.model.order' => [
                'class'     => \Mautic\ChannelBundle\Model\OrderModel::class,
            ],
            'mautic.channel.model.message' => [
                'class'     => \Mautic\ChannelBundle\Model\MessageModel::class,
                'arguments' => [
                    'mautic.channel.helper.channel_list',
                    'mautic.campaign.model.campaign',
                ],
            ],
            'mautic.channel.model.queue' => [
                'class'     => 'Mautic\ChannelBundle\Model\MessageQueueModel',
                'arguments' => [
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.core_parameters',
                ],
            ],
            'mautic.channel.model.channel.action' => [
                'class'     => \Mautic\ChannelBundle\Model\ChannelActionModel::class,
                'arguments' => [
                    'mautic.lead.model.lead',
                    'mautic.lead.model.dnc',
                    'translator',
                ],
            ],
            'mautic.channel.model.frequency.action' => [
                'class'     => \Mautic\ChannelBundle\Model\FrequencyActionModel::class,
                'arguments' => [
                    'mautic.lead.model.lead',
                    'mautic.lead.repository.frequency_rule',
                ],
            ],
        ],
        'repositories' => [
            'mautic.channel.repository.message_queue' => [
                'class'     => Doctrine\ORM\EntityRepository::class,
                'factory'   => ['@doctrine.orm.entity_manager', 'getRepository'],
                'arguments' => \Mautic\ChannelBundle\Entity\MessageQueue::class,
            ],
            'mautic.channel.repository.message_queue' => [
                'class'     => Doctrine\ORM\EntityRepository::class,
                'factory'   => ['@doctrine.orm.entity_manager', 'getRepository'],
                'arguments' => \Mautic\ChannelBundle\Entity\MessageQueue::class,
            ],
           'mautic.channel.repository.product' => [
                'class'     => Doctrine\ORM\EntityRepository::class,
                'factory'   => ['@doctrine.orm.entity_manager', 'getRepository'],
                'arguments' => \Mautic\ChannelBundle\Entity\Product::class,
            ],
        ],
    ],

    'parameters' => [
    ],
];
