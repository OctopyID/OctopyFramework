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

namespace Octopy\Debug;

use ReflectionObject;

class VarDumper
{
    /**
     * @var bool
     */
    protected $initialized;

    /**
     * @var bool
     */
    protected $console;

    /**
     * @var int
     */
    protected $indent = 0;

    /**
     * @var int
     */
    protected $nesting = 20;

    /**
     * @var int
     */
    protected $padding = 3;

    /**
     * @var int
     */
    protected $posix = false;

    /**
     * @var bool
     */
    protected $force = false;

    /**
     * @var array
     */
    protected $colors = [
        'boolean'    => ['#FFC447', 'purple'     ],
        'double'     => ['#abb2bf', 'cyan'       ],
        'integer'    => ['#FFC447', 'light_green'],
        'null'       => ['#FFC447', 'white'      ],
        'recursion'  => ['#e06c75', 'red'        ],
        'size'       => ['#21B089', 'green'      ],
        'string'     => ['#589DF6', 'blue'       ],
        'type'       => ['#BBBBBB', 'light_gray' ],

        'array'      => ['#abb2bf', 'white'      ],
        'arrow'      => ['#e06c75', 'red'        ],
        'name'       => ['#e6c07b', 'yellow'     ],
        'object'     => ['#abb2bf', 'white'      ],
        'visibility' => ['#FA8C8F', 'light_red'  ],
   ];

    /**
     * @var array
     */
    protected $foreground = [
        'none'          => null,
        'black'         => 30,
        'red'           => 31,
        'green'         => 32,
        'yellow'        => 33,
        'blue'          => 34,
        'purple'        => 35,
        'cyan'          => 36,
        'light_gray'    => 37,
        'dark_gray'     => 90,
        'light_red'     => 91,
        'light_green'   => 92,
        'light_yellow'  => 93,
        'light_blue'    => 94,
        'light_magenta' => 95,
        'light_cyan'    => 96,
        'white'         => 97,
   ];

    /**
     * @var array
     */
    protected $background = [
        'none'          => null,
        'black'         => 40,
        'red'           => 41,
        'green'         => 42,
        'yellow'        => 43,
        'blue'          => 44,
        'purple'        => 45,
        'cyan'          => 46,
        'light_gray'    => 47,
        'dark_gray'     => 100,
        'light_red'     => 101,
        'light_green'   => 102,
        'light_yellow'  => 103,
        'light_blue'    => 104,
        'light_magenta' => 105,
        'light_cyan'    => 106,
        'white'         => 107,
   ];

    /**
     * @var array
     */
    protected $styles = [
        'none'      => null,
        'bold'      => 1,
        'faint'     => 2,
        'italic'    => 3,
        'underline' => 4,
        'blink'     => 5,
        'negative'  => 7,
   ];

    /**
     *
     */
    public function __construct()
    {
        if (substr(PHP_SAPI, 0, 3) == 'cli') {
            $this->console = true;
            $this->posix = $this->posix();
        }
    }

    /**
     * @return void
     */
    public function dump() : void
    {
        foreach (func_get_args() as $arg) {
            $this->output($this->evaluate([$arg]));
        }
    }

    /**
     * @return void
     */
    public function dd() : void
    {
        $this->dump(...func_get_args());
        die();
    }

    /**
     * @return bool
     */
    protected function above() : bool
    {
        return count(debug_backtrace()) > $this->nesting;
    }

    /**
     * @return bool
     */
    protected function windows() : bool
    {
        return (defined('PHP_OS') && (substr_compare(PHP_OS, 'win', 0, 3, true) === 0)) || (getenv('OS') != false && substr_compare(getenv('OS'), 'windows', 0, 7, true));
    }

    /**
     * @return bool
     */
    protected function posix() : bool
    {
        if ($this->force) {
            return true;
        }
        
        if ($this->windows()) {
            return false;
        }

        if (function_exists('posix_isatty')) {
            set_error_handler(function () {
            });
       
            $posix = posix_isatty(STDIN);
            restore_error_handler();
       
            return $posix;
        }

        return false;
    }

