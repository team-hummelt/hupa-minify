<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4e173bb1270325f273c9eba2a767da14
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'ScssPhp\\ScssPhp\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ScssPhp\\ScssPhp\\' => 
        array (
            0 => __DIR__ . '/..' . '/scssphp/scssphp/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4e173bb1270325f273c9eba2a767da14::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4e173bb1270325f273c9eba2a767da14::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4e173bb1270325f273c9eba2a767da14::$classMap;

        }, null, ClassLoader::class);
    }
}
