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

namespace Octopy\Console\Command;

use Closure;
use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;
use Octopy\Console\Output\TableFormatter;

class RouteListCommand extends Command
{
    /**
     * @var string
     */
    protected $command = 'route:list';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $argument = [];

    /**
     * @var string
     */
    protected $description = 'List all registered routes';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        $color = static function ($text) use ($output) {
            return rtrim($output->format('<c:green>' . $text), "\n");
        };

        $data = [];
        foreach ($this->app['router']->collection as $method => $routes) {
            foreach ($routes as $uri => $row) {
                $middleware = [];
                foreach ($row->middleware as $layer) {
                    $middleware[] = $layer instanceof Closure ? 'Closure' : $layer;
                }

                $action = $row->controller instanceof Closure ? 'Closure' : $row->controller;

                $method = implode(' & ', $row->method);

                $data[$method . $uri] = [
                    $color('Method')     => $color($method),
                    $color('URI')        => $color($uri),
                    $color('Name')       => $color($row->name),
                    $color('Action')     => $color($action),
                    $color('Middleware') => $color(implode(', ', $middleware)),
                ];
            }
        }

        return $output->reset(new TableFormatter(array_values($data)));
    }
}
