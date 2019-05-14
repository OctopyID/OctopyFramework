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

namespace Octopy\View;

use Closure;
use Exception;
use RuntimeException;

class Engine
{
    /**
     * @var Octopy\View\Finder
     */
    protected $finder;

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

        if (! array_key_exists($name, $this->directive)) {
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
        $storage = $this->finder->find($name = $this->trim($name));

        $this->parameter($parameter);

        if ($storage->octopy() && $storage->expired()) {
            $this->parser->compile($storage);
        }

        try {
            $content = $this->evaluate($storage);
        } catch (Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        return ltrim($content, "\n");
    }

    /**
     * @param  array $parameter
     * @return void
     */
    protected function parameter(array $parameter) : void
    {
        $this->parameter = array_merge($this->parameter, array_replace($this->shared, $parameter));
    }

    /**
     * @param  mixed $value
     * @return mixed
     */
    protected function escape($value)
    {
        if (is_string($value)) {
            return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
        }

        return $value;
    }

    /**
     * @param  string $name
     * @param  string $default
     * @return string
     */
    protected function yield(string $name, string $default = null)
    {
        return ltrim($this->section[$this->trim($name)] ?? $default, "\n");
    }

    /**
     * @param  string $name
     * @param  string $value
     * @return void
     */
    protected function section(string $name, string $content = null)
    {
        $name = $this->trim($name);
        if (! array_key_exists($name, $this->section)) {
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
     * @param  array $octopy
     * @return mixed
     */
    protected function evaluate(...$octopy)
    {
        extract($this->parameter);

        try {
            if ($octopy[0]->octopy() && $octopy[0]->compiled()) {
                ob_start();
                require $octopy[0];

                return ob_get_clean();
            } else {
                ob_start();
                eval('; ?>' . $octopy[0] . '<?php ;');

                return ob_get_clean();
            }
        } catch (Exception $exception) {
            throw $exception;
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
