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

namespace Octopy\View\Compiler;

class Directive
{
    /**
     * @param  string $format
     * @param  array  $args
     * @return string
     */
    protected function php(string $format, ...$args) : string
    {
        return sprintf('<?php ' . $format . ' ?>', ...$args);
    }
}
