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

namespace Octopy\Validation;

use Octopy\Application;
use Octopy\HTTP\Request;
use Octopy\Validation\Exception\ValidationRuleException;

class Validator
{
    use ValidationRules;

    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $message = [];

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param  Request $request
     * @param  array   $rules
     * @return bool
     */
    public function validate(Request $request, array $rules)
    {
        $this->request = $request;
        foreach ($rules as $attribute => $rule) {
            foreach ($this->parse($rule) as $rule) {
                [$method, $parameter] = $rule;
                if (! method_exists($this, $method)) {
                    throw new ValidationRuleException("Call to undefined rule [$method].");
                }

                $this->$method($attribute, ...$parameter);
            }
        }

        return $this->passed();
    }

    /**
     * @return bool
     */
    public function passed() : bool
    {
        return count($this->message) === 0;
    }

    /**
     * @return array
     */
    public function message() : array
    {
        return $this->message;
    }

    /**
     * @param  string $attribute
     * @return mixed
     */
    protected function value(string $attribute)
    {
        return $this->request->$attribute ?? null;
    }

    /**
     * @param  string $format
     * @param  array  $replace
     * @return void
     */
    protected function format(string $format, array $replace = []) : void
    {
        $this->message[$replace[':attribute']] = str_replace(array_keys($replace), $replace, $format);
    }

    /**
     * @param  string $rule
     * @return array
     */
    private function parse(string $rule)
    {
        $parsed = [];
        foreach (explode('|', $rule) as $rule) {
            if (! strstr($rule, ':')) {
                $args = [];
            } else {
                [$rule, $args] = explode(':', $rule);
                $args = (array) $args;
                if (strstr($args[0], ',')) {
                    $args = explode(',', $args[0]);
                }
            }

            $parsed[] = [$rule, $args];
        }

        return $parsed;
    }
}
