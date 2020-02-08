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

namespace Octopy\Debug\Toolbar\Controller;

use Octopy\Application;
use Octopy\HTTP\Response;
use Octopy\HTTP\Controller;

class DetailController extends Controller
{
    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $app->view->resource(
            $app->path->system->debug->toolbar('View')
        );

        $this->app->toolbar->boot($this->app);
    }

    /**
     * @param  string   $id
     * @param  Response $response
     * @return Response
     */
    public function index(string $id, Response $response)
    {
        return $response->view('content', [
            'tool' => $this->app->toolbar,
            'time' => $this->app->toolbar->time(),
            'data' => json_decode(file_get_contents(
                $this->app->config->get('toolbar.storage', 'storage') . $id . '.json'
            )),
        ]);
    }
}
