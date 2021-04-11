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

use Octopy\View\Parser;
use Octopy\View\Stream;

class LayoutDirective extends Directive
{
    /**
     * @param  Stream $stream
     * @param  Parser $parser
     * @return string
     */
    public function parse(Stream $stream, Parser $parser)
    {
        if ($stream->next('parent') || $stream->next('extend')) {
            return $parser->footer($this->php('echo $this->render(%s);', $stream->expression()));
        } else if ($stream->next('section') || $stream->next('block')) {
            return $this->php('$this->section(%s);', $stream->expression());
        } else if ($stream->next('endsection') || $stream->next('endblock')) {
            return $this->php('$this->endsection();');
        } else if ($stream->next('yield') || $stream->next('child')) {
            return $this->php('echo $this->yield(%s);', $stream->expression());
        }
    }
}
