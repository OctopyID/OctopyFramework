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

class IteratorDirective extends Directive
{
    /**
     * @param  Stream $stream
     * @return string
     */
    public function parse(Stream $stream)
    {
        if (in_array($stream->token(), [T_FOR, T_FOREACH, T_WHILE])) {
            return $this->php('%s(%s) :', $stream->type(), $stream->value());
        }

        if (in_array($stream->token(), [T_ENDFOR, T_ENDFOREACH, T_ENDWHILE])) {
            return $this->php('%s;', $stream->type());
        }

        if ($stream->next(T_CONTINUE)) {
            if ($stream->value() == '') {
                return $this->php('%s;', $stream->type());
            }

            return $this->php('if(%s) : continue; endif;', $stream->value());
        }

        if ($stream->next(T_BREAK)) {
            if ($stream->value() == '') {
                return $this->php('%s;', $stream->type());
            }

            return $this->php('if(%s) : break; endif;', $stream->value());
        }
    }
}
