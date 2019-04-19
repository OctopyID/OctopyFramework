<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : www.octopy.xyz
 * @license : MIT
 */

namespace Octopy\Database;

class Connection
{
    /**
     * @var string
     */
    protected $driver;

    /**
     * @var array
     */
    protected $config;

    /**
     * @param string $driver
     * @param array  $config
     */
    public function __construct(string $driver, array $config)
    {
        $this->driver = $driver;
        $this->config = $config;
    }

    /**
     * @param  string $driver
     * @param  array  $config
     * @return PDO
     */
    public function connect()
    {
        switch (strtolower($this->driver)) {
            case 'mysql':
                return new \Octopy\Database\Driver\MySQL(
                    $this->config['hostname'],
                    $this->config['database'],
                    $this->config['username'],
                    $this->config['password']
                );
        }
    }
}
