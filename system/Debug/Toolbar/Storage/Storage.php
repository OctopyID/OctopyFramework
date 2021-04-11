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

namespace Octopy\Debug\Toolbar;

use Octopy\Application;
use Octopy\Debug\Toolbar\DataCollector\HistoryCollector;

abstract class Storage
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @param  Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return string
     */
    protected function content() : string
    {
        $content = [
            'time' => $this->app->toolbar->time(),
        ];

        foreach ($this->app->toolbar->collectors() as $collector) {
            if ($collector instanceof HistoryCollector) {
                continue;
            }

            $content[strtolower($collector->name)] = $collector->collect();
        }

        return json_encode($content, JSON_PRETTY_PRINT);
    }
}
