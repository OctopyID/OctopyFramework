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

namespace Octopy\Console;

use Octopy\Application;
use Octopy\Console\Output\Color;
use Octopy\Console\Output\MenuFormatter;

class Output extends Color
{
    /**
     * @var Octopy\Console\Output\MenuFormatter
     */
    protected $table;

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
        $this->table = new MenuFormatter($app);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function success(string $value) : string
    {
        return $this->format('<c:green>' . $value);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function info(string $value) : string
    {
        return $this->format('<c:lightgray>' . $value);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function warning(string $value) : string
    {
        return $this->format('<c:yellow>' . $value);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function error(string $value) : string
    {
        return $this->format('<c:red>' . $value);
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
     * @param  string $value
     * @return string
     */
    public function reset(string $value) : string
    {
        return $this->format('<c:white>' . $value);
    }

    /**
     * @param  Route $route
     * @return string
     */
    public function help(Route $route) : string
    {
        $output  = $this->banner();

        // description
        $this->table->add(['header'], [
            'header' => $this->yellow('Description :'),
        ]);

        $this->table->margin(3);
        $this->table->add(['description'], [
            'description' => $this->white($route->describe),
        ]);

        $this->table->margin(0);
        $this->table->add(['margin'], [
            'margin' => '',
        ]);

        // usage
        $this->table->add(['usage'], [
            'usage' => $this->yellow('Usage :'),
        ]);

        $this->table->margin(3);

        $route->command .= ' [options]';
        if (count($route->argument)) {
            $route->command .= ' [--] ';

            foreach ($route->argument as $argument => $description) {
                $route->command .= "<$argument> ";
            }
        }

        $this->table->add(['usage'], [
            'usage' => $this->white($route->command),
        ]);

        // for avoiding unnecessary whitespace
        $output .= $this->table->render();

        // argument
        if (count($route->argument) > 0) {
            $this->table->margin(0);
            $this->table->add(['margin'], [
                'margin' => '',
            ]);

            $this->table->add(['argument'], [
                'argument' => $this->yellow('Arguments :'),
            ]);

            $this->table->margin(3);
            foreach ($route->argument as $argument => $description) {
                $this->table->add(['argument', 'description'], [
                    'argument'    => $this->green($argument),
                    'description' => $this->white($description),
                ]);
            }
        }

        $this->table->margin(0);
        $this->table->add(['margin'], [
            'margin' => '',
        ]);

        // option
        $this->table->add(['option'], [
            'option' => $this->yellow('Options :'),
        ]);

        $this->table->margin(3);
        foreach ($route->option as $option => $description) {
            $description = preg_replace_callback('/\[(.*)\]/', function ($colored) {
                return $this->yellow($colored[0]);
            }, $description);

            $this->table->add(['option', 'description'], [
                'option'      => $this->green($option),
                'description' => str_repeat(' ', 3) . $this->white($description),
            ]);
        }

        return $output . $this->table->render();
    }

    /**
     * @return string
     */
    public function list() : string
    {
        $output  = $this->banner();
        $output .= $this->white(' USAGE : command [options] [args]') . "\n";

        // Header
        $this->table->add(['header'], [
            'header' => $this->yellow('Available Options :'),
        ]);

        // Command without prefix
        $routes = [];

        $this->table->margin(3);
        foreach ($this->app['console']->all() as $command => $route) {
            if (mb_substr($command, 0, 1) === '-' || mb_substr($command, 0, 2) === '--') {
                $this->table->add(['command', 'description'], [
                    'command'     => $this->green($command),
                    'description' => $this->white($route->describe),
                ]);
            } else {
                if (mb_strpos($command, ':') === false) {
                    $routes[0][$command] = $route;
                } else {
                    $routes[1][$command] = $route;
                }
            }
        }

        $this->table->margin(0);
        $this->table->add(['margin'], [
            'margin' => '',
        ]);

        $this->table->add(['header'], [
            'header' => $this->yellow('Available Commands :'),
        ]);

        $this->table->margin(3);
        foreach ($routes[0] as $command => $route) {
            $this->table->add(['command', 'description'], [
                'command'     => $this->green($command),
                'description' => $this->white($route->describe),
            ]);
        }

        asort($routes);
        if (! empty($routes[1])) {
            $group = [];
            foreach ($routes[1] as $command => $route) {
                [$prefix] = explode(':', $command);

                if (! in_array($prefix, $group)) {
                    $this->table->margin(2);
                    $this->table->add(['group'], [
                        'group' => $this->yellow($prefix),
                    ]);

                    $group[] = $prefix;
                }

                $this->table->margin(3);
                $this->table->add(['command', 'description'], [
                    'command'     => $this->green($command),
                    'description' => $this->white($route->describe),
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
        if (mb_substr($command, 0, 2) === '--') {
            return $this->error(sprintf('The "%s" option does not exist.', $command));
        }

        $list = array_filter($this->app['console']->all(), static function ($key) {
            return mb_substr($key, 0, 2) !== '--';
        }, ARRAY_FILTER_USE_KEY);

        $possible = [];

        foreach ($list as $key => $value) {
            if (preg_match('/' . str_replace(':', '|', $key) . '/', $command)) {
                $possible[] = $key;
            }
        }

        $error[] = sprintf('  Command "%s" is not defined.  ', $command);

        if (! empty($possible)) {
            $error[] = '';
            $error[] = '  Did you mean one of these ?';
            foreach ($possible as $value) {
                $error[] = "      $value";
            }

            $error[] = '';
        }

        $length = 0;
        foreach ($error as $value) {
            if (mb_strlen($value) > $length) {
                $length = mb_strlen($value);
            }
        }

        foreach ($error as $key => $value) {
            $error[$key] = $value . str_repeat(' ', $length - mb_strlen($value));
        }

        return $this->format('<b:red><c:white>' . implode("\n", $error));
    }

    /**
     * @return string
     */
    private function banner() : string
    {
        foreach (['system', 'shell_exec', 'exec'] as $shell) {
            if (function_exists($shell)) {
                $shell('clear');
                break;
            }
        }

        $octopy[] = "   ___       _                     ";
        $octopy[] = "  / _ \  ___| |_ ___  _ __  _   _  ";
        $octopy[] = " | | | |/ __| __/ _ \| '_ \| | | | ";
        $octopy[] = " | |_| | (__| || (_) | |_) | |_| | ";
        $octopy[] = "  \___/ \___|\__\___/| .__/ \__, | ";
        $octopy[] = "   framework.octopy.id    |_|    |___/  ";
        $octopy[] = "";


        return $this->yellow(implode("\n", $octopy));
    }
}
