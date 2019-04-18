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

namespace Octopy\HTTP\Request;

use Octopy\Support\Arr;

class Collection
{
    /**
     * @var array
     */
    protected $parameter;

    /**
     * @param array $parameter
     */
    public function __construct(array $parameter = [])
    {
        $this->parameter = $parameter;
    }

    /**
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $value = Arr::get($this->parameter, $key, $default);

        if (is_numeric($value)) {
            $value += 0;
        }

        return $value ?? $default;
    }

    /**
     * @return array
     */
    public function all() : array
    {
        return $this->parameter;
    }
}
