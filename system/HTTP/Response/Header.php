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

namespace Octopy\HTTP\Response;

use Octopy\Support\DateTime;

class Header
{
    /**
     * @var array
     */
    protected $header = [];

    /**
     * @param array $header
     */
    public function __construct(array $header = [])
    {
        foreach ($header as $key => $value) {
            $this->set($key, $value);
        }

        if (!isset($this->header['Content-Type'])) {
            $this->set('Content-Type', 'text/html; charset=UTF-8');
        }

        if (!isset($this->header['Cache-Control'])) {
            $this->set('Cache-Control', 'no-store, max-age=0, no-cache');
        }

        if (!isset($this->header['Date'])) {
            $this->set('Date', (new DateTime)->now()->format('D, d M Y H:i:s') . ' GMT');
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (!$header = $this->all()) {
            return '';
        }

        ksort($header);
        $max = max(array_map('strlen', array_keys($header))) + 1;
        $content = '';
        foreach ($header as $name => $value) {
            $name = ucwords($name, '-');
            foreach ($value as $value) {
                $content .= sprintf("%-{$max}s %s\r\n", $name . ':', $value);
            }
        }

        return $content;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @param bool  $replace
     */
    public function set($key, $value = null, bool $replace = true)
    {
        if (is_array($value)) {
            $value = array_values($value);
            if ($replace === true || !isset($this->header[$key])) {
                $this->header[$key] = $value;
            } else {
                $this->header[$key] = array_merge($this->header[$key], $value);
            }
        } else {
            if ($replace === true || !isset($this->header[$key])) {
                $this->header[$key] = [$value];
            } else {
                $this->header[$key][] = $value;
            }
        }
        
        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed  $default
     * @param  bool   $first
     * @return mixed
     */
    public function get(string $key, $default = null, bool $first = true)
    {
        $header = $this->all();

        if (!array_key_exists($key, $header)) {
            if (null === $default) {
                return $first ? null : [];
            }

            return $first ? $default : [$default];
        }

        if ($first) {
            return count($header[$key]) ? $header[$key][0] : $default;
        }

        return $header[$key];
    }

    /**
     * @param  string $key
     * @return bool
     */
    public function has(string $key) : bool
    {
        return array_key_exists($key, $this->all());
    }

    /**
     * @return array
     */
    public function all() : array
    {
        return $this->header;
    }

    /**
     * @param  string $key
     * @return void
     */
    public function remove(string $key)
    {
        if ($this->has($key)) {
            unset($this->header[$key]);
        }
    }
}
