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

namespace App\HTTP\Middleware;

use Octopy\HTTP\Middleware\VerifyCSRFToken as Middleware;

class VerifyCSRFToken extends Middleware
{
    /**
     * @var array
     */
    protected $except = [
        //
    ];
}
