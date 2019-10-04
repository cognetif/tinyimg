<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInited8929558c940dc561f2a22c45295127
{
    public static $files = array (
        '74ed299072414d276bb7568fe71d5b0c' => __DIR__ . '/..' . '/tinify/tinify/lib/Tinify.php',
        '9635627915aaea7a98d6d14d04ca5b56' => __DIR__ . '/..' . '/tinify/tinify/lib/Tinify/Exception.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Tinify\\' => 7,
        ),
        'P' => 
        array (
            'Psr\\Container\\' => 14,
        ),
        'C' => 
        array (
            'Cognetif\\TinyImg\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Tinify\\' => 
        array (
            0 => __DIR__ . '/..' . '/tinify/tinify/lib/Tinify',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Cognetif\\TinyImg\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib/TinyImg',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Pimple' => 
            array (
                0 => __DIR__ . '/..' . '/pimple/pimple/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInited8929558c940dc561f2a22c45295127::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInited8929558c940dc561f2a22c45295127::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInited8929558c940dc561f2a22c45295127::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
