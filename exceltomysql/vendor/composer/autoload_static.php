<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4e2f3c9edac50dbe6ad0f9141de03198
{
    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PHPExcel' => 
            array (
                0 => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit4e2f3c9edac50dbe6ad0f9141de03198::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit4e2f3c9edac50dbe6ad0f9141de03198::$classMap;

        }, null, ClassLoader::class);
    }
}
