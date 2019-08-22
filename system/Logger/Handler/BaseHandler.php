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

namespace Octopy\Logger\Handler;

abstract class BaseHandler
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $datetime;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->datetime = date($config['dateformat'] ?? 'Y-m-d H:i:s');
    }

    /**
     * @param  string $property
     * @return mixed
     */
    public function __get(string $property)
    {
        return $this->$property ?? null;
    }

    /**
     * @param  string $key
     * @return mixed
     */
    abstract public function config(string $key = null);

    /**
     * @param  mixed  $level
     * @param  string $message
     * @return bool
     */
    abstract public function handle($level, string $message) : bool;
}
