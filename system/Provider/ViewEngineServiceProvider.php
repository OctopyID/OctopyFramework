<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : www.octopy.xyz
 * @license : MIT
 */

namespace Octopy\Provider;

use Octopy\View\Engine;

class ViewEngineServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $config = $app['config']['view'];
        $app->instance('view', new Engine(
            $config['resource'],
            $config['compiled']
        ));

        // We adding a view macro method in Response class.
        $macro = function (string $name, array $data = [], int $status = 200, array $header = []) use ($app) {
            $value = $app->view->render($name, $data);

            return $app->response->make($value, $status, $header);
        };

        $app->response->macro('view', $macro);
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->app['view']->share('app', $this->app);
    }
}
