<?php


/*
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

namespace Octopy\Foundation;

use Exception;
use Octopy\Application;
use Octopy\Support\Facade\DB;

class Auth
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $guard;

    /**
     * @var array
     */
    protected $config;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app    = $app;
        $this->config = $app->config['auth'];

        if (! $this->guard) {
            $this->guard($this->config['default']);
        }
    }

    /**
     * @return bool
     */
    public function check() : bool
    {
        return $this->app->session->has('authenticated');
    }

    /**
     * @param  string $column
     * @return int
     */
    public function id(string $column = 'id')
    {
        if ($this->check()) {
            return ($this->fetch())->$column;
        }
    }

    /**
     * @return object
     */
    public function fetch()
    {
        return json_decode($this->app->session->get('authenticated'));
    }

    /**
     * @param  array $data
     * @return bool
     */
    public function attempt(array $data) : bool
    {
        $key = array_keys($data);
        $val = array_values($data);

        switch ($this->provider['driver']) {
            case 'tentacle':
                $query = $this->provider['model']::where($key[0], $val[0]);
            break;
            case 'database':
                $query = DB::table($this->provider['table'])->where($key[0], $val[0]);
            break;
        }

        if ($query->count() > 0) {
            $hash = $query->first()->{$key[1]};
            if ($this->app->hash->verify($val[1], $hash)) {
                return $this->app->session->set('authenticated', json_encode($query->first([$key[1]])));
            }
        }

        return false;
    }

    /**
     * @param  string $guard
     * @return $this
     */
    public function guard(string $guard)
    {
        if (! array_key_exists($guard, $this->config['guards'])) {
            throw new Exception("Auth guard [$guard] is not defined");
        }

        $this->provider = $this->config['providers'][
            $this->config['guards'][$guard]['provider']
        ];

        return $this;
    }
}
