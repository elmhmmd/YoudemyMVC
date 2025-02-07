<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit65d4175bfbfa50c54abaf90d51af1303
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit65d4175bfbfa50c54abaf90d51af1303::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit65d4175bfbfa50c54abaf90d51af1303::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit65d4175bfbfa50c54abaf90d51af1303::$classMap;

        }, null, ClassLoader::class);
    }
}
