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

declare(strict_types = 1);

namespace Octopy\Testing;

use Octopy\Container;
use Octopy\HTTP\Kernel;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @return void
     */
    protected function setUp() : void
    {
        if (! $this->app) {
            ($this->app = Container::make('app'))->make(Kernel::class);
        }
    }
}
