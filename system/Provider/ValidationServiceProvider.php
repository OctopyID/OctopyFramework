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

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $app = $this->app;
        $app->request->macro('validate', function (array $rules) use ($app) {
            if (! $app->validator->validate($this, $rules)) {
                $message = array_reverse($app->validator->message());

                if ($this->ajax()) {
                    return $app->response->json($message, 422)->send();
                }

                return $app->response->flash('error', $message)->redirect()->back(422)->send();
            }
        });
    }
}
