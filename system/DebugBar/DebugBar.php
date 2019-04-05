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

namespace Octopy;

use Throwable;

use Octopy\Application;
use Octopy\View\Engine;
use Octopy\HTTP\Response;

class DebugBar
{
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
    }

    /**
     * @param  Response $response
     * @return void
     */
    public function modify(Response $response)
    {
        $content = $response->body;

        $debugbar = $this->render($this->app->view);

        $middle = strripos($content, '</body>');
        if (false !== $middle) {
            $content = substr($content, 0, $middle) . $debugbar . substr($content, $middle);
        } else {
            $content = $content . $debugbar;
        }

        return $response->body($content);
    }

    /**
     * @param  Engine $view
     * @return string
     */
    protected function render(Engine $view) : string
    {
        $view->finder->set(__DIR__ . DS);

        try {
            return $view->render('template.debugbar', [
                'collector' => array_map([$this->app, 'make'], $this->app->config['debugbar.collector'])
            ]);
        } catch (Throwable $exception) {
            return '';
        }
    }
}
