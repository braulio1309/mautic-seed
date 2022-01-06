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

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title><?php echo $view['slots']->get('pageTitle', 'Destiny'); ?></title>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="icon" type="image/x-icon" href="<?php echo $view['assets']->getUrl('app\assets\images\favicon.png'); ?>" />
    <link rel="apple-touch-icon" href="<?php echo $view['assets']->getUrl('app\assets\images\Logo-destiny.png'); ?>" />
    <?php $view['assets']->outputSystemStylesheets(); ?>
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('app\assets\css\libraries.css'); ?>">
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('app\assets\css\app.css'); ?>">

    <?php echo $view->render('MauticCoreBundle:Default:script.html.php'); ?>
    <?php $view['assets']->outputHeadDeclarations(); ?>
</head>
<body>
<section id="main" role="main">
    <div class="container" style="margin-top:100px;">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <div class="panel" name="form-login">
                    <div class="panel-body">
                        <div class=" text-center">

                            <img src="<?php echo $view['assets']->getUrl('app\assets\images\Logo-destiny.png'); ?>" height="100px" width="100px" />

                        </div>
                        <br>
                        <div id="main-panel-flash-msgs">
                            <?php echo $view->render('MauticCoreBundle:Notification:flashes.html.php'); ?>
                        </div>
                        <?php $view['slots']->output('_content'); ?>
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-lg-4 col-lg-offset-4 text-center text-muted">
                <?php echo $view['translator']->trans('mautic.core.copyright', ['%date%' => date('Y')]); ?>
            </div>
        </div>
    </div>
</section>
<?php echo $view['security']->getAuthenticationContent(); ?>
</body>
</html>
