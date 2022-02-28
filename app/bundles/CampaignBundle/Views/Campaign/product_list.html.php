<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

$view['slots']->set('headerTitle', 'Products');
if ('index' == $tmpl) {
    $view->extend('MauticCoreBundle:Standard:index.html.php');
}
?>
<?php if (count($items)): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered campaign-list" id="campaignTable">
            <thead>
            <tr>
                <?php
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'checkall'        => 'true',
                        'target'          => '#campaignTable',
                        'routeBase'       => 'campaign',
                        'templateButtons' => [
                            'delete' => $permissions['campaign:campaigns:deleteown']
                            || $permissions['campaign:campaigns:deleteother'],
                        ],
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'campaign',
                        'orderBy'    => 'c.name',
                        'text'       => 'Name',
                        'class'      => 'col-campaign-name',
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'campaign',
                        'orderBy'    => 'cat.title',
                        'text'       => 'Description',
                        'class'      => 'visible-md visible-lg col-campaign-category',
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'campaign',
                        'orderBy'    => 'c.dateAdded',
                        'text'       => 'mautic.lead.import.label.dateAdded',
                        'class'      => 'visible-md visible-lg col-campaign-dateAdded',
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'campaign',
                        'orderBy'    => 'c.dateModified',
                        'text'       => 'mautic.lead.import.label.dateModified',
                        'class'      => 'visible-md visible-lg col-campaign-dateModified',
                        'default'    => true,
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'campaign',
                        'orderBy'    => 'c.createdByUser',
                        'text'       => 'mautic.core.createdby',
                        'class'      => 'visible-md visible-lg col-campaign-createdByUser',
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'campaign',
                        'orderBy'    => 'c.id',
                        'text'       => 'mautic.core.id',
                        'class'      => 'visible-md visible-lg col-campaign-id',
                    ]
                );
                ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
            <?php $mauticTemplateVars['item'] = $item; ?>
                <tr>
                    <td>
                        <?php
                        echo $view->render(
                            'MauticCoreBundle:Helper:list_actions.html.php',
                            [
                                'item'            => $item,
                                'templateButtons' => [
                                    'edit'   => $view['security']->hasEntityAccess(
                                        $permissions['campaign:campaigns:editown'],
                                        $permissions['campaign:campaigns:editother'],
                                        $item->getCreatedBy()
                                    ),

                                    'delete'   => $view['security']->hasEntityAccess(
                                        $permissions['campaign:campaigns:deleteown'],
                                        $permissions['campaign:campaigns:deleteother'],
                                        $item->getCreatedBy()
                                    ),
                                ],
                                'routeBase' => 'campaign',
                            ]
                        );
                        ?>
                    </td>
                    <td>
                        <div>
                            <a href="<?php echo $view['router']->path(
                                'mautic_campaign_action',
                                ['objectAction' => 'view', 'objectId' => $item->getId()]
                            ); ?>" data-toggle="ajax">
                                <?php echo $item->getName(); ?>
                            <?php echo $view['content']->getCustomContent('campaign.name', $mauticTemplateVars); ?>
                            </a>
                        </div>
                    </td>
                    <td class="visible-md visible-lg">
                        <?php $category = $item->getName(); ?>
                        <?php $catName  = ($category) ? $category->getName() : $view['translator']->trans('mautic.core.form.uncategorized'); ?>
                        <?php $color    = ($category) ? '#'.$category->getName() : 'inherit'; ?>
                        <span style="white-space: nowrap;"> <span><?php echo $catName; ?></span></span>
                    </td>
                    <td class="visible-md visible-lg"><?php echo $item->getName() ? '' : ''; ?></td>
                    <td class="visible-md visible-lg"><?php echo $item->getName() ? '' : ''; ?></td>
                    <td class="visible-md visible-lg"><?php echo $item->getName(); ?></td>
                    <td class="visible-md visible-lg"><?php echo $item->getId(); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <?php echo $view->render(
            'MauticCoreBundle:Helper:pagination.html.php',
            [
                'totalItems' => count($items),
                'page'       => $page,
                'limit'      => $limit,
                'menuLinkId' => 'products_list',
                'baseUrl'    => $view['router']->path('products_list'),
                'sessionVar' => 'campaign',
            ]
        ); ?>
    </div>
<?php else: ?>
    <?php echo $view->render('MauticCoreBundle:Helper:noresults.html.php', ['tip' => 'Create a new product']); ?>
<?php endif; ?>
