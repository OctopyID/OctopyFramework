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

declare(strict_types=1);

namespace Octopy\Testing\Autoload;

use Octopy\Testing\TestCase;

class AutoloaderTest extends TestCase
{
    /**
     * @var Octopy\Autoload
     */
    protected $loader;

    /**
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->loader = $this->app->make('autoload');
    }

    /**
     * @return void
     */
    public function testServiceAutoLoaderFromShareInstance() : void
    {
        $actual = $this->loader->load('App\HTTP\Controller');
        $expect = $this->app->basepath('app/HTTP/Controller/Controller.php');

        $this->assertSame($expect, $actual);
    }

    /**
     * @return void
     */
    public function testAddNamespaceStringToArray() : void
    {
        $this->loader->namespace('Testing', 'testing');

        $this->assertSame(__FILE__, $this->loader->load('Testing\Autoload\AutoloaderTest'));
    }

    /**
     * @return void
     */
    public function testRemoveNamespace() : void
    {
        $this->testAddNamespaceStringToArray();

        $this->loader->remove('Testing');

        $this->assertFalse((bool) $this->loader->load('Testing\Autoload\AutoloaderTest'));
    }

    /**
     * @return void
     */
    public function testMatchesWithPreceedingSlash()
    {
        $actual = $this->loader->load('\App\HTTP\Controller');

        $expect = $this->app->basepath('app/HTTP/Controller/Controller.php');

        $this->assertSame($expect, $actual);
    }

    /**
     * @return void
     */
    public function testMissingFile() : void
    {
        $this->assertFalse(
            $this->loader->load('\App\Missing\Classname')
        );
    }
}
