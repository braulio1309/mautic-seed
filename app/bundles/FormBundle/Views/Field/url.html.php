<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
echo $view->render(
    'MauticFormBundle:Field:text.html.php',
    [
        'field'    => $field,
        'fields'   => isset($fields) ? $fields : [],
        'inForm'   => (isset($inForm)) ? $inForm : false,
        'type'     => 'url',
        'id'       => $id,
        'formId'   => (isset($formId)) ? $formId : 0,
        'formName' => (isset($formName)) ? $formName : '',
    ]
);
