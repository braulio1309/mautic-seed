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

$objectName = $view['translator']->trans($objectName);

$view['slots']->set('mauticContent', 'Products');
$view['slots']->set('headerTitle', 'Products');

?>
<?php if (isset($form['file'])): ?>
<?php echo $view->render('MauticChannelBundle:Import:upload_form.html.php', ['form' => $form]); ?>
<?php else: ?>
<?php echo $view->render('MauticChannelBundle:Import:mapping_form.html.php', ['form' => $form]); ?>
<?php endif; ?>
