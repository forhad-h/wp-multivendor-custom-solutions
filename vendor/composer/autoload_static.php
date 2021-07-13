<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5fbd97e47ab29fe0cb76532a16bb17c2
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'GRONC\\WCFM\\' => 11,
            'GRONC\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'GRONC\\WCFM\\' => 
        array (
            0 => __DIR__ . '/../..' . '/wcfm',
        ),
        'GRONC\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5fbd97e47ab29fe0cb76532a16bb17c2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5fbd97e47ab29fe0cb76532a16bb17c2::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5fbd97e47ab29fe0cb76532a16bb17c2::$classMap;

        }, null, ClassLoader::class);
    }
}
