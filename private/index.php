#!/usr/bin/php
<?php
/**
 * Set the PHP error reporting level.
 *
 * When developing your application, it is highly recommended to enable notices
 * and strict warnings. Enable them by using: E_ALL | E_STRICT
 *
 * In a production environment, it is safe to ignore notices and strict warnings.
 * Disable them by using: E_ALL ^ E_NOTICE
 */
error_reporting(E_ALL | E_STRICT);

if (!defined('__ROOT__')) {
    /**
     * Full path to the docroot
     */
    define('__ROOT__', dirname(__DIR__));
}

if (!function_exists('__')) {

    /**
     * Translation function
     * @see Ice\I18n translate()
     */
    function __($string, array $values = null, $lang = null)
    {
        return \Ice\I18n::fetch()->translate($string, $values, $lang);
    }

}

/**
 * Register namespaces
 */
(new Ice\Loader())
        ->addNamespace('App', __ROOT__ . '/app')
        ->register();

// Include composer's autolader
include_once __ROOT__ . '/vendor/autoload.php';

/**
 * Execute the main request
 */
try {
    // Initialize the application, handle a MVC request and display the HTTP response body
    (new App\Console(new Ice\Di()))
            ->initialize()
            ->handle($argv);
} catch (Exception $e) {
    // Do something with the exception depending on the environment
    if (!$e instanceof App\Error) {
        new App\Error($e->getMessage(), $e->getCode(), $e);
    }
}