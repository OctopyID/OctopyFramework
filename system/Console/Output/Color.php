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
    /**
     * @var int
     */
    const FOREGROUND = 38;

    /**
     * @var int
     */
    const BACKGROUND = 48;

    /**
     * @var string
     */
    const COLOR256_REGEXP = '~^(bg_)?color_([0-9]{1,3})$~';

    /**
     * @var int
     */
    const RESET_STYLE = 0;

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
        'none'             => null,
        'bold'             => 1,
        'dark'             => 2,
        'italic'           => 3,
        'underline'        => 4,
        'blink'            => 5,
        'reverse'          => 7,
        'concealed'        => 8,
        
        'default'          => 39,
        'black'            => 30,
        'red'              => 31,
        'green'            => 32,
        'yellow'           => 33,
        'blue'             => 34,
        'magenta'          => 35,
        'cyan'             => 36,
        'light_gray'       => 37,
        
        'dark_gray'        => 90,
        'light_red'        => 91,
        'light_green'      => 92,
        'light_yellow'     => 93,
        'light_blue'       => 94,
        'light_magenta'    => 95,
        'light_cyan'       => 96,
        'white'            => 97,
        
        'bg_default'       => 49,
        'bg_black'         => 40,
        'bg_red'           => 41,
        'bg_green'         => 42,
        'bg_yellow'        => 43,
        'bg_blue'          => 44,
        'bg_magenta'       => 45,
        'bg_cyan'          => 46,
        'bg_light_gray'    => 47,
        
        'bg_dark_gray'     => 100,
        'bg_light_red'     => 101,
        'bg_light_green'   => 102,
        'bg_light_yellow'  => 103,
        'bg_light_blue'    => 104,
        'bg_light_magenta' => 105,
        'bg_light_cyan'    => 106,
        'bg_white'         => 107,
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
        return $this->format('{' . $color . '}' . ($args[0] ?? ''));
    }

    /**
     * @param  string $text
     * @return string
     */
    public function format(string $text)
    {
        preg_match_all('^\{' . implode('|', array_keys($this->style)) . '\}^', $text, $match);
        $match[1] = [];
        foreach ($match[0] as $i  => $key) {
            $match[0][$i] = '{' . $key . '}';
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
        if (!$this->forced() && !$this->supported()) {
            return $text;
        }

        if (is_string($style)) {
            $style = [$style];
        }
        
        if (!is_array($style)) {
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

        $sequence = array_filter($sequence, function ($value) {
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
        $this->force = (bool)$force;
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
        if (!is_array($styles)) {
            throw new InvalidArgumentException('Style must be string or array.');
        }

        foreach ($styles as $style) {
            if (!$this->validate($style)) {
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
            if (function_exists('sapi_windows_vt100_support') && @sapi_windows_vt100_support(STDOUT)) {
                return true;
            } elseif (getenv('ANSICON') !== false || getenv('ConEmuANSI') === 'ON') {
                return true;
            }
            return false;
        } else {
            return function_exists('posix_isatty') && @posix_isatty(STDOUT);
        }
    }

    /**
     * @return bool
     */
    public function are256supported()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return function_exists('sapi_windows_vt100_support') && @sapi_windows_vt100_support(STDOUT);
        } else {
            return strpos(getenv('TERM'), '256color') !== false;
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

        if (!$this->are256supported()) {
            return null;
        }

        preg_match(Color::COLOR256_REGEXP, $style, $matches);

        $type  = $matches[1] === 'bg_' ? Color::BACKGROUND : Color::FOREGROUND;
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
