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

use Octopy\HTTP\Response;

Route::get('/', static function (Response $response) {
    return $response->view('welcome', [], 200);
});

Route::get('/test', function () {
    $x = '⚔' . md5('dwad') . '⚔';
    echo $x . '<br>';
    echo 'substr(string, start) => ' . substr($x, 0, 10);
    echo '<br>';
    echo 'mb_substr(str, start) => ' . mb_substr($x, 0, 10);
});
