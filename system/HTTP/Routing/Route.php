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

class Route
{
    /**
     * @var array
     */
    public $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = array_merge($data, [
            'name' => ''
       ]);
    }

    /**
     * @param  string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->data[$key];
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function __set(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @return string
     */
    public function uri() : string
    {
        return $this->data['uri'];
    }

    /**
     * @return array
     */
    public function method() : array
    {
        return $this->data['method'];
    }

    /**
     * @param  string $name
     * @return Route
     */
    public function name(string $name = null)
    {
        if (is_null($name)) {
            return $this->data['name'];
        }
        
        return $this->update('name', trim($name));
    }

    /**
     * @param  mixed $middleware
     * @return Route
     */
    public function middleware(...$middleware)
    {
        return $this->update('middleware', array_merge($this->data['middleware'], $middleware));
    }

    /**
     * @param  array $parameter
     * @return Route
     */
    public function parameter(array $parameter)
    {
        return $this->update('parameter', array_merge($this->data['parameter'], $parameter));
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return Route
     */
    protected function update(string $key, $value) : Route
    {
        $this->data[$key] = $value;
        return $this;
    }
}
