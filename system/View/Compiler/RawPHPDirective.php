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

class RawPHPDirective extends Directive
{
    /**
     * @param  Stream $stream
     * @return string
     */
    public function parse(Stream $stream)
    {
        // @php($foo = 'bar')
        if ($stream->next('php') && $stream->value() != '') {
            return $this->php('%s;', $stream->value());
        }

        // @php
        if ($stream->next('php') && $stream->value() == '') {
            return '<?php ';
        }

        // @endphp
        if ($stream->next('endphp')) {
            return ' ?>';
        }

        if ($stream->next(T_UNSET)) {
            return $this->php('%s(%s);', $stream->type(), $stream->value());
        }

        if ($stream->next(T_EXIT)) {
            if ($stream->value() == '') {
                return $this->php('%s;', $stream->type());
            }

            return $this->php('if(%s) : %s; endif;', $stream->value(), $stream->type());
        }
    }
}