    /**
     * @param  string  $string
     * @param  string  $format
     * @return string
     */
    protected function console(string $string, string $format = null) : string
    {
        if (!$format || !$this->posix) {
            return $string;
        }

        $format = $format ? explode('|', $format) : [];

        return sprintf("\033[%sm%s\033[0m", implode(';', array_filter([$this->background[$format[1] ?? null] ?? null, $this->styles[$format[2] ?? null] ?? null, $this->foreground[$format[0] ?? null] ?? null,])), $string);
    }

    /**
     * @param string  $data
     */
    protected function output(string $data) : void
    {
        if (!$this->console) {
            $data = preg_replace('/&lt;address\>|&lt;\/address><br \/>\n/', '', $data);
            echo '<pre style="background:#0c1021;font:85% Monaco, Consolas, monospace;padding:10px;line-height:1.3;">' . $data . '</pre>';
           
            if (!$this->initialized) {
                $this->initialized = true;
                echo '<script>function __vardump(c,b){var a=document.getElementById(c);a.classList.toggle("active")?(b.innerHTML=" \u25b6 ",a.style.display="none") :(b.innerHTML=" \u25bc ",a.style.display="inline")};</script>';
            }
        } else {
            echo $this->console($data);
        }
    }

    /**
     * @param  string  $value
     * @param  string  $name
     * @return string
     */
    protected function color($value, string $name) : ?string
    {
        if (!$this->console) {
            if ($name == 'type') {
                return '<small style="color:' . $this->colors[$name][0] . '">' . $value . '</small>';
            }

            return '<span style="color:' . $this->colors[$name][0] . '">' . $value . '</span>';
        }

        return $this->console($value, $this->colors[$name][1]);
    }

    /**
     * @param  int  $size
     * @param  int  $type
     * @return string
     */
    protected function counter(int $size, int $type = 0) : string
    {
        if (!$this->console) {
            return $this->color('<small>(' . ($type ? 'length' : 'size')  . ":{$size})</small>", 'size');
        }

        return $this->color('(' . ($type ? 'length' : 'size')  . ":{$size})", 'size');
    }

    /**
     * @param  string  $type
     * @param  string  $before
     * @return string
     */
    protected function type(string $type, string $before = ' ') : string
    {
        return "{$before}{$this->color($type, 'type')}";
    }

    /**
     * @return string
     */
    protected function break() : string
    {
        return $this->console ? "\n" : '<br>';
    }

    /**
     * @param  iteger  $size
     * @return string
     */
    protected function indent(int $size) : string
    {
        return str_repeat(!$this->console ? '&nbsp;' : ' ', $size);
    }

    /**
     * @param  integer  $size
     * @return string
     */
    protected function pad(int $size) : string
    {
        return str_repeat(!$this->console ? '&nbsp;' : ' ', $size < 0 ? 0 : $size);
    }

    /**
     * @param  string  $key
     * @param  bool    $parent
     * @return string
     */
    protected function parent(string $key, bool $parent = false) : string
    {
        return $this->color("'$key'", 'name') . " {$this->color('=>', 'arrow')} ";
    }

    /**
     * @param  array  $array
     * @param  bool   $object
     * @return string
     */
    protected function array(array $array, bool $object) : string
    {
        $temporary = '';
        $this->indent += $this->padding;
        foreach ($array as $key => $arr) {
            if (is_array($arr)) {
                $temporary .= $this->break() . $this->indent($this->indent) . $this->parent((string) $key)  . 'Array ' . $this->counter(count($arr));
                
                $result = $this->array($arr, $object);

                if ($object == false && $result != '') {
                    $result .= $this->indent($this->indent);
                }

                if (!$this->console) {
                    $id = time() . rand(0, 9999);
                    
                    $temporary .= " [<span onclick=\"__vardump('debug_" . $id . "', this)\"> ▼ </span><span id=\"debug_" . $id ."\">{$result}</span>]";
                } else {
                    $temporary .= " [{$result}]";
                }
            } else {
                $temporary .= $this->break() . $this->indent($this->indent) . $this->parent((string) $key, true)
                    . $this->evaluate([$arr], true);
            }
        }

        $this->indent -= $this->padding;

        if ($temporary != '') {
            $temporary .= $this->break();
            if ($object) {
                $temporary .= $this->indent($this->indent);
            }
        }

        return $temporary;
    }

