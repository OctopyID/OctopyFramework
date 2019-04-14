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

use Octopy\View\Stream;

class IncludeDirective extends Directive
{
    /**
     * @param  Stream $stream
     * @return string
     */
    public function parse(Stream $stream)
    {
        if ($stream->next(T_INCLUDE)) {
            return $this->php('echo $this->render(%s);', $stream->expression());
        }
    }
}
