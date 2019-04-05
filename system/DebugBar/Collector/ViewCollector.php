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

namespace Octopy\DebugBar\Collector;

class ViewCollector extends DebugBarCollector
{
    /**
     * @var string
     */
    protected $name = 'view';

    /**
     * @return string
     */
    public function icon() : string
    {
        return 'icon-code';
    }
}
