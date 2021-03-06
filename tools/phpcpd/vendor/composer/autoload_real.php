<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitcf430c715cbf328cdc1f46d35e58e796
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitcf430c715cbf328cdc1f46d35e58e796', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitcf430c715cbf328cdc1f46d35e58e796', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        \Composer\Autoload\ComposerStaticInitcf430c715cbf328cdc1f46d35e58e796::getInitializer($loader)();

        $loader->register(true);

        return $loader;
    }
}
