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

namespace Octopy\Console;

use Octopy\Application;
use Octopy\Console\Output\Color;
use Octopy\Console\Output\TableFormatter;

class Output extends Color
{
    /**
     * @var Octopy\Console\Output\TableFormatter
     */
    public $table;

    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->table = new TableFormatter($app);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function success(string $value) : string
    {
        return $this->format('{green}' . $value);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function info(string $value) : string
    {
        return $this->format('{light_gray}' . $value);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function warning(string $value) : string
    {
        return $this->format('{yellow}' . $value);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function error(string $value) : string
    {
        return $this->format('{red}' . $value);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function comment(string $value) : string
    {
        return $this->warning($value);
    }

    /**
     * @return string
     */
    public function help() : string
    {
        foreach (['system', 'shell_exec', 'exec'] as $function) {
            if (function_exists($function)) {
                $function('clear');
                break;
            }
        }

        $vers = $this->app->version();

        $octopy[] = "   ___       _                     ";
        $octopy[] = "  / _ \  ___| |_ ___  _ __  _   _  ";
        $octopy[] = " | | | |/ __| __/ _ \| '_ \| | | | ";
        $octopy[] = " | |_| | (__| || (_) | |_) | |_| | ";
        $octopy[] = "  \___/ \___|\__\___/| .__/ \__, | ";
        $octopy[] = "   www.octopy.xyz    |_|    |___/  ";

        $output  = $this->yellow(implode("\n", $octopy)) . "\n";
        $output .= $this->white(' USAGE : command [options] [args]') . "\n";

        // Header
        $this->table->add(['header'], [
            'header' => $this->yellow('Available Option :')
        ]);

        // Command without prefix
        $rows = [];

        $this->table->margin(3);
        foreach ($this->app['console']->all() as $command => $row) {
            if (substr($command, 0, 1) === '-' || substr($command, 0, 2) === '--') {
                $this->table->add(['command', 'description'], [
                    'command'     => $this->green($command),
                    'description' => $this->white($row->describe)
                ]);
            } else {
                if (strpos($command, ':') == false) {
                    $rows[0][$command] = $row;
                } else {
                    $rows[1][$command] = $row;
                }
            }
        }

        $this->table->margin(0);
        $this->table->add(['margin'], [
            'margin' => ''
        ]);

        $this->table->add(['header'], [
            'header' => $this->yellow('Available Command :')
        ]);

        $this->table->margin(3);
        foreach ($rows[0] as $command => $row) {
            $this->table->add(['command', 'description'], [
                'command'     => $this->green($command),
                'description' => $this->white($row->describe)
            ]);
        }

        asort($rows);
        if (!empty($rows[1])) {
            $group = [];
            foreach ($rows[1] as $command => $row) {
                list($prefix) = explode(':', $command);

                if (!in_array($prefix, $group)) {
                    $this->table->margin(2);
                    $this->table->add(['group'], [
                        'group' => $this->yellow($prefix),
                    ]);

                    $group[] = $prefix;
                }

                $this->table->margin(3);
                $this->table->add(['command', 'description'], [
                    'command'     => $this->green($command),
                    'description' => $this->white($row->describe)
                ]);
            }
        }

        return $output . $this->table->render();
    }

    /**
     * @param  string $command
     * @return string
     */
    public function undefined(string $command) : string
    {
        if (substr($command, 0, 2) === '--') {
            return $this->error(sprintf('The "%s" option does not exist.', $command));
        }

        $list = array_filter($this->app['console']->all(), function ($key) {
            return substr($key, 0, 2) !== '--';
        }, ARRAY_FILTER_USE_KEY);

        $possible = [];

        foreach ($list as $key => $value) {
            if (preg_match('/' . str_replace(':', '|', $key) . '/', $command)) {
                $possible[] = $key;
            }
        }

        $error[] = sprintf('  Command "%s" is not defined.  ', $command);

        if (!empty($possible)) {
            $error[] = '';
            $error[] = '  Did you mean one of these ?';
            foreach ($possible as $value) {
                $error[] = "      $value";
            }

            $error[] = '';
        }

        $length = 0;
        foreach ($error as $value) {
            if (strlen($value) > $length) {
                $length = strlen($value);
            }
        }

        foreach ($error as $key => $value) {
            $error[$key] = $value . str_repeat(' ', $length - strlen($value));
        }

        return $this->format('{bg_red}{white}' . implode("\n", $error));
    }
}
