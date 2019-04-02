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

class Stream
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $token;

    /**
     * @var string
     */
    protected $value;

    /**
     * @param array $token
     */
    public function __construct(array $token, string $value = null)
    {
        $this->value = $value;
        [$this->token, $this->type] = $token[1];
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return printf('%s(%s)', $this->type(), $this->value());
    }

    /**
     * @return string
     */
    public function type() : string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function token() : int
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function value() : string
    {
        if (strstr($this->value, '(')) {
            return substr($this->value, 1, -1);
        }

        return $this->value;
    }

    public function z()
    {
        return $this;
    }

    /**
     * @param  mixed $value
     * @return bool
     */
    public function next($value) : bool
    {
        return $value === $this->type || $value === $this->token;
    }
}
