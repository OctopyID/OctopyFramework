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

use Octopy\Application;
use Octopy\HTTP\Request;
use Octopy\HTTP\Middleware\Exception\TokenMismatchException;

class VerifyCSRFToken
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $except = [
        //
    ];

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param  Request $request
     * @param  Closure $next
     * @return Request
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->method() !== 'POST' || $request->is(array_merge($this->except, ['__debugbar']))) {
            return $next($request);
        }

        $token = $request->header('X-CSRF-TOKEN') ?? $request->__TOKEN__;

        if ($token === $this->app['session']->get('X-CSRF-TOKEN')) {
            $time = time() - $this->app['session']->get('X-CSRF-TOKEN-EXPIRE');
            if ($time < $this->app['config']['session.lifetime']) {
                return $next($request);
            }
        }

        throw new TokenMismatchException;
    }

    /**
     * @return string
     */
    public function generate() : string
    {
        if (!$this->app['session']->has('X-CSRF-TOKEN')) {
            $token = sha1(random_bytes(32));
        } else {
            $token = $this->app['session']->get('X-CSRF-TOKEN');
        }

        $this->app['session']->set([
            'X-CSRF-TOKEN'        => $token,
            'X-CSRF-TOKEN-EXPIRE' => microtime(true)
        ]);

        return $token;
    }
}
