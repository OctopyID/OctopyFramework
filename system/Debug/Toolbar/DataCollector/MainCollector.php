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

namespace Octopy\Debug\Toolbar\DataCollector;

class MainCollector extends Collector
{
    /**
     * @var string
     */
    public $name = 'Main';

    /**
     * @var boolean
     */
    public $show = false;

    /**
     * @return array
     */
    public function collect()
    {
        return [
            'status' => http_response_code(),
            'url'    => $this->app->request->url(),
            'method' => $this->app->request->method(),
            'ajax'   => $this->app->request->ajax(),
        ];
    }
}
