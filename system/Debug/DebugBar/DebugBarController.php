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

namespace Octopy\Debug\DebugBar;

use Throwable;

use Octopy\HTTP\Response;
use Octopy\HTTP\Controller;

class DebugBarController extends Controller
{
    /**
     * @return string
     */
    public function index()
    {
        // idk
        return '';
    }

    /**
     * @param  string   $filename
     * @param  Response $response
     * @return Response
     */
    public function assets(string $filename, Response $response)
    {
        $array = explode('.', $filename);
        switch (end($array)) {
            case 'css':
                $response->header('Content-Type', 'text/css');
            break;
            case 'js':
                $response->header('Content-Type', 'text/javascript');
            break;
            case 'woff':
                $response->header('Content-Type', 'application/font-woff');
            break;
        }

        return $this->get($filename);
    }

    /**
     * @param  string $path
     * @return string
     */
    public function get(string $path) : string
    {
        try {
            return $this->app['filesystem']->get(__DIR__ . '/View/assets/' . $path);
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }
}
