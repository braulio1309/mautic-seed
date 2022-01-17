<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

use Symfony\Component\DependencyInjection\Definition;

$root = $container->getParameter('kernel.root_dir');
include __DIR__.'/paths_helper.php';

//load default parameters from bundle files
$core    = $container->getParameter('mautic.bundles');
$plugins = $container->getParameter('mautic.plugin.bundles');

$bundles = array_merge($core, $plugins);
unset($core, $plugins);

$mauticParams = [];

foreach ($bundles as $bundle) {
    if (!empty($bundle['config']['parameters'])) {
        $mauticParams = array_merge($mauticParams, $bundle['config']['parameters']);
    }
}

// Set the parameters in the container with env processors
foreach ($mauticParams as $k => $v) {
    switch (true) {
        case is_bool($v):
            $type = 'bool:';
            break;
        case is_int($v):
            $type = 'intNullable:';
            break;
        case is_array($v):
            $type = 'json:';
            break;
        case is_float($v):
            $type = 'float:';
            break;
        default:
            $type = 'nullable:';
    }

    // Add to the container with the applicable processor
    $container->setParameter("mautic.{$k}", sprintf('%%env(%sresolve:MAUTIC_%s)%%', $type, mb_strtoupper($k)));
}

//Sessions settings
$storageDefinition = new Definition(PdoSessionHandler::class, [
    'mysql:host=%mautic.db_host%;port=%mautic.db_port%;dbname=%mautic.db_name%',
    ['db_username' => '%database_user%', 'db_password' => '%mautic.db_password%'],
]);

$container->register('session.handler.pdo', PdoSessionHandler::class)
    ->setArguments([
        'mysql:dbname=%mautic.db_name%',
        ['db_table' => 'sessions', 'db_username' => '%mautic.db_user%', 'db_password' => '%mautic.db_password%'],
    ]);

// Set the router URI for CLI
$container->setParameter('router.request_context.host', '%env(MAUTIC_REQUEST_CONTEXT_HOST)%');
$container->setParameter('router.request_context.scheme', '%env(MAUTIC_REQUEST_CONTEXT_SCHEME)%');
$container->setParameter('router.request_context.base_url', '%env(MAUTIC_REQUEST_CONTEXT_BASE_URL)%');
$container->setParameter('request_listener.http_port', '%env(MAUTIC_REQUEST_CONTEXT_HTTP_PORT)%');
$container->setParameter('request_listener.https_port', '%env(MAUTIC_REQUEST_CONTEXT_HTTPS_PORT)%');

unset($mauticParams, $replaceRootPlaceholder, $bundles);
