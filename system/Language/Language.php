<?php

/*
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

namespace Octopy;

use ArrayAccess;
use Octopy\Support\Arr;
use Octopy\Language\Exception\TranslationNotDefinedException;

class Language implements ArrayAccess
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $translated = [];

    /**
     * @param  Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param  string $key
     * @return bool
     */
    public function has(string $key)
    {
        return Arr::has($this->translated, $key);
    }

    /**
     * @param  string $key
     * @param  string $default
     * @return string
     */
    public function get($key, string $default = null)
    {
        // lazy load
        if (! $this->has($key)) {
            $this->load($key);
        }

        if (is_array($key)) {
            return $this->many($key);
        }

        return Arr::get($this->translated, $key, $default);
    }

    /**
     * @param  array $keys
     * @return array
     */
    public function many(array $keys)
    {
        $config = [];

        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                [$key, $default] = [$default, null];
            }

            $config[$key] = Arr::get($this->translated, $key, $default);
        }

        return $config;
    }

    /**
     * @param  mixed $key
     * @param  mixed $value
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            Arr::set($this->translated, $key, $value);
        }
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     */
    public function prepend($key, $value)
    {
        $array = $this->get($key);

        array_unshift($array, $value);

        $this->set($key, $array);
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     */
    public function push(string $key, $value)
    {
        $this->set($key, array_merge($this->get($key), (array) $value));
    }

    /**
     * @return array
     */
    public function all() : array
    {
        return $this->translated;
    }

    /**
     * @param  string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * @param  string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * @param  string $key
     */
    public function offsetUnset($key)
    {
        $this->set($key, null);
    }

    /**
     * @param  string $key
     */
    protected function load(string $key)
    {
        $arr = explode('.', $key);

        $translation = $this->app->translation(
            $this->app->config['app.locale'] . '/' . ucfirst($arr[0]) . '.php'
        );

        if (! file_exists($translation)) {
            throw new TranslationNotDefinedException("Could not find translation for `$key`");
        }

        $this->translated[$arr[0]] = require $translation;
    }
}
