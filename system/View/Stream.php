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

class Stream
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var int
     */
    protected $token;

    /**
     * @var string
     */
    protected $expression;

    /**
     * @param array $token
     */
    public function __construct(array $token, string $expression = null)
    {
        $this->expression = $expression;
        [$this->token, $this->code] = $token[1];
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return printf('%s(%s)', $this->code(), $this->expression());
    }

    /**
     * @return string
     */
    public function code() : string
    {
        return $this->code;
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
    public function expression() : string
    {
        if (strstr($this->expression, '(')) {
            return substr($this->expression, 1, -1);
        }

        return $this->expression;
    }

    /**
     * @param  mixed $expression
     * @return bool
     */
    public function next($expression) : bool
    {
        return $expression === $this->code || $expression === $this->token;
    }
}
