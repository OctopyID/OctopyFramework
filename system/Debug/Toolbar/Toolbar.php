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

namespace Octopy\Debug;

use Octopy\Application;
use Octopy\Debug\Toolbar\Storage\FileStorage;
use Octopy\Debug\Toolbar\DataCollector\MainCollector;
use Octopy\Debug\Toolbar\DataCollector\HistoryCollector;

class Toolbar
{
    /**
     * @var string
     */
    protected $time;

    /**
     * @var array
     */
    protected $collectors = [];

    /**
     * @return void
     */
    public function boot($app)
    {
        if (! $this->time) {
            $this->time = time();
        }

        $collectors = array_merge($app->config['toolbar.collectors'], [MainCollector::class, HistoryCollector::class]);
        foreach ($collectors as $collector) {
            $this->collectors[] = $app->make($collector);
        }

        return $this;
    }

    /**
     * @param  Application $app
     */
    public function write($app)
    {
        $app->make(FileStorage::class)
            ->write();
    }

    /**
     * @return array
     */
    public function collectors() : array
    {
        return $this->collectors;
    }

    /**
     * @return bool
     */
    public function enabled() : bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function time()
    {
        return $this->time;
    }

    /**
     * @param  Response $response
     * @return Response
     */
    public function modify($response)
    {
        $content = (string) $response->body;

        $rendered = '<script id="toolbar_loader" data-time="' . $this->time . '" src="' . route("assets.javascript", "octopy.js") . '"></script>';
        $rendered .= '<style type="text/css" id="toolbar_dynamic_style"></style>';
        $rendered .= '<script type="text/javascript" id="toolbar_dynamic_script"></script>';

        $position = strripos($content, '</body>');
        if (false !== $position) {
            $content = substr($content, 0, $position) . $rendered . substr($content, $position);
        } else {
            $content = $content . $rendered;
        }

        return $content;
    }
}
