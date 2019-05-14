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

namespace Octopy;

use Exception;
use ArrayAccess;
use SessionHandlerInterface;
use Octopy\Session\Handler\FileSessionHandler;
use Octopy\Session\Handler\NullSessionHandler;

class Session implements ArrayAccess
{
    /**
     * @param Application             $app
     * @param SessionHandlerInterface $handler
     */
    public function __construct(Application $app, SessionHandlerInterface $handler)
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new Exception('You don\'t need to manually use session_start()');
        }

        extract($config = $app['config']['session'], EXTR_SKIP);

        if (! $handler instanceof NullSessionHandler) {
            session_set_save_handler($handler, true);

            if (empty($lifetime)) {
                $lifetime = $config['lifetime'] = (int) ini_get('session.gc_maxlifetime');
            } else {
                ini_set('session.gc_maxlifetime', (int) $lifetime);
            }

            if (empty($storage)) {
                $storage = $config['storage'] = ini_get('session.save_path');
            } else {
                ini_set('session.save_path', $storage);
            }

            session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
        }

        // Security is king
        ini_set('session.use_cookies', 1);
        ini_set('session.use_trans_sid', 0);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.use_only_cookies', 1);

        session_start();
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function set($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $name => $value) {
                if (is_int($name)) {
                    $name = $value;
                }

                $this->set($name, $value);
            }

            return true;
        }

        $_SESSION[$name] = $value;

        return true;
    }

    /**
     * @param  string $name
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        if ($this->has($name)) {
            return $_SESSION[$name];
        }

        return $default;
    }

    /**
     * @return array
     */
    public function all() : array
    {
        return $_SESSION;
    }

    /**
     * @param  string $name
     * @param  mixed  $default
     * @return mixed
     */
    public function pull(string $name, $default = null)
    {
        $result = $this->get($name, $default);

        $this->forget($name);

        return $result;
    }

    /**
     * @param  string $name
     * @return string
     */
    public function forget(string $name) : bool
    {
        if ($this->has($name)) {
            unset($_SESSION[$name]);
        }

        return true;
    }

    /**
     * @param  string $name
     * @return bool
     */
    public function has(string $name) : bool
    {
        return isset($_SESSION[$name]);
    }

    /**
     * @param  bool $destroy
     * @return string
     */
    public function regenerate(bool $destroy = false) : string
    {
        return session_regenerate_id($destroy);
    }

    /**
     * @return bool
     */
    public function destroy() : bool
    {
        return session_destroy();
    }

    /**
     * @param  string $name
     * @return string
     */
    public static function handler(?string $name) : string
    {
        $handler = [
            'file'  => FileSessionHandler::class,
            'array' => NullSessionHandler::class,
        ];

        return $handler[$name] ?? $handler['array'];
    }

    /**
     * @param  string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * @param  string $key
     * @return object
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return mixed
     */
    public function offsetSet($key, $value)
    {
        return $this->set($key, $value);
    }

    /**
     * @param string $key
     */
    public function offsetUnset($key)
    {
        $this->forget($key);
    }
}
