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

namespace App\Provider;

use Octopy\Support\Facade\Route;
use Octopy\Provider\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $namespace = \App\HTTP\Controller::class;

    /**
     * @return void
     */
    public function map() : void
    {
        Route::group(['namespace' => $this->namespace], static function ($route) {
            $route->load('Web.php');

            $route->group(['prefix' => 'api'], static function ($route) {
                $route->load('Api.php');
            });
        });
    }
}
