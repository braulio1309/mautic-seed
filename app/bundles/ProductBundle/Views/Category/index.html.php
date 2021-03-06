<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
$view->extend('MauticCoreBundle:Default:content.html.php');
$view['slots']->set('mauticContent', 'Categories');
$view['slots']->set('headerTitle', 'Categories');

$pageButtons   = [];
$pageButtons[] = [
    'attr' => [
        'href' => $view['router']->path('category_import_action', ['object' => 'companies', 'objectAction' => 'new']),
    ],
    'iconClass' => 'fa fa-upload',
    'btnText'   => 'Import',
];

$pageButtons[] = [
    'attr' => [
        'href' => $view['router']->path('category_create', ['objectId' => null, 'objectAction' => 'new']),
    ],
    'iconClass' => 'fa fa-history',
    'btnText'   => 'New',
];

$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:page_actions.html.php',
        [
            'templateButtons' => [
                'new' => '',
            ],
            'routeBase'     => 'company',
            'customButtons' => $pageButtons,
        ]
    )
);

?>

<div class="panel panel-default bdr-t-wdh-0 mb-0">
    <?php echo $view->render(
        'MauticCoreBundle:Helper:list_toolbar.html.php',
        [
            'searchValue' => $searchValue,
            'searchHelp'  => 'mautic.core.help.searchcommands',
            'action'      => $currentRoute,
        ]
    ); ?>
    <div class="page-list">
        <?php $view['slots']->output('_content'); ?>
    </div>
</div>
