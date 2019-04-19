<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : www.octopy.xyz
 * @license : MIT
 */

namespace Octopy;

use Octopy\Container;
use Octopy\Support\Macroable;

class Application extends Container
{
    use Macroable;

    /**
     * @var string
     */
    protected $basepath;

    /**
     * @var array
     */
    protected $boot = [];

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * @var array
     */
    protected $provider = [];

    /**
     * @param string $basepath
     */
    public function __construct(string $basepath)
    {
        $this->basepath = $basepath;

        if ($this->instance(static::class, $this)) {
            
            // Set instance aliases
            $aliases = include 'Config/Container.php';
            foreach ($aliases as $abstract => $concrete) {
                $this->alias($abstract, $concrete);
            }
        }
    }

    /**
     * @return string
     */
    public function name() : string
    {
        return $this['config']['app.name'];
    }

    /**
     * @return string
     */
    public function version() : string
    {
        return 'v0.2-dev';
    }

    /**
     * @return string
     */
    public function locale() : string
    {
        return $this['config']['app.locale'];
    }

    /**
     * @param  string $subpath
     * @return string
     */
    public function basepath(string $subpath = null) : string
    {
        return preg_replace('/\/+/', '/', $this->basepath . $subpath . DIRECTORY_SEPARATOR);
    }

    /**
     * @return bool
     */
    public function debug() : bool
    {
        return $this['config']['app.debug'];
    }

    /**
     * @return mixed
     */
    public function env(string $env = null)
    {
        if (!is_null($env)) {
            return $this['config']['app.env'] === $env;
        }

        return $this['config']['app.env'];
    }

    /**
     * @return bool
     */
    public function console() : bool
    {
        if (isset($_ENV['APP_RUNNING_IN_CONSOLE'])) {
            return $_ENV['APP_RUNNING_IN_CONSOLE'] === 'true';
        }

        return php_sapi_name() === 'cli' || php_sapi_name() === 'phpdbg';
    }

    /**
     * @param mixed $provider
     * @param bool  $force
     */
    public function register($provider, bool $force = false)
    {
        $name = is_string($provider) ? $provider : get_class($provider);
    
        if (isset($this->provider[$name]) && !$force) {
            return $this->provider[$name];
        }

        if (is_string($provider)) {
            $provider = new $provider($this);
        }

        $this->provider[$name] = $provider;

        if (method_exists($this->provider[$name], 'register')) {
            $provider->register();
        }

        if ($this->booted && method_exists($provider, 'boot')) {
            return $provider->boot();
        }
    }

    /**
     * @param callable $boot
     */
    public function boot(callable $boot)
    {
        $this->boot[] = $boot;
    }

    /**
     * @return void
     */
    public function booting()
    {
        if ($this->booted) {
            return true;
        }

        foreach ($this->provider as $provider) {
            if (method_exists($provider, 'boot')) {
                $provider->boot();
            }
        }

        foreach ($this->boot as $boot) {
            call_user_func($boot, $this);
        }

        $this->booted = true;
    }
}
