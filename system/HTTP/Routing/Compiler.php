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

class Compiler
{
    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @param  string $uri
     * @return array
     */
    public function parse(string $uri) : array
    {
        return [$this->regexp($uri), $this->option($uri)];
    }

    /**
     * @param  string $uri
     * @return array
     */
    protected function option(string $uri) : array
    {
        if (preg_match_all('/\:(\w+?)\?/', $uri, $matches)) {
            return array_filter(array_fill_keys($matches[1], null), function ($value, $key) {
                return is_string($key);
            }, ARRAY_FILTER_USE_BOTH);
        }

        return [];
    }

    /**
     * @param  string $uri
     * @return string
     */
    protected function regexp(string $uri) : ?string
    {
        if (preg_match_all('/(?<=\/):([^\/]+)(?=\/|$)/', $uri, $matches)) {
            $search = '';
            $regexp = '/';
                
            foreach ($matches[1] as $key => $value) {
                $search .= '\/' . preg_quote($matches[0][$key]);
                $regexp .= sprintf($this->compute($matches[1], $key), trim($value, '?'));
            }

            $regexp = preg_replace('/' . $search . '/', rtrim($regexp, '/'), $uri);
            $regexp = preg_replace('/\/+/', '/', $regexp .= str_repeat(')?', $this->count));
        }
        
        $this->count = 0;

        return '#^' . ($regexp ?? str_replace('/', '\/', $uri)) . '$#sDu';
    }

    /**
     * @param  array $array
     * @param  int   $offset
     * @return bool
     */
    protected function compute(array $array, int $offset = 0)
    {
        $regexp = '(?P<%s>[^/]++)';

        if ($offset > 0 && substr($array[$offset], -1) === '?') {
            $regexp = '(?:/' . $regexp;

            $this->count++;
        }

        if ($offset === 0 && substr($array[$offset], -1) === '?') {
            $regexp .= '?';
        }

        if ($this->next($array, $offset) && $this->next($array, ++$offset)) {
            $regexp .= '/';
        }

        return $regexp;
    }

    /**
     * @param  array $array
     * @param  int   $offset
     * @return bool
     */
    protected function next(array $array, int $offset) : bool
    {
        if (!isset($array[$offset])) {
            return true;
        }

        return strstr($array[$offset], '?') !== '?';
    }
}
