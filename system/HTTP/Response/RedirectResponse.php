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

namespace Octopy\HTTP\Response;

use Octopy\Container;
use Octopy\HTTP\Request;
use Octopy\HTTP\Response;
use Octopy\HTTP\Routing\URLGenerator;

class RedirectResponse extends Response
{
    /**
     * @param string $location
     * @param int    $status
     * @param array  $header
     */
    public function __construct(string $location = '/', int $status = 302, array $header = [])
    {
        parent::__construct('', $status, array_merge($header, [
            'Location' => $location
        ]));
    }

    /**
     * @param  int   $status
     * @param  array $header
     * @return Response
     */
    public function back(int $status = 302, array $header = [])
    {
        return $this->header('Location', $_SERVER['HTTP_REFERER'] ?? '/');
    }
}
