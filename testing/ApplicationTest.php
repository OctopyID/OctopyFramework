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

use Octopy\Application;

class ApplicationTest extends TestCase
{
    /**
     * @return void
     */
    public function testAppCreated() : void
    {
        $this->assertInstanceOf(Application::class, $this->app);
    }
}
