<?php
$stderr = fopen('php://stderr', 'w');

fwrite($stderr, "\nWriting initial Mautic config\n");

$parameters = array(
	'db_driver'      => 'pdo_mysql',
	'install_source' => 'Docker',
	'mailer_transport' => 'mautic.transport.amazon_api',
	'db_table_prefix' => null,
	'db_backup_tables' => false,
	'db_backup_prefix' => 'bak_',
	'mailer_host' => null,
	'mailer_port' => null,
	'mailer_amazon_other_region' => null,
	'mailer_api_key' => null,
	'mailer_encryption' => null,
	'mailer_auth_mode' => null,
	'mailer_spool_type' => 'memory',
	'mailer_spool_path' => '%kernel.root_dir%/../var/spool',
);

if(array_key_exists('MAUTIC_DB_HOST', $_ENV)) {
    // Figure out if we have a port in the database host string
    if (strpos($_ENV['MAUTIC_DB_HOST'], ':') !== false) {
        list($host, $port) = explode(':', $_ENV['MAUTIC_DB_HOST'], 2);
        $parameters['db_port'] = $port;
    }
    else {
        $host = $_ENV['MAUTIC_DB_HOST'];
    }
    $parameters['db_host'] = $host;
}
if(array_key_exists('MAUTIC_DB_NAME', $_ENV)) {
    $parameters['db_name'] = $_ENV['MAUTIC_DB_NAME'];
}
if(array_key_exists('MAUTIC_DB_TABLE_PREFIX', $_ENV)) {
    $parameters['db_table_prefix'] = $_ENV['MAUTIC_DB_TABLE_PREFIX'];
}
if(array_key_exists('MAUTIC_DB_USER', $_ENV)) {
    $parameters['db_user'] = $_ENV['MAUTIC_DB_USER'];
}
if(array_key_exists('MAUTIC_DB_PASSWORD', $_ENV)) {
    $parameters['db_password'] = $_ENV['MAUTIC_DB_PASSWORD'];
}
if(array_key_exists('MAUTIC_TRUSTED_PROXIES', $_ENV)) {
    $proxies = explode(',', $_ENV['MAUTIC_TRUSTED_PROXIES']);
    $parameters['trusted_proxies'] = $proxies;
}

if(array_key_exists('PHP_INI_DATE_TIMEZONE', $_ENV)) {
    $parameters['default_timezone'] = $_ENV['PHP_INI_DATE_TIMEZONE'];
}

if(array_key_exists('AWS_ACCESS_KEY_ID', $_ENV)) {
    $parameters['mailer_user'] = $_ENV['AWS_ACCESS_KEY_ID'];
}

if(array_key_exists('AWS_SECRET_ACCESS_KEY', $_ENV)) {
    $parameters['mailer_password'] = $_ENV['AWS_SECRET_ACCESS_KEY'];
}

if(array_key_exists('AWS_DEFAULT_REGION', $_ENV)) {
    $parameters['mailer_amazon_region'] = $_ENV['AWS_DEFAULT_REGION'];
}

if(array_key_exists('AWS_SENDER_NAME', $_ENV)) {
    $parameters['mailer_from_name'] = $_ENV['AWS_SENDER_NAME'];
}

if(array_key_exists('AWS_SENDER_EMAIL', $_ENV)) {
    $parameters['mailer_from_email'] = $_ENV['AWS_SENDER_EMAIL'];
}

if(array_key_exists('SITE_URL', $_ENV)) {
    $parameters['site_url'] = $_ENV['SITE_URL'];
}

if(array_key_exists('MAUTIC_SECRET_KEY', $_ENV)) {
    $parameters['secret_key'] = $_ENV['MAUTIC_SECRET_KEY'];
}

if(array_key_exists('MAUTIC_ADMIN_USERNAME', $_ENV)) {
    $parameters['mautic_admin_username'] = $_ENV['MAUTIC_ADMIN_USERNAME'];
}

if(array_key_exists('MAUTIC_ADMIN_EMAIL', $_ENV)) {
    $parameters['mautic_admin_email'] = $_ENV['MAUTIC_ADMIN_EMAIL'];
}

if(array_key_exists('MAUTIC_ADMIN_PASSWORD', $_ENV)) {
    $parameters['mautic_admin_password'] = $_ENV['MAUTIC_ADMIN_PASSWORD'];
}

$path     = '/var/www/html/app/config/local.php';
$rendered = "<?php\n\$parameters = ".var_export($parameters, true).";\n";

$status = file_put_contents($path, $rendered);

if ($status === false) {
	fwrite($stderr, "\nCould not write configuration file to $path, you can create this file with the following contents:\n\n$rendered\n");
}
