<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @version : v1.0
 * @license : MIT
 */

namespace App\HTTP\Middleware;

use Octopy\HTTP\Middleware\CheckMaintenanceMode as Middleware;

class CheckMaintenanceMode extends Middleware
{
    /**
     * @var array
     */
    protected $except = [
        //
    ];
}
