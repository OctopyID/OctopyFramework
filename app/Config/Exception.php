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
     | LOG EXCEPTIONS
     |--------------------------------------------------------------------------
     | If true, then exceptions will be logged
     |
     | Default: true
     */
    'log' => true,

    /*
     |--------------------------------------------------------------------------
     | DO NOT LOG STATUS CODES
     |--------------------------------------------------------------------------
     | Any status codes here will NOT be logged if logging is turned on.
     | By default, only 404 (Page Not Found) exception are ignored.
     |
     */
    'ignored' => [
        404,
    ],
];
