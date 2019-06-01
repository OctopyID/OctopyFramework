<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author   : Supian M <supianidz@gmail.com>
 * @link     : www.octopy.xyz
 * @license  : MIT
 */

namespace Octopy\Support\Syntax;

use Octopy\Console\Output\Color;

class CLIParser
{
    public const TOKEN_HTML = 'token_html';

    public const TOKEN_STRING = 'token_string';

    public const TOKEN_DEFAULT = 'token_default';

    public const TOKEN_COMMENT = 'token_comment';

    public const TOKEN_KEYWORD = 'token_keyword';

    public const LINE_NUMBER = 'line_number';

    public const ACTUAL_LINE_MARK = 'actual_line_mark';

    /**
     * @var Octopy\Console\Color
     */
    protected $color;

    /**
     * @var array
     */
    protected $default = [
        CLIParser::TOKEN_HTML       => 'c:cyan',
        CLIParser::TOKEN_STRING     => 'c:white',
        CLIParser::TOKEN_COMMENT    => 'c:yellow',
        CLIParser::TOKEN_DEFAULT    => 'c:default',
        CLIParser::TOKEN_KEYWORD    => 'c:red',
        CLIParser::LINE_NUMBER      => 'c:lightgray',
        CLIParser::ACTUAL_LINE_MARK => 'c:red',
    ];

    /**
     * @param Color $color
     */
    public function __construct(Color $color)
    {
        $this->color = $color;

        foreach ($this->default as $name => $styles) {
            if (! $this->color->has($name)) {
                $this->color->theme($name, $styles);
            }
        }
    }

    /**
     * @param  string $source
     * @param  int    $marker
     * @param  int    $before
     * @param  int    $after
     * @return string
     */
    public function highlight(string $source, ?int $marker = null, int $before = 0, int $after = 0)
    {
        $token = $this->parse($source);

        $offset = $marker - $before - 1;
        $offset = max($offset, 0);
        $length = $after + $before + 1;
        $token = array_slice($token, $offset, $length, true);

        $lines = $this->color($token);

        return $this->number($lines, $marker);
    }

    /**
     * @param string $source
     * @return array
     */
    protected function parse($source)
    {
        return $this->split(
            $this->tokenize(
                str_replace(["\r\n", "\r"], "\n", $source)
            )
        );
    }

    /**
     * @param  string $source
     * @return array
     */
    protected function tokenize($source) : array
    {
        $tokens = token_get_all($source);

        $buffer  = '';
        $output  = [];
        $current = null;

        foreach ($tokens as $token) {
            if (is_array($token)) {
                switch ($token[0]) {
                    case T_WHITESPACE:
                        break;

                    case T_OPEN_TAG:
                    case T_OPEN_TAG_WITH_ECHO:
                    case T_CLOSE_TAG:
                    case T_VARIABLE:

                    // Constants
                    case T_DIR:
                    case T_FILE:
                    case T_METHOD_C:
                    case T_DNUMBER:
                    case T_LNUMBER:
                    case T_NS_C:
                    case T_LINE:
                    case T_CLASS_C:
                    case T_FUNC_C:
                    case T_TRAIT_C:
                        $newtype = CLIParser::TOKEN_DEFAULT;
                        break;

                    case T_COMMENT:
                    case T_DOC_COMMENT:
                    case T_CLASS:
                        $newtype = CLIParser::TOKEN_COMMENT;
                        break;

                    case T_STRING:
                    case T_ENCAPSED_AND_WHITESPACE:
                    case T_CONSTANT_ENCAPSED_STRING:
                        $newtype = CLIParser::TOKEN_STRING;
                        break;

                    case T_INLINE_HTML:
                        $newtype = CLIParser::TOKEN_HTML;
                        break;

                    default:
                        $newtype = CLIParser::TOKEN_KEYWORD;
                }
            } else {
                $newtype = $token === '"' ? CLIParser::TOKEN_STRING  : CLIParser::TOKEN_KEYWORD;
            }

            if ($current === null) {
                $current = $newtype;
            }

            if ($current !== $newtype) {
                $output[] = [$current, $buffer];
                $buffer   = '';
                $current  = $newtype;
            }

            $buffer .= is_array($token) ? $token[1]  : $token;
        }

        if (isset($newtype)) {
            $output[] = [$newtype, $buffer];
        }

        return $output;
    }

    /**
     * @param  array $tokens
     * @return array
     */
    protected function split(array $tokens) : array
    {
        $lines = [];

        $line = [];
        foreach ($tokens as $token) {
            foreach (explode("\n", $token[1]) as $count => $tline) {
                if ($count > 0) {
                    $lines[] = $line;
                    $line = [];
                }

                if ($tline === '') {
                    continue;
                }

                $line[] = [$token[0], $tline];
            }
        }

        $lines[] = $line;

        return $lines;
    }

    /**
     * @param  array $token
     * @return array
     */
    protected function color(array $token) : array
    {
        $lines = [];
        foreach ($token as $count => $tline) {
            $line = '';
            foreach ($tline as $token) {
                [$type, $value] = $token;

                if ($this->color->has($type)) {
                    $line .= $this->color->apply($type, $value);
                } else {
                    $line .= $value;
                }
            }

            $lines[$count] = $line;
        }

        return $lines;
    }

    /**
     * @param  array $lines
     * @param  int   $marker
     * @return string
     */
    protected function number(array $lines, int $marker = null)
    {
        end($lines);
        $length = mb_strlen(key($lines) + 1);

        $snippet = '';
        foreach ($lines as $i => $line) {
            if ($marker !== null) {
                $snippet .= ($marker === $i + 1 ? $this->color->apply(CLIParser::ACTUAL_LINE_MARK, '  > ')  : '    ');
            }

            $snippet .= $this->color->apply(CLIParser::LINE_NUMBER, str_pad($i + 1, $length, ' ', STR_PAD_LEFT) . '| ');

            $snippet .= $line . "\n";
        }

        return $snippet;
    }
}
