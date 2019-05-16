<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : www.octopy.xyz
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
defined('DS') || define('DS', '/');

defined('BS') || define('BS', '\\');

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
    'App' => 'app',
    'Octopy' => 'system',
]);

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
 * @return Octopy\Application
 */
return $app;
