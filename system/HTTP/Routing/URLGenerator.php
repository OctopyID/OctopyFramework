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

namespace Octopy\HTTP\Routing;

use Octopy\Application;
use Octopy\HTTP\Routing\Exception\MissingParameterException;
use Octopy\HTTP\Routing\Exception\RouteNameNotExistException;

class URLGenerator
{
    /**
     * @var array
     */
    protected $app;

    /**
     * @param  Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param  string $name
     * @param  array  $default
     * @return string
     */
    public function route(string $name, array $default = [])
    {
        $collection = $this->app['router']->collection->alias();
        if (array_key_exists($name, $collection)) {
            preg_match($collection[$name]->pattern, $collection[$name]->uri, $matches);

            $passed = [];
            $default = array_merge($collection[$name]->parameter, $default);

            foreach ($required = array_slice($matches, 1) as $key => $value) {
                if (isset($default[$key])) {
                    $passed[$value] = $default[$key];
                }
            }

            if (count($passed) !== (count($required) / 2)) {
                throw new MissingParameterException;
            }

            return str_replace(array_keys($passed), $passed, $collection[$name]->uri);
        }

        throw new RouteNameNotExistException("Route name [$name] doesn't exists.");
    }

    /**
     * @param  string $url
     * @return string
     */
    public function url(string $url) : string
    {
        return $this->app['config']['app.url'] . $url;
    }
}
