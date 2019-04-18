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

namespace Octopy\Console;

class Argv
{
    /**
     * @var string
     */
    protected $command;

    /**
     * @var array
     */
    protected $option = [];

    /**
     * @param array $argv
     */
    public function __construct(array $argv = [])
    {
        if (isset($argv[1])) {
            $this->command = $argv[1];
        }

        foreach ($argv = array_slice($argv, 2) as $i => $value) {
            if (preg_match('/^--(.*?)/', $value)) {
                if (count($array = explode('=', $value, 2)) > 1) {
                    $this->option[$array[0]] = $array[1];
                } else {
                    $this->option[$array[0]] = true;
                }
            } elseif (preg_match('/^-(.*?)/', $value) && strlen($value) === 2) {
                $this->option[$value] = $argv[$i + 1] ?? true;
            } elseif (!array_key_exists('value', $this->option)) {
                $this->option['value'] = $value;
            }
        }
    }

    /**
     * @param  array $option
     * @return void
     */
    public function option(array $option)
    {
        $this->option = array_merge($this->option, $option);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($default = $this->get('value')) {
            return $default;
        }
    }

    /**
     * @param  string $command
     * @return mixed
     */
    public function command(string $command = null)
    {
        if (!is_null($command)) {
            return $this->command === $command;
        }

        return $this->command;
    }

    /**
     * @param  string $key
     * @return string
     */
    public function get(string $key)
    {
        return $this->option[$key] ?? false;
    }

    /**
     * @param string $key
     */
    public function remove(string $key)
    {
        if (isset($this->option[$key])) {
            unset($this->option[$key]);
        }
    }
}
