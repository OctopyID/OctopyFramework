<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @version : v1.0
 * @license : MIT
 */

namespace Octopy\Database\Migration;

use Closure;
use Exception;

use Octopy\Application;
use Octopy\Database\Exception\DBException;

class Schema
{
    /**
     * @var Octopy\Database\Migration\BluePrint
     */
    protected $blueprint;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        try {
            $this->blueprint = $app->make(
                \Octopy\Database\Migration\BluePrint\MySQL::class
            );
        } catch (DBException $exception) {
            throw $exception;
        }
    }

    /**
     * @param  string  $table
     * @param  Closure $callback
     * @return BluePrint
     */
    public function create(string $table, Closure $callback)
    {
        if ($callback instanceof Closure) {
            $callback($this->blueprint);
        }

        return $this->blueprint->create($table);
    }

    /**
     * @param string $table
     */
    public function drop(string $table)
    {
        return $this->blueprint->drop($table);
    }
}
