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

declare(strict_types=1);

namespace Octopy\Testing\Config;

use Octopy\Config\DotEnv;
use Octopy\Testing\TestCase;

class DotEnvTest extends TestCase
{
    /**
     * @var Octopy\Config\DotEnv
     */
    protected $env;

    /**
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        if (! $this->env) {
            ($this->env = new DotEnv(__DIR__))->load();
        }
    }

    /**
     * @return void
     */
    public function testReturnFalseIfCannotFindFile() : void
    {
        $this->assertFalse((new DotEnv(__DIR__, 'notfound'))->load());
    }

    /**
     * @return void
     */
    public function testSpacedValueWithoutQuotesThrowException() : void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('.env values containing spaces must be surrounded by quotes.');

        (new DotEnv(__DIR__, '.env_error'))->load();
    }

    /**
     * @return void
     */
    public function testLoadVar() : void
    {
        $this->assertEquals('', getenv('NULL'));
        $this->assertEquals('bar', getenv('FOO'));
        $this->assertEquals('baz', getenv('BAR'));
        $this->assertEquals('with space', getenv('SPACED'));
    }

    /**
     * @return void
     */
    public function testCommentedLoadVar() : void
    {
        $this->assertFalse(getenv('CBAR'));
        $this->assertFalse(getenv('CZOO'));

        $this->assertEquals('', getenv('CNULL'));
        $this->assertEquals('bar', getenv('CFOO'));
        $this->assertEquals('with spaces', getenv('CSPACED'));
        $this->assertEquals('a value with a # character', getenv('CQUOTES'));
        $this->assertEquals('a value with a # character & a quote " character inside quotes', getenv('CQUOTESWITHQUOTE'));
    }

    /**
     * @return void
     */
    public function testLoadServerGlobal() : void
    {
        $this->assertEquals('', $_SERVER['NULL']);
        $this->assertEquals('bar', $_SERVER['FOO']);
        $this->assertEquals('baz', $_SERVER['BAR']);
        $this->assertEquals('with space', $_SERVER['SPACED']);
    }

    /**
     * @return void
     */
    public function testNestedEnvironmentVar() : void
    {
        $this->assertEquals('Hello World!', $_ENV['NVAR4']);
        $this->assertEquals('$NVAR1 {NVAR2}', $_ENV['NVAR5']);
        $this->assertEquals('{$NVAR1} {$NVAR2}', $_ENV['NVAR3']);
    }

    /**
     * @return void
     */
    public function testDotenvAllowSpecialCharacter() : void
    {
        $this->assertEquals('22222:22#2^{', getenv('SPVAR4'));
        $this->assertEquals('$a6^C7k%zs+e^.jvjXk', getenv('SPVAR1'));
        $this->assertEquals('?BUty3koaV3%GA*hMAwH}B', getenv('SPVAR2'));
        $this->assertEquals('jdgEB4{QgEC]HL))&GcXxokB+wqoN+j>xkV7K?m$r', getenv('SPVAR3'));
        $this->assertEquals('test some escaped characters like a quote " or maybe a backslash \\', getenv('SPVAR5'));
    }
}
