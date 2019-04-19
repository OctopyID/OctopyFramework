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

return [

    /*
     |--------------------------------------------------------------------------
     | Debugbar Settings
     |--------------------------------------------------------------------------
     |
     | Debugbar is enabled by default, when debug is set to true in app.php.
     | You can override the value by setting enable to true or false instead of null.
     |
     | You can provide an array of URI's that must be ignored (eg. 'api/*')
     |
     */
    'enable' => true,

    /*
     |--------------------------------------------------------------------------
     | Excluding URI
     |--------------------------------------------------------------------------
     |
     | You can provide an array of URI's that must be ignored (eg. 'api/*')
     |
     */
    'except' => [
        //
    ],

    /*
     |--------------------------------------------------------------------------
     | Debugbar Collector
     |--------------------------------------------------------------------------
     |
     | Comment out Collector for disable from debugbar
     |
     */
    'collector' => [
        Octopy\DebugBar\Collector\FileCollector::class,
        Octopy\DebugBar\Collector\ViewCollector::class,
    ],
];
