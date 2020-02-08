<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

namespace Octopy\Provider;

use Octopy\Support\Facade\Route;

class ToolbarServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $namespace = \Octopy\Debug\Toolbar\Controller::class;

    /**
     * @return void
     */
    public function register() : void
    {
        if ($this->app['config']['app.debug'] && $this->app['config']['toolbar.enabled']) {
            $this->app->middleware->set(\Octopy\HTTP\Middleware\InjectToolbar::class);
        }
    }

    /**
     * @return void
     */
    public function boot() : void
    {
        Route::group(['prefix' => $this->app['config']['toolbar.prefix'], 'namespace' => $this->namespace], static function ($route) {
            $route->get('assets/stylesheet', 'AssetController@stylesheet')
                    ->name('assets.stylesheet');

            $route->get('assets/javascript/:filename', 'AssetController@javascript')
                    ->name('assets.javascript');

            $route->get('detail/:time', 'DetailController@index')
                    ->name('toolbar.detail');
        });
    }
}
