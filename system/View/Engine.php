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

namespace Octopy\View;

use Closure;
use RuntimeException;

use Octopy\View\Finder;
use Octopy\View\Parser;
use Octopy\Support\Collector;
use Octopy\Support\Benchmark;

class Engine
{
    /**
     * @var Octopy\View\Finder
     */
    public $finder;

    /**
     * @var Octopy\View\Parser
     */
    protected $parser;

    /**
     * @var array
     */
    protected $shared = [];

    /**
     * @var array
     */
    protected $storage = [];

    /**
     * @var array
     */
    protected $section = [];

    /**
     * @var array
     */
    protected $parameter = [];

    /**
     * @var array
     */
    protected $directive = [];

    /**
     * @param string $resource
     * @param string $compiled
     */
    public function __construct(string $resource, ?string $compiled = null)
    {
        $this->parser = new Parser($this);
        $this->finder = new Finder($resource, $compiled);
        $this->benchmark = new Benchmark;
    }
    
    /**
     * @param string $varname
     * @param mixed  $value
     */
    public function share(string $varname, $value = null)
    {
        $this->shared[$varname] = $value;
    }

    /**
     * @param string  $name
     * @param Closure $handler
     */
    public function directive(string $name, Closure $handler = null)
    {
        if (is_null($handler)) {
            return $this->directive[$name] ?? null;
        }

        if (!array_key_exists($name, $this->directive)) {
            $this->directive[$name] = $handler;
        }
    }

    /**
     * @param  string $name
     * @param  array  $parameter
     * @return string
     */
    public function render(string $name, array $parameter = [])
    {
        // benchmark purpose
        $this->benchmark->mark($name);

        $storage = $this->finder->find($name = $this->trim($name));

        $this->parameter = array_merge($this->parameter, array_replace($this->shared, $parameter));

        if ($storage->octopy() && $storage->expired()) {
            $this->parser->compile($storage);
        }
        
        if (false === $content = $this->evaluate($storage)) {
            throw new RuntimeException("The template [$name] cannot be rendered.");
        }

        $storage->benchmark($this->benchmark->elapsed($name), $this->benchmark->memory());

        return $content;
    }

    /**
     * @param  mixed $value
     * @return mixed
     */
    protected function escape($value)
    {
        if (is_numeric($value)) {
            return $value;
        }

        return is_string($value) ? htmlspecialchars($value, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8', false) : $value;
    }

    /**
      * @param  string $name
      * @param  string $default
      * @return string
      */
    protected function yield(string $name, string $default = null)
    {
        return ltrim($this->section[$this->trim($name)] ?? $default, PHP_EOL);
    }

    /**
     * @param  string $name
     * @param  string $value
     * @return void
     */
    protected function section(string $name, string $content = null)
    {
        $name = $this->trim($name);
        if (!array_key_exists($name, $this->section)) {
            if (ob_start()) {
                $this->section[$name] = $content;
            }
        }
    }

    /**
     * @return void
     */
    protected function endsection()
    {
        $name = array_keys($this->section);
        if (array_key_exists($name = end($name), $this->section)) {
            if ($this->section[$name] === null) {
                $this->section[$name] = ob_get_clean();
            }
        }
    }

    /**
     * @param  array $octopy__
     * @return mixed
     */
    protected function evaluate(...$octopy__)
    {
        extract($this->parameter);

        if ($octopy__[0]->octopy() && $octopy__[0]->compiled()) {
            ob_start();
            require $octopy__[0];
            return ob_get_clean();
        } else {
            ob_start();
            eval('; ?>' . $octopy__[0] . '<?php ;');
            return ob_get_clean();
        }

        return false;
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function trim(string $name) : string
    {
        return trim(preg_replace('/(\.|\/)+/', '.', $name), '.');
    }
}