    /**
     * @param  object  $object
     * @return string
     */
    protected function refcount($object) : string
    {
        ob_start();
        debug_zval_dump($object);
        if (preg_match('/object\(.*?\)#(\d+)\s+\(/', ob_get_clean(), $match)) {
            return $match[1];
        }
    }

    /**
     * @param  object  $object
     * @return mixed
     */
    protected function object($object)
    {
        if ($this->above()) {
            return $this->color('...', 'recursion');
        }

        $temporary = '';
        $reflection = new ReflectionObject($object);
        $this->indent += $this->padding;
       
        foreach ($reflection->getProperties() as $size => $prop) {
            if ($prop->isPrivate()) {
                $temporary .= "{$this->break()}{$this->indent($this->indent)}{$this->color('protected', 'visibility')}{$this->pad(2)} {$this->color(':', 'arrow')} ";
            } elseif ($prop->isProtected()) {
                $temporary .= "{$this->break()}{$this->indent($this->indent)}{$this->color('protected', 'visibility')} {$this->color(':', 'arrow')} ";
            } elseif ($prop->isPublic()) {
                $temporary .= "{$this->break()}{$this->indent($this->indent)}{$this->color('public', 'visibility')}{$this->pad(3)} {$this->color(':', 'arrow')} ";
            }

            $prop->setAccessible(true);
            $temporary .= $this->color("'{$prop->getName()}'", 'name') . " {$this->color('=>', 'arrow')} {$this->evaluate([$prop->getValue($object)], true, true)}";
        }

        if ($temporary != '') {
            $temporary .= $this->break();
        }

        $this->indent -= $this->padding;

        $temporary .= $temporary != '' ? $this->indent($this->indent) : '';

        if (!$this->console) {
            $id = time() . rand(0, 9999);
            $format = $this->color('Object (:name) [:id] {<span onclick="__vardump(\'debug_' . $id . '\', this)"> ▼ </span><span id="debug_' . $id .'">:content</span>}', 'object');
        } else {
            $format = $this->color('Object (:name) [:id] {:content}', 'object');
        }

        $temporary = str_replace([':name', ':id', ':content'], [
            $reflection->getName(),
            $this->color("#{$this->refcount($object)}", 'size'),
            $temporary
       ], $format);

        return $temporary;
    }

    /**
     * @param  array  $args
     * @param  bool   $called
     * @param  bool   $object
     * @return string
     */
    protected function evaluate(array $args, bool $called = false, bool $object = false) : string
    {
        $temporary = null;
        foreach ($args as $each) {
            $type = gettype($each);
            switch ($type) {
                case 'string':
                    if (!$this->console) {
                        $each = nl2br(str_replace(['<', ' '], ['&lt;', '&nbsp;'], $each));
                    }

                    $temporary .=  $this->color("'{$each}'", $type) . " {$this->counter(strlen($each), 1)}{$this->type($type)}";
                    break;
                case 'integer':
                    $temporary .=  "{$this->color((string) $each, $type)}{$this->type($type)}";
                    break;
                case 'double':
                    $temporary .= "{$this->color((string) $each, $type)}{$this->type($type)}";
                    break;
                case 'NULL':
                    $temporary .= "{$this->color('null', 'null')}{$this->type($type)}";
                    break;
                case 'boolean':
                    $temporary .= "{$this->color($each ? 'true' : 'false', $type)}{$this->type($type)}";
                    break;
                case 'array':
                    if (!$this->console) {
                        $id = time() . rand(0, 9999);
                        $format = $this->color('Array :size [<span onclick="__vardump(\'debug_' . $id . '\', this)"> ▼ </span><span id="debug_' . $id .'">:content</span>]', 'array');
                    } else {
                        $format = $this->color('Array :size [:content]', 'array');
                    }

                    $temporary .= str_replace([':size', ':content'], [
                        $this->counter(count($each)),
                        $this->array($each, $object)
                   ], $format);
                    break;
                case 'object':
                    $temporary .= $this->object($each);
                    break;
            }

            if (!$called) {
                $temporary .= $this->break();
            }
        }

        return $temporary;
    }
}
