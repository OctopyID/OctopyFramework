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

use Octopy\Application;

abstract class BluePrint
{
    /**
     * @var Octopy\Application;
     */
    protected $app;

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var array
     */
    protected $index = [];

    /**
     * @var array
     */
    protected $primary = [];
        
    /**
     * @var array
     */
    protected $unique = [];

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param  string $query
     * @return bool
     */
    protected function query(string $query)
    {
        return $this->app['database']->query($query);
    }
}
