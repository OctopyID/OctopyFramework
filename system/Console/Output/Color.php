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

namespace Octopy\Console\Output;

use InvalidArgumentException;
use Exception as InvalidStyleException;

class Color
{
    public const RESET_STYLE     = 0;

    public const FOREGROUND      = 38;

    public const BACKGROUND      = 48;

    public const COLOR256_REGEXP = '~^(b:)?color_([0-9]{1,3})$~';

    /**
     * @var bool
     */
    protected $supported;

    /**
     * @var bool
     */
    protected $force = false;

    /**
     * @var array
     */
    protected $style = [
        's:none'          => null,
        's:bold'          => 1,
        's:dark'          => 2,
        's:italic'        => 3,
        's:underline'     => 4,
        's:blink'         => 5,
        's:reverse'       => 7,
        's:concealed'     => 8,

        'c:default'       => 39,
        'c:black'         => 30,
        'c:red'           => 31,
        'c:green'         => 32,
        'c:yellow'        => 33,
        'c:blue'          => 34,
        'c:magenta'       => 35,
        'c:cyan'          => 36,
        'c:lightgray'    => 37,

        'c:darkgray'     => 90,
        'c:lightred'     => 91,
        'c:lightgreen'   => 92,
        'c:lightyellow'  => 93,
        'c:lightblue'    => 94,
        'c:lightmagenta' => 95,
        'c:lightcyan'    => 96,
        'c:white'         => 97,

        'b:default'       => 49,
        'b:black'         => 40,
        'b:red'           => 41,
        'b:green'         => 42,
        'b:yellow'        => 43,
        'b:blue'          => 44,
        'b:magenta'       => 45,
        'b:cyan'          => 46,
        'b:lightgray'    => 47,

        'b:darkgray'     => 100,
        'b:lightred'     => 101,
        'b:lightgreen'   => 102,
        'b:lightyellow'  => 103,
        'b:lightblue'    => 104,
        'b:lightmagenta' => 105,
        'b:lightcyan'    => 106,
        'b:white'         => 107,
    ];

    /**
     *
     */
    public function __construct()
    {
        $this->supported = $this->supported();
    }

    /**
     * @param  string $color
     * @param  array  $args
     * @return string
     */
    public function __call(string $color, array $args = []) : string
    {
        return $this->format('<c:' . $color . '>' . ($args[0] ?? ''));
    }

    /**
     * @param  string $text
     * @return string
     */
    public function format(string $text)
    {
        preg_match_all('^\<' . implode('|', array_keys($this->style)) . '\>^', $text, $match);
        $match[1] = [];
        foreach ($match[0] as $i  => $key) {
            $match[0][$i] = '<' . $key . '>';
            $match[1][$i] = sprintf("\033[%um", $this->style[$key]);
        }

        return str_replace($match[0], $match[1], $text) . "\033[0m\n";
    }

    /**
     * @param  string $style
     * @param  string $text
     * @return string
     */
    public function apply($style, $text)
    {
        if (! $this->forced() && ! $this->supported()) {
            return $text;
        }

        if (is_string($style)) {
            $style = [$style];
        }

        if (! is_array($style)) {
            throw new InvalidArgumentException('Style must be string or array');
        }

        $sequence = [];

        foreach ($style as $key) {
            if (isset($this->theme[$key])) {
                $sequence = array_merge($sequence, $this->sequence($key));
            } elseif ($this->validate($key)) {
                $sequence[] = $this->style($key);
            } else {
                throw new InvalidStyleException($key);
            }
        }

        $sequence = array_filter($sequence, static function ($value) {
            return $value !== null;
        });

        if (empty($sequence)) {
            return $text;
        }

        return $this->concat(implode(';', $sequence)) . $text . $this->concat(Color::RESET_STYLE);
    }

    /**
     * @param bool $force
     */
    public function force($force)
    {
        $this->force = (bool) $force;
    }

    /**
     * @return bool
     */
    public function forced()
    {
        return $this->force;
    }

    /**
    * @param array $theme
    */
    public function set(array $theme)
    {
        $this->theme = [];
        foreach ($theme as $name => $styles) {
            $this->theme($name, $styles);
        }
    }

    /**
     * @param string $name
     * @param mixed  $styles
     */
    public function theme($name, $styles)
    {
        if (is_string($styles)) {
            $styles = [$styles];
        }
        if (! is_array($styles)) {
            throw new InvalidArgumentException('Style must be string or array.');
        }

        foreach ($styles as $style) {
            if (! $this->validate($style)) {
                throw new InvalidStyleException($style);
            }
        }

        $this->theme[$name] = $styles;
    }

    /**
     * @param  string $name
     * @return bool
     */
    public function has($name) : bool
    {
        return isset($this->theme[$name]);
    }

    /**
     * @param string $name
     */
    public function remove($name)
    {
        unset($this->theme[$name]);
    }

    /**
     * @return bool
     */
    public function supported()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            if (function_exists('sapi_windows_vt100_support') && sapi_windows_vt100_support(STDOUT)) {
                return true;
            } elseif (getenv('ANSICON') !== false || getenv('ConEmuANSI') === 'ON') {
                return true;
            }
            return false;
        } else {
            return function_exists('posix_isatty') && posix_isatty(STDOUT);
        }
    }

    /**
     * @return bool
     */
    public function are256supported()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return function_exists('sapi_windows_vt100_support') && sapi_windows_vt100_support(STDOUT);
        } else {
            return mb_strpos(getenv('TERM'), '256color') !== false;
        }
    }

    /**
     * @param  string $name
     * @return array
     */
    protected function sequence($name) : array
    {
        $sequences = [];
        foreach ($this->theme[$name] as $style) {
            $sequences[] = $this->style($style);
        }

        return $sequences;
    }

    /**
     * @param  string $style
     * @return string
     */
    protected function style($style) : string
    {
        if (array_key_exists($style, $this->style)) {
            return $this->style[$style];
        }

        if (! $this->are256supported()) {
            return null;
        }

        preg_match(Color::COLOR256_REGEXP, $style, $matches);

        $type  = $matches[1] === 'b:' ? Color::BACKGROUND : Color::FOREGROUND;
        $value = $matches[2];

        return "$type;5;$value";
    }

    /**
     * @param  string $style
     * @return bool
     */
    protected function validate(string $style) : bool
    {
        return array_key_exists($style, $this->style) || preg_match(Color::COLOR256_REGEXP, $style);
    }

    /**
     * @param  string $value
     * @return string
     */
    protected function concat(string $value) : string
    {
        return "\033[{$value}m";
    }
}
