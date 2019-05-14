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
    | Default Session Handler
    |--------------------------------------------------------------------------
    |
    | This option controls the default session "handler" that will be used on
    | requests. By default, we will use the lightweight native handler but
    | you may specify any of the other wonderful handlers provided here.
    |
    | Supported: "file"
    |
    */
    'handler' => 'file',

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Here you may specify the number of seconds that you wish the session
    | to be allowed to remain idle before it expires. If you want them
    | to immediately expire on the browser closing, set that option.
    |
    */
    'lifetime' => env('SESSION_LIFETIME', 3600),

    /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | This option allows you to easily specify that all of your session data
    | should be encrypted before it is stored. All encryption will be run
    | automatically by Octopy and you can use the Session like normal.
    |
    */
    'encrypt' => true,

    /*
    |--------------------------------------------------------------------------
    | Session File Storage
    |--------------------------------------------------------------------------
    |
    | When using the native session handler, we need a location where session
    | files may be stored. A default has been set for you but a different
    | location may be specified. This is only needed for file sessions.
    |
    */
    'storage' => $this->path->storage('framework/session'),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Path
    |--------------------------------------------------------------------------
    |
    | The session cookie path determines the path for which the cookie will
    | be regarded as available. Typically, this will be the root path of
    | your application but you are free to change this when necessary.
    |
    */
    'path' => '/',

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Domain
    |--------------------------------------------------------------------------
    |
    | Here you may change the domain of the cookie used to identify a session
    | in your application. This will determine which domains the cookie is
    | available to in your application. A sensible default has been set.
    |
    */
    'domain' => env('SESSION_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | HTTPS Only Cookies
    |--------------------------------------------------------------------------
    |
    | By setting this option to true, session cookies will only be sent back
    | to the server if the browser has a HTTPS connection. This will keep
    | the cookie from being sent to you if it can not be done securely.
    |
    */
    'secure' => env('SESSION_SECURE_COOKIE', false),

    /*
    |--------------------------------------------------------------------------
    | HTTP Access Only
    |--------------------------------------------------------------------------
    |
    | Setting this value to true will prevent JavaScript from accessing the
    | value of the cookie and the cookie will only be accessible through
    | the HTTP protocol. You are free to modify this option if needed.
    |
    */
    'httponly' => true,
];
