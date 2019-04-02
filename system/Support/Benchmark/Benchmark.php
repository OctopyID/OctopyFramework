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

namespace Octopy\Debug;

class Benchmark
{
    /**
     * @var array
     */
    protected $marker = [];

    /**
     * @param  string $name
     * @param  float  $time
     */
    public function mark(string $name, float $time = null)
    {
        $this->marker[$name] = $time ?? microtime(true);
    }

    /**
     * @param  string $start
     * @param  string $end
     * @param  int    $decimal
     * @return string
     */
    public function elapsed(string $start, string $end = null, int $decimal = 4) : string
    {
        if ($start === '') {
            return '{elapsed}';
        }

        if (!isset($this->marker[$start])) {
            return '';
        }

        if (!isset($this->marker[$end])) {
            $this->marker[$end] = microtime(true);
        }

        return number_format($this->marker[$end] - $this->marker[$start], $decimal);
    }
}
