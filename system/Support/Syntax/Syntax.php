<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 *
 * @author  : Supian M <supianidz@gmail.com>
 *
 * @link    : www.octopy.xyz
 *
 * @license : MIT
 */

namespace Octopy\Support;

use Exception;
use Octopy\Application;
use Octopy\Support\Syntax\CLIParser;
use Octopy\Support\Syntax\HTMLParser;

class Syntax
{
    /**
     * @var Octopy\Support\Syntax\CLIParser
     * @var Octopy\Support\Syntax\HTMLParser
     */
    protected $parser;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        switch (substr(PHP_SAPI, 0, 3)) {
            case 'cli':
                $this->parser = $app->make(CLIParser::class);
                break;

            default:
                $this->parser = $app->make(HTMLParser::class);
                break;
        }
    }

    /**
     * @param string $source
     * @param int    $marker
     * @param int    $before
     * @param int    $after
     *
     * @return string
     */
    public function highlight(string $source, int $marker = null, int $before = 0, int $after = 0) : string
    {
        if (is_file($source) && is_readable($source)) {
            try {
                $source = file_get_contents($source);
            } catch (Exception $exception) {
                throw $exception;
            }
        }

        try {
            return $this->parser->highlight($source, $marker, $before, $after);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
