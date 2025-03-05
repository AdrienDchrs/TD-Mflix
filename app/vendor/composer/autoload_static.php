<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7c7e27d93ea1a67cd25bcaae5c6aae40
{
    public static $files = array (
        '3a37ebac017bc098e9a86b35401e7a68' => __DIR__ . '/..' . '/mongodb/mongodb/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'MongoDB\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'MongoDB\\' => 
        array (
            0 => __DIR__ . '/..' . '/mongodb/mongodb/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7c7e27d93ea1a67cd25bcaae5c6aae40::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7c7e27d93ea1a67cd25bcaae5c6aae40::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7c7e27d93ea1a67cd25bcaae5c6aae40::$classMap;

        }, null, ClassLoader::class);
    }
}
