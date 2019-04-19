<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 *
 * @author  : Supian M <supianidz@gmail.com>
 *
 * @link    : www.octopy.xyz
 *
 * @license : MIT
 */

namespace App\HTTP;

use Octopy\HTTP\Kernel as HTTPKernel;

class Kernel extends HTTPKernel
{
    /**
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\HTTP\Middleware\CheckMaintenanceMode::class,
        \App\HTTP\Middleware\VerifyCSRFToken::class,
    ];

    /**
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routemiddleware = [
        //
    ];
}
