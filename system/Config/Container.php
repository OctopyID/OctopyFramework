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
    'app'        => Octopy\Application::class,
    'autoload'   => Octopy\Autoload::class,
    'benchmark'  => Octopy\Debug\Benchmark::class,
    'collector'  => Octopy\Support\Collector::class,
    'config'     => Octopy\Config::class,
    'console'    => Octopy\Console::class,
    'database'   => Octopy\Database::class,
    'debugbar'   => Octopy\DebugBar::class,
    'encrypter'  => Octopy\Encryption\Encrypter::class,
    'env'        => Octopy\Config\DotEnv::class,
    'filesystem' => Octopy\FileSystem::class,
    'hash'       => Octopy\Hashing\HashManager::class,
    'middleware' => Octopy\HTTP\Middleware::class,
    'path'       => Octopy\FileSystem\PathLocator::class,
    'request'    => Octopy\HTTP\Request::class,
    'response'   => Octopy\HTTP\Response::class,
    'route'      => Octopy\HTTP\Routing\Router::class,
    'router'     => Octopy\HTTP\Routing\Router::class,
    'schema'     => Octopy\Database\Migration\Schema::class,
    'session'    => Octopy\Session::class,
    'syntax'     => Octopy\Support\Syntax::class,
    'validator'  => Octopy\Validation\Validator::class,
    'view'       => Octopy\View\Engine::class,
];
