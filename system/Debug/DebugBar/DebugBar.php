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

namespace Octopy\Debug;

use Octopy\Application;
use Octopy\HTTP\Response;

class DebugBar
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return bool
     */
    public function enabled() : bool
    {
        return $this->app['config']['debugbar.enable'] && $this->app['config']['app.debug'];
    }

    /**
     * @param  Response $response
     * @return Response
     */
    public function modify(Response $response) : Response
    {
        $debugbar  = '<div class="debugbar"></div>' . $this->javascript();

        $position = strripos($body = $response->body, '</body>');
        if ($position !== false) {
            $body = substr($body, 0, $position) . $debugbar . substr($body, $position);
        } else {
            $body = $body . $debugbar;
        }

        // Update the new body and reset the body length
        $response->body($body);
        $response->header->remove('Content-Length');

        return $response;
    }

    /**
     * @return string
     */
    private function javascript() : string
    {
        $js  = '<script type="text/javascript" src="' . route('debugbar.assets', 'jquery.js') . '"></script>';
        $js .= '<script type="text/javascript">$(document).ready(function(){$("div.debugbar").load("' . route('debugbar') . '");});</script>';

        return $js;
    }
}
