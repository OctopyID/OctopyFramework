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

namespace Octopy\HTTP\Routing;

use Octopy\Application;
use Octopy\HTTP\Routing\Exception\MissingParameterException;
use Octopy\HTTP\Routing\Exception\RouteNameNotExistException;

class URLGenerator
{
    /**
     * @var array
     */
    protected $route;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->route = $app->router->collection->alias();
    }

    /**
     * @param  string $name
     * @param  array  $default
     * @return string
     */
    public function route(string $name, array $default = [])
    {
        if (array_key_exists($name, $this->route)) {
            preg_match($this->route[$name]->pattern, $this->route[$name]->uri, $matches);

            $passed  = [];
            $default = array_merge($this->route[$name]->parameter, $default);
            
            foreach ($required = array_slice($matches, 1) as $key => $value) {
                if (isset($default[$key])) {
                    $passed[$value] = $default[$key];
                }
            }

            if (count($passed) !== (count($required) / 2)) {
                throw new MissingParameterException;
            }

            return str_replace(array_keys($passed), $passed, $this->route[$name]->uri);
        }

        throw new RouteNameNotExistException;
    }
}
