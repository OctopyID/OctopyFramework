<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @version : v1.0
 * @license : MIT
 */

namespace Octopy\Database;

abstract class Seeder
{
    /**
     * @var array
     */
    protected $seeder = [];

    /**
     * @param  array $seeder
     * @return void
     */
    public function call(...$seeder)
    {
        if (empty($seeder)) {
            return $this->seeder;
        }

        $this->seeder = array_merge($this->seeder, $seeder);
    }

    /**
     * @return void
     */
    abstract public function seed();
}
