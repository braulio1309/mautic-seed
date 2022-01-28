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

$header = ($entity->getId()) ?
    'Edit' :
    'New Product';
$view['slots']->set('headerTitle', $header);
?>

<?php echo $view['form']->start($form); ?>
<!-- start: box layout -->
<div class="box-layout">
    <!-- container -->
    <div class="col-md-9 bg-auto height-auto bdr-r">
        <div class="pa-md">
           

            <div class="row">
                <div class="col-md-12">
                    <?php echo $view['form']->row($form['product_name']); ?>
                </div>
               
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['initial_quantity']); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['initial_price']); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php echo $view['form']->row($form['vendor']); ?>
                </div>
               
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php echo $view['form']->row($form['product_desc']); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 bg-white height-auto">
        <div class="pr-lg pl-lg pt-md pb-md">
            <?php
          /* echo $view['form']->row($form['category']);
            echo $view['form']->row($form['allowRestart']);
            echo $view['form']->row($form['isPublished']);
            echo $view['form']->row($form['publishUp']);
            echo $view['form']->row($form['publishDown']);*/
            ?>
        </div>
    </div>
</div>

<?php echo $view['form']->end($form); ?>


