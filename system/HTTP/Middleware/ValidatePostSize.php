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

namespace Octopy\HTTP\Middleware;

use Closure;

use Octopy\HTTP\Request;
use Octopy\HTTP\Middleware\Exception\PostTooLargeException;

class ValidatePostSize
{
    /**
     * @param  Request $request
     * @param  Closure $next
     * @return Request
     */
    public function handle(Request $request, Closure $next)
    {
        $max = $this->size();

        if ($max > 0 && $request->server('CONTENT_LENGTH') > $max) {
            throw new PostTooLargeException;
        }

        return $next($request);
    }

    /**
     * @return int
     */
    protected function size() : int
    {
        if (is_numeric($size = ini_get('post_max_size'))) {
            return (int)$size;
        }

        $metric = strtoupper(substr($size, -1));

        switch ($metric) {
            case 'K':
                return (int)$size * 1024;
            case 'M':
                return (int)$size * 1048576;
            case 'G':
                return (int)$size * 1073741824;
            default:
                return (int)$size;
        }
    }
}
