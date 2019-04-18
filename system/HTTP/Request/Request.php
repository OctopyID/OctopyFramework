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

namespace Octopy\HTTP;

use Octopy\Support\Str;
use Octopy\Support\Macroable;
use Octopy\HTTP\Request\Collection;
use Octopy\HTTP\Request\FileHandler;

class Request
{
    use Macroable;
    
    /**
     * @var ctopy\HTTP\Request\Collection
     */
    protected $query;

    /**
     * @var ctopy\HTTP\Request\Collection
     */
    protected $request;

    /**
     * @var ctopy\HTTP\Request\Collection
     */
    protected $file;

    /**
     * @var ctopy\HTTP\Request\Collection
     */
    protected $cookie;

    /**
     * @var ctopy\HTTP\Request\Collection
     */
    protected $server;

    /**
     *
     */
    public function __construct()
    {
        foreach (['query' => $_GET, 'request' => $_POST, 'file' => $_FILES, 'cookie' => $_COOKIE,
             'server' => $_SERVER] as $property => $request) {

            //
            if ($property === 'file') {
                $request = array_map(function ($value) {
                    return new FileHandler($value);
                }, $request);
            }

            $this->$property = new Collection($request);
        }
    }

    /**
     * @param  string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        if ($value = $this->input($key)) {
            return $value;
        }

        return $this->file($key);
    }
    
    /**
     * @param  string $source
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function retrieve(string $source, string $key = null, $default = null)
    {
        if (!is_null($key)) {
            return $this->$source->get($key, $default);
        }

        return $this->$source->all();
    }

    /**
     * @return array
     */
    public function all() : array
    {
        return $this->input() + $this->file();
    }

    /**
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function query(string $key = null, $default = null)
    {
        return $this->retrieve('query', $key, $default);
    }

    /**
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function input(string $key = null, $default = null)
    {
        return $this->retrieve('request', $key, $default);
    }

    /**
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function file(string $key = null, $default = null)
    {
        return $this->retrieve('file', $key, $default);
    }

    /**
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function cookie(string $key = null, $default = null)
    {
        return $this->retrieve('cookie', $key, $default);
    }

    /**
     * @param  string $key
     * @return string
     */
    public function server(string $key)
    {
        return $this->retrieve('server', $key);
    }

    /**
     * @return string
     */
    public function method() : string
    {
        return $this->server('REQUEST_METHOD');
    }

    /**
     * @return string
     */
    public function uri() : string
    {
        return preg_replace('/\+/', '/', $this->server('REQUEST_URI'));
    }

    /**
     * @return string
     */
    public function url() : string
    {
        return $this->scheme(true) . $this->domain() . $this->uri();
    }

    /**
     * @return string
     */
    public function path() : string
    {
        $root = explode('/', $this->server('PHP_SELF'));
        array_pop($root);
        $root = implode('/', $root);
        
        return rtrim(preg_replace('/\?.*/', '', preg_replace('/\/+/', '/', str_replace($root, '', $this->server('REQUEST_URI')))), '/') ? : '/';
    }

    /**
     * @return string
     */
    public function domain() : string
    {
        return $this->server('SERVER_NAME');
    }

    /**
     * @return string
     */
    public function scheme(bool $suffix = false) : string
    {
        if ($suffix) {
            return $this->server('REQUEST_SCHEME') . '://';
        }

        return $this->server('REQUEST_SCHEME');
    }

    /**
     * @return string
     */
    public function uagent() : string
    {
        return $this->server('HTTP_USER_AGENT');
    }

    /**
     * @return string
     */
    public function referer() : string
    {
        return $this->server('HTTP_REFERER');
    }

    /**
     * @return bool
     */
    public function secure() : bool
    {
        return $this->server('HTTPS') === 'on';
    }

    /**
     * @return bool
     */
    public function ajax() : bool
    {
        return strtoupper($this->server('HTTP_X_REQUESTED_WITH')) === 'XMLHTTPREQUEST';
    }

    /**
     * @param  string $name
     * @return mixed
     */
    public function header(string $name = null)
    {
        $header = [];
        foreach ($_SERVER as $key => $val) {
            if (preg_match('/\AHTTP_/', $key)) {
                $name  = preg_replace('/\AHTTP_/', '', $key);
                $match = explode('_', $name);
                if (count($match) > 0 and strlen($name) > 2) {
                    foreach ($match as $subkey => $subvalue) {
                        $match[$subkey] = ucfirst($subvalue);
                    }

                    $name = implode('-', $match);
                }

                $header[ucwords(strtolower($name), '-')] = $val;
            }
        }
        
        if (!is_null($name)) {
            return $header[$name] ?? null;
        }
        
        return $header;
    }

    /**
     * @param  bool $server
     * @return string
     */
    public function ip(bool $server = false) : string
    {
        if ($server) {
            return $this->server('SERVER_ADDR');
        }

        return $this->server('HTTP_CLIENT_IP') ?? $this->server('HTTP_X_FORWARDED_FOR') ?? $this->server('REMOTE_ADDR');
    }

    /**
     * @return int
     */
    public function port() : int
    {
        return $this->server('SERVER_PORT');
    }

    /**
     * @param  mixed ...$patterns
     * @return bool
     */
    public function is(...$patterns)
    {
        $path = rawurldecode($this->path());

        foreach ($patterns as $pattern) {
            if (Str::is($pattern, $path)) {
                return true;
            }
        }

        return false;
    }
}
