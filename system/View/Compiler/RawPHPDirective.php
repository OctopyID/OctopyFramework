<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
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
        if ($stream->next('php') && $stream->expression() !== '') {
            return $this->php('%s;', $stream->expression());
        } else if ($stream->next('php') && $stream->expression() === '') {
            return '<?php ';
        } else if ($stream->next('endphp')) {
            return ' ?>';
        } else if ($stream->next(T_UNSET)) {
            return $this->php('%s(%s);', $stream->code(), $stream->expression());
        } else if ($stream->next(T_EXIT)) {
            if ($stream->expression() === '') {
                return $this->php('%s;', $stream->code());
            }

            return $this->php('if(%s) : %s; endif;', $stream->expression(), $stream->code());
        }
    }
}
