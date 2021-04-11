<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

use Octopy\Stuff\Inspiring;
use Octopy\Support\Facade\Console;

Console::command('inspire', static function (Octopy\Console\Output $output) {
    return $output->comment(Inspiring::quote());
})->describe('Display an inspiring quote');
