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

return [
    'app'       => Octopy\Application::class,
    'autoload'  => Octopy\Autoload::class,
    'benchmark' => Octopy\Debug\Benchmark::class,
    'config'    => Octopy\Config::class,
    'console'   => Octopy\Console::class,
    'database'  => Octopy\Database::class,
    'env'       => Octopy\Config\DotEnv::class,
    'fsys'      => Octopy\FileSystem::class,
    'path'      => Octopy\FileSystem\PathLocator::class,
    'request'   => Octopy\HTTP\Request::class,
    'response'  => Octopy\HTTP\Response::class,
    'route'     => Octopy\HTTP\Routing\Router::class,
    'router'    => Octopy\HTTP\Routing\Router::class,
    'schema'    => Octopy\Database\Migration\Schema::class,
    'session'   => Octopy\Session::class,
    'syntax'    => Octopy\Support\Syntax::class,
    'validator' => Octopy\Validation\Validator::class,
    'view'      => Octopy\View\Engine::class,
];
