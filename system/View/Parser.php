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

use Throwable;

class Parser
{
    /**
     * @var array
     */
    protected $engine;

    /**
     * @var array
     */
    protected $footer = [];

    /**
     * @param Engine $engine
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;

        $this->directive[] = new Compiler\RawPHPDirective;
        $this->directive[] = new Compiler\HelperDirective;
        $this->directive[] = new Compiler\LayoutDirective;
        $this->directive[] = new Compiler\IncludeDirective;
        $this->directive[] = new Compiler\ControlDirective;
        $this->directive[] = new Compiler\IteratorDirective;
    }

    /**
     * @param  string $compiled
     * @return string
     */
    public function footer(?string $compiled) : string
    {
        $this->footer[] = $compiled;

        return '';
    }

    /**
     * @param  Storage $storage
     * @return string
     */
    public function compile(Storage $storage) : ?string
    {
        $source = $storage->source();

        // Removing PHP Comment parser adapted from Laravel Blade
        $source = preg_replace('/{{--(.*?)--}}/s', '', $source);

        // Unescaped output parser adapted from Laravel Blade
        $source = preg_replace_callback('/(@)?{{{\s*(.+?)\s*}}}?/s', function ($match) {
            return $match[1] ? substr($match[0], 1) : sprintf('<?php echo %s; ?>', $match[2]);
        }, $source);

        // Escaped output parser adapted from Laravel Blade
        $source = preg_replace_callback('/(@)?{{\s*(.+?)\s*}}?/s', function ($match) {
            return $match[1] ? substr($match[0], 1) : sprintf('<?php echo $this->escape(%s); ?>', $match[2]);
        }, $source);

        //
        $compiled = '';
        foreach (token_get_all($source) as $token) {
            $compiled .= is_array($token) ? $this->parse(...$token) : $token;
        }

        unset($source);

        if (count($this->footer) > 0) {
            $compiled = ltrim($compiled) . "\n" . implode("\n", array_reverse($this->footer));
            $this->footer = [];
        }

        try {
            $storage->write(sprintf("<?php /* %s */ ?>\n%s", $storage->template(), $compiled));
        } catch (Throwable $exception) {
            throw $exception;
        }

        return $compiled;
    }

    /**
     * @param  int    $token
     * @param  string $content
     * @return string
     */
    protected function parse(int $token, ?string $content)
    {
        $search = [];
        if ($token === T_INLINE_HTML) {
            preg_match_all('/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x', $content, $match);

            foreach ($match[1] as $x => $type) {
                $key = $match[0][$x];
                if (! array_key_exists($key, $search)) {
                    $search[$key] = $this->stream($type, $match[3][$x]);
                }
            }
        }

        if (count($search) > 0) {
            $content = str_replace(array_keys($search), $search, $content);
        }

        return $content;
    }

    /**
     * @param  string $type
     * @param  string $parameter
     * @return string
     */
    protected function stream(string $type, string $parameter)
    {
        $stream = new Stream(token_get_all('<?php ' . $type), $parameter);

        if ($directive = $this->engine->directive($type)) {
            return $directive($stream->expression());
        } else {
            foreach ($this->directive as $directive) {
                if ($compiled = $directive->parse($stream, $this)) {
                    return $compiled;
                }
            }
        }
    }
}
