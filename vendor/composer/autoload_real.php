<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit34d2c3dc6395b893177062beb521b27c
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

        spl_autoload_register(array('ComposerAutoloaderInit34d2c3dc6395b893177062beb521b27c', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit34d2c3dc6395b893177062beb521b27c', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit34d2c3dc6395b893177062beb521b27c::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
