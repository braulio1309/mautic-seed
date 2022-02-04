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
$view['slots']->set('headerTitle', 'Orders');
?>

<?php echo $view['form']->start($form); ?>
<!-- start: box layout -->
<div class="box-layout">
    <!-- container -->
    <div class="col-md-9 bg-auto height-auto bdr-r">
        <div class="pa-md">
           

            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['subtotal_price']); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['total_tax']); ?>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['payment_method']); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['currency']); ?>

                </div>
                
            </div>

            

            <div class="row">
                <div class="col-md-12">
                    <?php echo $view['form']->row($form['notes']); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 bg-white height-auto">
        <div class="pr-lg pl-lg pt-md pb-md">
            <?php
                echo $view['form']->row($form['customer_id']);

            ?>
        </div>
    </div>
</div>

<?php echo $view['form']->end($form); ?>


