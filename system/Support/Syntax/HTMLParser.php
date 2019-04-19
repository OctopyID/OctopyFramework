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

namespace Octopy\Support\Syntax;

class HTMLParser
{
    /**
     * @var array
     */
    protected $token;

    /**
     * @var bool
     */
    protected $theme;

    /**
     * @param  string $source
     * @param  int    $marker
     * @param  int    $before
     * @param  int    $after
     * @return string
     */
    public function highlight(string $source, int $marker = 0, int $before = 0, int $after = 0)
    {
        if (! $this->theme) {
            $this->theme('default.min');
        }

        $token = $this->parse($source);

        $offset = $marker - $before - 1;
        $offset = max($offset, 0);
        $length = $after + $before + 1;
        $token = array_slice($token, $offset, $length, true);

        return '<pre class="syntax">' . $this->number($token, $marker) . '</pre>';
    }

    /**
     * @param  string $source
     * @return array
     */
    protected function parse(string $source): array
    {
        $this->token = token_get_all($source);

        $output = '';

        $class = false;
        $string = false;
        $nspace = false;

        foreach ($this->token as $o => $value) {
            if (is_array($value)) {
                $token = $value[0];
                $tname = token_name($value[0]);
                $value = htmlspecialchars($value[1]);

                if ($token === T_WHITESPACE) {
                    $output .= $value;
                } elseif ($token === T_OPEN_TAG) {
                    $output .= $this->span($tname, trim($value));
                    $output .= "\n";
                } elseif ($token === T_DOC_COMMENT) {
                    $value = preg_replace('/(\@(author|license|link|param|return|throw|var|version))(\s)/i', '<span class="C_DOCTAG">\1</span>\3', $value);

                    $value = preg_replace('/(http\:\/\/[A-Za-z0-9\.\/\-\_\~\#\?\=\&\!\%]*)/i', '<a href="\1" class="C_DOCLINK">\1</a>', $value);

                    $value = preg_replace('/\&lt\;([A-Za-z0-9].*?@.*?)\&gt\;/i', '&lt;<a href="mailto:\1" class="C_DOCLINK">\1</a>&gt;', $value);

                    $docs = [];
                    foreach (explode("\n", $value) as $doc) {
                        $docs[] = $this->span($tname, $doc);
                    }

                    $output .= implode("\n", $docs);
                } elseif ($token === T_COMMENT) {
                    $value = preg_replace('/(http\:\/\/[A-Za-z0-9\.\/\-\_\~\#\?\=\&\!\%]*)/i', '<a href="\1" class="C_COMMENT_LINK">\1</a>', $value);
                    $value = preg_replace('/\&lt\;([A-Za-z0-9].*?@.*?)\&gt\;/i', '&lt;<a href="mailto:\1" class="C_COMMENT_LINK">\1</a>&gt;', $value);

                    $output .= $this->span($tname, $value);
                } elseif ($token === T_STRING && mb_strtolower($value) === 'null') {
                    $output .= $this->span('T_STRING C_NULL', $value);
                } elseif ($token === T_STRING && mb_strtolower($value) === 'true') {
                    $output .= $this->span('T_STRING C_TRUE', $value);
                } elseif ($token === T_STRING && mb_strtolower($value) === 'false') {
                    $output .= $this->span('T_STRING C_FALSE', $value);
                } elseif ($token === T_STRING && $this->prev($o) === T_OBJECT_OPERATOR && $this->next($o) === '(') {
                    $output .= $this->span('T_STRING C_METHOD_CALL', $value);
                } elseif ($token === T_STRING && $this->prev($o) === T_CLASS) {
                    $output .= $this->span('T_STRING C_CLASSNAME', $value);
                } elseif ($token === T_STRING && $this->prev($o) === T_EXTENDS) {
                    $output .= $this->span('T_STRING C_EXTENDS_CLASS', $value);
                } elseif ($token === T_STRING && $this->prev($o) === T_IMPLEMENTS) {
                    $output .= $this->span('T_STRING C_IMPLEMENTS_CLASS', $value);
                } elseif ($token === T_STRING && $this->prev($o) === T_NEW) {
                    $output .= $this->span('T_STRING C_CLASSNAME_REF', $value);
                } elseif ($token === T_STRING && $this->next($o) === T_VARIABLE) {
                    $output .= $this->span('T_STRING C_PARAMETER_TYPEHINT', $value);
                } elseif ($token === T_STRING && $this->prev($o) === T_OBJECT_OPERATOR && $this->next($o) !== '(') {
                    $output .= $this->span('C_PROPERTY', $value);
                } elseif ($token === T_STRING && ($this->prev($o) === T_NAMESPACE || $this->prev($o) === T_USE)) {
                    $output .= '<span class="C_NAMESPACE">';
                    $output .= $this->span('T_STRING', $value);
                    $nspace = true;
                } elseif ($token === T_STRING && $this->prev($o) === T_FUNCTION || function_exists($value)) {
                    $output .= $this->span('C_FUNCTION_NAME', $value);
                } elseif ($token === T_STRING && $this->prev($o) === T_DOUBLE_COLON) {
                    if ($this->next($o, 1) === '(') {
                        $output .= $this->span('T_VARIABLE', $value);
                    } else {
                        $output .= $this->span('C_CONST', $value);
                    }
                } elseif ($token === T_STRING && $this->prev($o) !== T_FUNCTION) {
                    $output .= $this->span('T_STRING C_BUILTIN_FUNCTION', $value);
                } elseif ($token === T_CONSTANT_ENCAPSED_STRING || $token === T_ENCAPSED_AND_WHITESPACE) {
                    $output .= '<span class="' . $tname . '">' . preg_replace('`(\\\[ ^])`', '<span class="C_BACKSLASH">\1</span>', $value) . '</span>';
                } elseif ($token === T_START_HEREDOC) {
                    $output . $this->span('T_START_HEREDOC', $this->span('C_ARROWS', '&lt;&lt;&lt;') . str_replace('&lt;&lt;&lt;', '', $value));
                } else {
                    $output .= $this->span($tname, $value);
                }
            } else {
                if ($value === ';' && $nspace) {
                    $output .= '</span>';
                    $output .= $this->span('C_SEMICOLON', $value);
                    $nspace = false;
                } elseif ($value === ';') {
                    $output .= $this->span('C_SEMICOLON', $value);
                } elseif ($value === '=') {
                    $output .= $this->span('C_ASSIGNMENT', $value);
                } elseif ($value === '"') {
                    if ($string) {
                        $output .= '"</span>';
                        $string = false;
                    } else {
                        $output .= $this->span('C_VARSTRING', '"');
                        $string = true;
                    }
                } else {
                    $output .= $this->span('T_DEFAULT', $value);
                }
            }
        }

        return explode("\n", $output);
    }

