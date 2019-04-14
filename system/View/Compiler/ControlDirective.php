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

class ControlDirective extends Directive
{
    /**
     * @param  Stream $stream
     * @return string
     */
    public function parse(Stream $stream)
    {
        if (in_array($stream->token(), [T_IF, T_ELSEIF, T_ELSE])) {
            if ($stream->next(T_ELSE)) {
                return $this->php('%s :', $stream->code());
            }

            return $this->php('%s(%s) :', $stream->code(), $stream->expression());
        }

        if ($stream->next(T_ENDIF)) {
            return $this->php('%s;', $stream->code());
        }
    }
}
