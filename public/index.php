<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : www.octopy.xyz
 * @license : MIT
 */

/**
 * Check minimum requirement
 */
if (!version_compare(PHP_VERSION, '7.1', '>=')) {
    die('Your PHP version must be 7.1 or higher to run Octopy Framework. Current version: ' . PHP_VERSION);
}

/**
 *
 */
define('STARTING_TIME', microtime(true));

/**
 * Octopy\Application
 */
$app = require '../system/Octopy.php';

/**
 * App\HTTP\Kernel
 */
$kernel = $app->make(App\HTTP\Kernel::class);

$response = $kernel->handle(
    $request = $app->make(Octopy\HTTP\Request::class)
);

$response->send();

$kernel->terminate($request, $response);
