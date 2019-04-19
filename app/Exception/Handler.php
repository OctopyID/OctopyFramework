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

namespace App\Exception;

use Throwable;
use Octopy\HTTP\Request;
use Octopy\Exception\ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * @param Throwable $exception
     */
    public function report(Throwable $exception)
    {
        return parent::report($exception);
    }

    /**
     * @param  Request   $request
     * @param  Throwable $exception
     * @return mixed
     */
    public function render(Request $request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
}
