<?php

return [
    'routes' => [
        'main' => [
            'customer_import_action' => [
                'path'       => '/customer/import/{objectAction}/{objectId}',
                'controller' => 'OrderBundle:ImportCustomer:execute',
            ],
            'customer_import_index' => [
                'path'       => '/customer/import/{page}',
                'controller' => 'OrderBundle:ImportCustomer:index',
            ],
            'order_import_action' => [
                'path'       => '/order/import/{objectAction}/{objectId}',
                'controller' => 'OrderBundle:ImportOrder:execute',
            ],
            'order_import_index' => [
                'path'       => '/order/import/{page}',
                'controller' => 'OrderBundle:ImportOrder:index',
            ],
            'customer_import_index' => [
                'path'       => '/customer/import/{page}',
                'controller' => 'OrderBundle:ImportCustomer:index',
            ],
            'order_list' => [
                'path'       => '/order/{page}',
                'controller' => 'OrderBundle:Order:index',
            ],
            'order_create' => [
                'path'       => '/order/{objectAction}/{objectId}',
                'controller' => 'OrderBundle:Order:new',
            ],
            'customer_create' => [
                'path'       => '/customer/{objectAction}/{objectId}',
                'controller' => 'OrderBundle:Customer:new',
            ],
            'customer_list' => [
                'path'       => '/customer/{page}',
                'controller' => 'OrderBundle:Customer:index',
            ],
        ],
        'api' => [
            'mautic_api_order'      => [
                'path'       => '/orders/{objectId}',
                'controller' => 'OrderBundle:Api\OrderApi:getAll',
                'method'     => 'GET',
            ],
            'mautic_api_order_new'      => [
                'path'       => '/order/new/{objectId}',
                'controller' => 'OrderBundle:Api\OrderApi:new',
                'method'     => 'POST',
            ],
            'mautic_api_customer'      => [
                'path'       => '/customers/{objectId}',
                'controller' => 'OrderBundle:Api\CustomerApi:getAll',
                'method'     => 'GET',
            ],
        ],
    ],

    'menu' => [
        'main' => [
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
    ],

    'categories' => [
        'messages' => null,
    ],

    'services' => [
        'forms' => [
            'mautic.channel.type.order'                 => [
                'class'     => 'Mautic\OrderBundle\Form\Type\OrderForm',
                'arguments' => 'mautic.security',
            ],
            'mautic.channel.type.customer'                 => [
                'class'     => 'Mautic\OrderBundle\Form\Type\CustomerForm',
                'arguments' => 'mautic.security',
            ],
            'mautic.channel.type.listcustomer' => [
                'class'     => 'Mautic\OrderBundle\Form\Type\CustomerListType',
                'arguments' => [
                    'mautic.channel.model.customer',
                    'translator',
                    'mautic.security',
                ],
            ],
        ],
        'models' => [
            'mautic.channel.model.customer' => [
                'class'     => \Mautic\OrderBundle\Model\CustomerModel::class,
            ],
            'mautic.channel.model.order' => [
                'class'     => \Mautic\OrderBundle\Model\OrderModel::class,
            ],
        ],
        'repositories' => [
            'mautic.channel.repository.order' => [
                'class'     => Doctrine\ORM\EntityRepository::class,
                'factory'   => ['@doctrine.orm.entity_manager', 'getRepository'],
                'arguments' => \Mautic\OrderBundle\Entity\Order::class,
            ],
            'mautic.channel.repository.customer' => [
                'class'     => Doctrine\ORM\EntityRepository::class,
                'factory'   => ['@doctrine.orm.entity_manager', 'getRepository'],
                'arguments' => \Mautic\OrderBundle\Entity\Order::class,
            ],
        ],
    ],
];
