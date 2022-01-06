<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
?>
<head>
    <meta charset="UTF-8" />
    <title><?php if (!empty($view['slots']->get('headerTitle', 'Destiny'))): ?>
        <?php echo strip_tags(str_replace('<', ' <', $view['slots']->get('headerTitle', 'Destiny'))); ?> | 
    <?php endif; ?>
	<?php echo $view['slots']->get('pageTitle', 'Destiny'); ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="icon" type="image/x-icon" href="<?php echo $view['assets']->getUrl('app\assets\images\favicon.png'); ?>" />
    <link rel="icon" sizes="192x192" href="<?php echo $view['assets']->getUrl('app\assets\images\Logo-destiny.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo $view['assets']->getUrl('app\assets\images\Logo-destiny.png'); ?>" />
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('app\assets\css\libraries.css'); ?>">
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('app\assets\css\app.css'); ?>">

    <?php echo $view['assets']->outputSystemStylesheets(); ?>

    <?php echo $view->render('MauticCoreBundle:Default:script.html.php'); ?>
    <?php $view['assets']->outputHeadDeclarations(); ?>
</head>