    /**
     * @param  int  $position
     * @param  int  $modifier
     * @param  bool  $significant
     * @return function
     */
    protected function next(int $position, int $modifier = 1, bool $significant = true)
    {
        if (! isset($this->token[$position + $modifier])) {
            return 0;
        }

        $value = $this->token[$position + $modifier];

        if ($significant === true) {
            if ($this->token[$position + $modifier][0] === T_WHITESPACE) {
                $value = $this->next($position, $modifier + 1);
            } else {
                $value = $this->token[$position + $modifier];
            }
        }

        if (is_array($value)) {
            return $value[0];
        }

        return $value;
    }

    /**
     * @param  int $position
     * @param  int $modifier
     * @param  bool $significant
     * @return string
     */
    protected function prev(int $position, int $modifier = 1, bool $significant = true)
    {
        if (! isset($this->token[$position - $modifier])) {
            return 0;
        }

        $value = $this->token[$position - $modifier];

        if ($significant === true) {
            if ($this->token[$position - $modifier][0] === T_WHITESPACE) {
                $value = $this->prev($position, $modifier + 1);
            } else {
                $value = $this->token[$position - $modifier];
            }
        }

        if (is_array($value)) {
            return $value[0];
        }

        return $value;
    }

    /**
     * @param  string $class
     * @param  string $code
     * @return string
     */
    protected function span(string $class, string $code): string
    {
        return '<span class="' . $class . '">' . $code . '</span>';
    }

    /**
     * @param  array $lines
     * @param  int   $marker
     * @return string
     */
    protected function number(array $lines, int $marker = null): string
    {
        end($lines);
        $length = mb_strlen(key($lines) + 1);

        $snippet = '';
        foreach ($lines as $i => $line) {
            $class = 'line';
            if ($marker > 0 && $marker === $i + 1) {
                $class .= ' highlighted';
            }

            $snippet .= sprintf('<span class="%s">%s| %s</span>', $class, str_pad($i + 1, $length, ' ', STR_PAD_LEFT), $line) . "\n";
        }

        return $snippet;
    }

    /**
     * @param string $style
     */
    protected function theme(string $style): void
    {
        $this->theme = true;

        if (filter_var($style, FILTER_VALIDATE_URL)) {
            echo sprintf('<link rel="stylesheet" type="text/css" href="%s">', $style);
        } else {
            echo sprintf('<style type="text/css">%s</style>', file_get_contents(
                sprintf('%s/theme/%s.css', __DIR__, $style)
            ));
        }
    }
}
