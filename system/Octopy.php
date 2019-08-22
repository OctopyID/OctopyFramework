<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

/*
|---------------------------------------------------------------
| SETUP OUR IMPORTANT CONSTANTS
|---------------------------------------------------------------
|
| We always using DS constant instead DIRECTORY_SEPARATOR
| for avoid problem in Windows System
*/

if (! defined('DS')) {
    define('DS', '/');
}

if (! defined('BS')) {
    define('BS', '\\');
}

/*
|--------------------------------------------------------------------------
| JUST ROOT PATH DIRECTORY PROJECT
|--------------------------------------------------------------------------
*/
$basepath = dirname(__DIR__) . DS;

/*
|--------------------------------------------------------------------------
| LOAD OUR AUTOLOADER
|--------------------------------------------------------------------------
|
| The autoloader allows all of the pieces to work together
| in the framework. We have to load it here, though, so
| that the config files can use the path constants.
*/
require 'Autoload.php';

$autoload = new Octopy\Autoload($basepath, [
    'App'    => 'app',
    'Octopy' => 'system',
]);

$autoload->composer();

/**
 *
 */
require 'Common.php';

/**
 * @var Octopy\Application
 */
$app = new Octopy\Application($basepath);

$app->instance(Octopy\Autoload::class, $autoload);

/*
 |---------------------------------------------------------------
 | LAUNCH THE APPLICATION
 |---------------------------------------------------------------
 | Now that everything is setup, it's time to actually fire
 | up the engines and make this app do its thang.
 |
 */
return $app;
