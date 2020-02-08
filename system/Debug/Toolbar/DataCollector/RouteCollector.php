<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

namespace Octopy\Debug\Toolbar\DataCollector;

use Closure;

class RouteCollector extends Collector
{
    /**
     * @var string
     */
    public $name = 'Route';

    /**
     * @var boolean
     */
    public $badge = false;

    /**
     * @return array
     */
    public function collect()
    {
        $route = $this->app->router->current();

        $middleware = [];
        foreach ($route->middleware as $layer) {
            $middleware[] = $layer instanceof Closure ? 'Closure' : $layer;
        }

        return [
            'uri'        => $route->uri,
            'name'       => $route->name,
            'method'     => $route->method,
            'parameter'  => $route->parameter,
            'middleware' => $middleware,
            'controller' => $route->controller instanceof Closure ? 'Closure' : $route->controller,
        ];
    }

    /**
     * @return string
     */
    public function icon() : string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAMAAADXqc3KAAAAM1BMVEUAAAA0SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV4JtAQBAAAAEHRSTlMAChESIiM3QH+ChZHD6ev9naZH3AAAAGNJREFUKM+tkEEOwjAQxNwAHUoK9f9f2xMIJemhAp8sWZqVFt4sqnd6rEl1FAI5F46mHqrLIBANv4XrasN6A5g2O7YJmB0wf0+38r9QqtZCLy/VJ3Ty+XYr58PhjUuSFBjImB0oNwy5+WrIEgAAAABJRU5ErkJggg==';
    }
}
