<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

namespace Octopy\Debug\Toolbar\DataCollector;

use Octopy\Application;

class Collector
{
    /**
     * @var boolean
     */
    public $show = true;

    /**
     * @var boolean
     */
    public $badge = true;

    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @param  Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name();
    }
}
