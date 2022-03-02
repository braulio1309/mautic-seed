<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8c073d00f5e1457a6c78b37c474825a3
{
    public static $prefixLengthsPsr4 = [
        'S' => [
            'Smsapi\\Client\\Tests\\' => 20,
            'Smsapi\\Client\\'        => 14,
        ],
    ];

    public static $prefixDirsPsr4 = [
        'Smsapi\\Client\\Tests\\' => [
            0 => __DIR__.'/../..'.'/tests',
        ],
        'Smsapi\\Client\\' => [
            0 => __DIR__.'/../..'.'/src',
        ],
    ];

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8c073d00f5e1457a6c78b37c474825a3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8c073d00f5e1457a6c78b37c474825a3::$prefixDirsPsr4;
        }, null, ClassLoader::class);
    }
}
