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

namespace Octopy\HTTP\Routing;

use Closure;
use Octopy\HTTP\Routing\Exception\ResourceControllerException;

trait Resource
{
    /**
     * @param  string $uri
     * @param  string $controller
     * @param  array  $option
     * @return Route
     */
    public function resource(string $uri, $controller = null, array $option = [])
    {
        if (empty($option) && is_array($controller)) {
            $option = $controller;
            unset($controller);
        }

        if (! isset($controller)) {
            $controller = ucfirst($uri);

            if (isset($option['controller'])) {
                $controller = ucfirst(filter_var($option['controller'], FILTER_SANITIZE_STRING));
            }
        }

        if ($controller instanceof Closure) {
            throw new ResourceControllerException('Controller for route resource should not use Closure.');
        }

        $method = isset($option['only']) ? is_string($option['only']) ? explode(',', $option['only']) : $option['only'] : ['index', 'show', 'create', 'update', 'destroy', 'store', 'edit'];

        if (isset($option['except'])) {
            $option['except'] = is_array($option['except']) ? $option['except'] : explode(',', $option['except']);

            for ($int = 0; $int < count($method); $int++) {
                if (in_array($method[$int], $option['except'])) {
                    unset($method[$int]);
                }
            }
        }

        $name = $option['name'] ?? strtolower($uri);

        if (in_array('index', $method)) {
            $this->get($uri, $controller . '@index')->name($name . '.index');
        }

        if (in_array('create', $method)) {
            $this->get($uri . '/create', $controller . '@create')->name($name . '.create');
        }

        if (in_array('edit', $method)) {
            $this->get($uri . '/edit/:id', $controller . '@edit')->name($name . '.edit');
        }

        if (in_array('show', $method)) {
            $this->get($uri . '/show/:id', $controller . '@show')->name($name . '.show');
        }

        if (in_array('store', $method)) {
            $this->post($uri . '/store', $controller . '@store')->name($name . '.store');
        }

        if (in_array('update', $method)) {
            $this->post($uri . '/:id', $controller . '@update')->name($name . '.update');
        }

        if (in_array('destroy', $method)) {
            $this->post($uri . '/destroy/:id', $controller . '@destroy')->name($name . '.destroy');
        }
    }
}
