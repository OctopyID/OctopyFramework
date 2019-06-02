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

use Exception;
use Octopy\HTTP\Request;
use Octopy\Exception\ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * @param Exception $exception
     */
    public function report(Exception $exception)
    {
        return parent::report($exception);
    }

    /**
     * @param  Request   $request
     * @param  Exception $exception
     * @return mixed
     */
    public function render(Request $request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
}
