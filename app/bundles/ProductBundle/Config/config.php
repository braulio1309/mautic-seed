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
            'products_list' => [
                'path'       => '/products/{page}',
                'controller' => 'ProductBundle:Product:index',
            ],
            'products_create' => [
                'path'       => '/products/{objectAction}/{objectId}',
                'controller' => 'ProductBundle:Product:new',
            ],
            'product_delete' => [
                'path'       => '/products/delete/{objectId}',
                'controller' => 'ProductBundle:Product:delete',
            ],
            'product_import_action' => [
                'path'       => '/product/import/{objectAction}/{objectId}',
                'controller' => 'ProductBundle:Import:execute',
            ],
            'product_import_index' => [
                'path'       => '/product/import/{page}',
                'controller' => 'ProductBundle:Import:index',
            ],
            'variant_import_action' => [
                'path'       => '/variant/import/{objectAction}/{objectId}',
                'controller' => 'ProductBundle:ImportVariant:execute',
            ],
            'variant_import_index' => [
                'path'       => '/variant/import/{page}',
                'controller' => 'ProductBundle:ImportVariant:index',
            ],
            'category_create' => [
                'path'       => '/category/{objectAction}/{objectId}',
                'controller' => 'ProductBundle:Category:new',
            ],
            'category_list' => [
                'path'       => '/category/{page}',
                'controller' => 'ProductBundle:Category:index',
            ],
            'category_import_action' => [
                'path'       => '/category/import/{objectAction}/{objectId}',
                'controller' => 'ProductBundle:ImportCategory:execute',
            ],
            'category_import_index' => [
                'path'       => '/category/import/{page}',
                'controller' => 'ProductBundle:ImportCategory:index',
            ],
            'variant_create' => [
                'path'       => '/variant/{objectAction}/{objectId}',
                'controller' => 'ProductBundle:Variant:new',
            ],
            'variant_list' => [
                'path'       => '/variant/{page}',
                'controller' => 'ProductBundle:Variant:index',
            ],
        ],
        'api' => [
            'mautic_api_products'      => [
                'path'       => '/products/{objectId}',
                'controller' => 'ProductBundle:Api\ProductApi:getAll',
                'method'     => 'GET',
            ],
            'mautic_api_products_new'      => [
                'path'       => '/products/new/{objectId}',
                'controller' => 'ProductBundle:Api\ProductApi:new',
                'method'     => 'POST',
            ],
            'mautic_api_product_categories'      => [
                'path'       => '/product/categories/{objectId}',
                'controller' => 'ProductBundle:Api\CategoryApi:getAll',
                'method'     => 'GET',
            ],
            'mautic_api_product_categories_new'      => [
                'path'       => '/products/categories/new/{objectId}',
                'controller' => 'ProductBundle:Api\CategoryApi:new',
                'method'     => 'POST',
            ],
            'mautic_api_variant'      => [
                'path'       => '/products/variant/{objectId}',
                'controller' => 'ProductBundle:Api\VariantApi:getAll',
                'method'     => 'GET',
            ],
            'mautic_api_variant_new'      => [
                'path'       => '/products/variant/new/{objectId}',
                'controller' => 'ProductBundle:Api\VariantApi:new',
                'method'     => 'POST',
            ],
        ],
    ],

    'menu' => [
        'main' => [
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
        ],
    ],

    'services' => [
        'events' => [
            'mautic.product.dashboard.subscriber' => [
                'class'     => \Mautic\ProductBundle\EventListener\ProductDashboardSubscriber::class,
                'arguments' => [
                    'mautic.channel.model.product',
                    'mautic.lead.model.list',
                    'router',
                    'translator',
                ],
            ],
        ],
        'forms' => [
            'mautic.channel.type.product'                 => [
                'class'     => 'Mautic\ProductBundle\Form\Type\ProductForm',
                'arguments' => 'mautic.security',
            ],
            'mautic.form.type.product_dashboard_product_in_time_widget' => [
                'class' => \Mautic\ProductBundle\Form\Type\ProductDashboardInTimeWidgetType::class,
            ],

            'mautic.channel.type.categorylist'         => [
                'class'     => 'Mautic\ProductBundle\Form\Type\CategoryListType',
                'arguments' => [
                    'mautic.channel.model.category',
                    'translator',
                    'mautic.security',
                ],
            ],
            'mautic.channel.type.category'                 => [
                'class'     => 'Mautic\ProductBundle\Form\Type\CategoryForm',
                'arguments' => 'mautic.security',
            ],
            'mautic.channel.type.variant'                 => [
                'class'     => 'Mautic\ProductBundle\Form\Type\VariantForm',
                'arguments' => 'mautic.security',
            ],

            'mautic.form.type.product_import' => [
                'class' => \Mautic\ProductBundle\Form\Type\ProductImportType::class,
            ],
            'mautic.form.type.product_field_import' => [
                'class'     => \Mautic\ProductBundle\Form\Type\ProductImportFieldType::class,
                'arguments' => ['translator', 'doctrine.orm.entity_manager'],
            ],
        ],
        'models' => [
            'mautic.channel.model.product' => [
                'class'     => \Mautic\ProductBundle\Model\ProductModel::class,
                'arguments' => [
                    'mautic.lead.model.list',
                    'mautic.form.model.form',
                    'mautic.campaign.event_collector',
                    'mautic.campaign.membership.builder',
                    'mautic.tracker.contact',
                ],
            ],
            'mautic.channel.model.category' => [
                'class'     => \Mautic\ProductBundle\Model\CategoryModel::class,
            ],
            'mautic.channel.model.variant' => [
                'class'     => \Mautic\ProductBundle\Model\VariantModel::class,
            ],
        ],
        'repositories' => [
           'mautic.channel.repository.product' => [
                'class'     => Doctrine\ORM\EntityRepository::class,
                'factory'   => ['@doctrine.orm.entity_manager', 'getRepository'],
                'arguments' => \Mautic\ProductBundle\Entity\Product::class,
            ],
            'mautic.channel.repository.category' => [
                'class'     => Doctrine\ORM\EntityRepository::class,
                'factory'   => ['@doctrine.orm.entity_manager', 'getRepository'],
                'arguments' => \Mautic\ProductBundle\Entity\Category::class,
            ],
            'mautic.channel.repository.variant' => [
                'class'     => Doctrine\ORM\EntityRepository::class,
                'factory'   => ['@doctrine.orm.entity_manager', 'getRepository'],
                'arguments' => \Mautic\ProductBundle\Entity\Variant::class,
            ],
        ],
    ],
];
