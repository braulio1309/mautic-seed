<?php

return [
    'routes' => [
        'main' => [
            'products' => [
                'path'       => '/productos',
                'controller' => 'DestinyProductBundle:Product:index',
            ],
        ],
        'menu' => [
            'main' => [
                'mautic.product.title' => [
                    'route'    => 'products',
                    'access'   => ['channel:messages:viewown', 'channel:messages:viewother'],
                    'parent'   => 'mautic.core.channels',
                    'priority' => 120,
                ],
            ],
        ],
    ],
];
