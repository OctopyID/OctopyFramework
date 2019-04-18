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
    public function success(string $value)
    {
        return $this->format('{green}' . $value);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function info(string $value)
    {
        return $this->format('{light_gray}' . $value);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function warning(string $value)
    {
        return $this->format('{yellow}' . $value);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function error(string $value)
    {
        return $this->format('{red}' . $value);
    }

    /**
     * @return string
     */
    public function help()
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
        $this->table->add(['header'], array(
            'header' => $this->yellow('Available Options')
        ));

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
        $this->table->add(['margin'], array(
            'margin' => ''
        ));
        
        $this->table->add(['header'], array(
            'header' => $this->yellow('Available Commands')
        ));

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
                    $this->table->add(['group'], array(
                        'group' => $this->yellow($prefix),
                    ));

                    $group[] = $prefix;
                }

                $this->table->margin(3);
                $this->table->add(['command', 'description'], array(
                    'command'     => $this->green($command),
                    'description' => $this->white($row->describe)
                ));
            }
        }

        return $output . $this->table->render();
    }
}
