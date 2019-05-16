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

namespace Octopy\Support\Syntax;

class HTMLParser
{
    /**
     * @return string
     */
    public function stylesheet() : string
    {
        return sprintf('<style type="text/css">%s</style>', file_get_contents(
            sprintf('%s/Vendor/prism.min.css', __DIR__)
        ));
    }

    /**
     * @return string
     */
    public function javascript()
    {
        return sprintf('<script type="text/javascript">%s</script>', file_get_contents(
            sprintf('%s/Vendor/prism.min.js', __DIR__)
        ));
    }

    /**
     * @param  string $source
     * @param  int    $marker
     * @param  string $offset
     * @param  string $lang
     * @return string
     */
    public function highlight(string $source, int $marker = 0, string $offset = null, string $lang = 'php')
    {
        [$source, $offset] = $this->slice($source, $marker, $offset);

        return $this->format($marker, $offset, $lang, htmlspecialchars($source));
    }

    /**
     * @param  array $format
     * @return string
     */
    private function format(...$format) : string
    {
        if ($format[0] > 0) {
            return sprintf('<pre class="line-numbers" data-line="%s" data-start="%s"><code class="language-%s">%s</code></pre>', ...$format);
        }

        unset($format[0]);

        return sprintf('<pre class="line-numbers" data-start="%s"><code class="language-%s">%s</code></pre>', ...$format);
    }

    /**
     * @param  string $source
     * @param  int    $marker
     * @param  string $offset
     * @return array
     */
    private function slice(string $source, int $marker, string $offset = null) : array
    {
        if ($offset && strpos($offset, ':')) {
            [$before, $after] = explode(':', $offset, 2);
            $offset = max($marker - $before - 1, 0);
            $length = $after + $before + 1;
            $source = array_slice(explode("\n", $source), $offset, $length, true);
            $source = implode("\n", $source);
        }

        return [$source, $offset + 1];
    }
}
