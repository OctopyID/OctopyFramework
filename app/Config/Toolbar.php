<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
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

    'enabled' => env('DEBUGBAR_ENABLED', true),

    'except' => [

    ],

    /*
     |--------------------------------------------------------------------------
     | DataCollectors
     |--------------------------------------------------------------------------
     |
     | Enable/disable DataCollectors
     |
     */
    'collectors' => [
        Octopy\Debug\Toolbar\DataCollector\RouteCollector::class,
        Octopy\Debug\Toolbar\DataCollector\ViewCollector::class,
        Octopy\Debug\Toolbar\DataCollector\VarsCollector::class,
        Octopy\Debug\Toolbar\DataCollector\FileCollector::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Max History
    |--------------------------------------------------------------------------
    | The Toolbar allows you to view recent requests that have been made to
    | the application while the toolbar is active. This allows you to quickly
    | view and compare multiple requests.
    |
    | history sets a limit on the number of past requests that are stored,
    | helping to conserve file space used to store them. You can set it to
    |
    | -1 for unlimited history.
    |
    */
    'history' => 20,

    /*
     |--------------------------------------------------------------------------
     | Storage settings
     |--------------------------------------------------------------------------
     |
     | DebugBar stores data for session/ajax requests.
     |
     */
    'storage' => $this->path->storage('debugbar'),

    /*
     |--------------------------------------------------------------------------
     | Inject Debugbar in Response
     |--------------------------------------------------------------------------
     |
     | Usually, the debugbar is added just before </body>, by listening to the
     | Response after the App is done. If you disable this, you have to add them
     | in your template yourself.
     |
     */
    'inject' => true,

    /*
     |--------------------------------------------------------------------------
     | DebugBar route prefix
     |--------------------------------------------------------------------------
     |
     | Sometimes you want to set route prefix to be used by DebugBar to load
     | its resources from. Usually the need comes from misconfigured web server or
     | from trying to overcome bugs like this: http://trac.nginx.org/nginx/ticket/97
     |
     */
    'prefix' => 'debugbar',
];
