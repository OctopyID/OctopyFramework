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

namespace Octopy\DebugBar;

use Throwable;

use Octopy\HTTP\Response;
use Octopy\HTTP\Controller;

class DebugBarController extends Controller
{
    /**
     * @param  string   $directory
     * @param  string   $filename
     * @param  Response $response
     * @return Response
     */
    public function assets(string $directory, string $filename, Response $response)
    {
        $array = explode('.', $filename);
            
        if (!$content = $this->get($directory . DS . $filename)) {
            return $response->status(404);
        }

        $response->status(200);

        switch (end($array)) {
            case 'js':
                $response->header('Content-Type', 'text/javascript');
                break;
            case 'css':
                $response->header('Content-Type', 'text/css');
                break;
            default:
                $response->header('Content-Type', 'application/font-woff');
                break;
        }

        return $response->body($content);
    }

    /**
     * @param  string $filename
     * @return string
     */
    private function get(string $filename) : ?string
    {
        try {
            return $this->app->fsys->get(sprintf('%s/template/assets/%s', __DIR__, $filename));
        } catch (Throwable $exception) {
            return null;
        }
    }
}
