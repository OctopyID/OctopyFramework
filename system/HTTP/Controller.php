<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

namespace Octopy\HTTP;

use Octopy\Application;
use Octopy\HTTP\Middleware\ControllerMiddleware;

class Controller
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $middleware = [];

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param  array $middleware
     * @param  array $option
     * @return mixed
     */
    public function middleware($middleware = [], array $option = [])
    {
        if (empty($middleware)) {
            return $this->middleware;
        }

        foreach ((array) $middleware as $layer) {
            $this->middleware[] = [
                'option'     => &$option,
                'middleware' => $layer,
            ];
        }

        return new ControllerMiddleware($option);
    }

    /**
     * @param  Request $request
     * @param  array   $rules
     * @return bool
     */
    public function validate(Request $request, array $rules = [])
    {
        return $request->validate($rules);
    }
}
