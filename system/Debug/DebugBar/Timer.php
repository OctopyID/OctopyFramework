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

namespace Octopy\Debug\ToolBar;

use RuntimeException;

class Timer
{
    /**
     * @var array
     */
    protected $timers = [];

    /**
     * @param  string $name
     * @param  float  $start
     * @return $this
     */
    public function start(string $name, float $start = null) : Timer
    {
        $this->timers[$name] = [
            'start' => $start ?? microtime(true),
            'end'   => null,
        ];

        return $this;
    }

    /**
     * @param  string $name
     * @return $this
     */
    public function stop(string $name)
    {
        if (!$this->has($name)) {
            throw new RuntimeException('Cannot stop timer: invalid name given.');
        }

        $this->timers[$name]['end'] = microtime(true);

        return $this;
    }

    /**
     * @param  string $name
     * @param  int    $decimal
     * @return float
     */
    public function elapsed(string $name, int $decimal = 4)
    {
        if (empty($this->timers[$name])) {
            return null;
        }

        $timer = $this->timers[$name];

        if (empty($timer['end'])) {
            $timer['end'] = microtime(true);
        }

        return (float)number_format($timer['end'] - $timer['start'], $decimal);
    }

    /**
     * @param  int $decimal
     * @return array
     */
    public function timers(int $decimal = 4) : array
    {
        $timers = $this->timers;

        foreach ($timers as &$timer) {
            if (empty($timer['end'])) {
                $timer['end'] = microtime(true);
            }

            $timer['duration'] = (float)number_format($timer['end'] - $timer['start'], $decimal);
        }

        return $timers;
    }

    /**
     * @param  string $name
     * @return bool
     */
    public function has(string $name) : bool
    {
        return array_key_exists(strtolower($name), $this->timers);
    }
}
