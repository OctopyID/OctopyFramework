<?php

/**
 * @  ___       _
 * @ / _ \  ___| |_ ___  _ __  _   _
 * @| | | |/ __| __/ _ \| '_ \| | | |
 * @| |_| | (__| || (_) | |_) | |_| |
 * @ \___/ \___|\__\___/| .__/ \__, |
 * @                    |_|    |___/
 *
 * @author  : Supian M <supianidzgmail.com>
 *
 * @link    : www.octopy.xyz
 *
 * @license : MIT
 */

namespace Octopy\Session\Handler;

use Octopy\Application;
use Octopy\Session\Exception\SessionException;
use SessionHandlerInterface;

class FileSessionHandler implements SessionHandlerInterface
{
    /**
     * @var string
     */
    protected $data;

    /**
     * @@var string
     */
    protected $storage;

    /**
     * @@param Application $app
     */
    public function __construct(array $config)
    {
        $this->storage = $config['storage'];
    }

    /**
     * @@param  string $storage
     * @@param  string $name
     *
     * @@return bool
     */
    public function open($storage, $name)
    {
        $this->storage = $this->storage ?? $storage;

        if (!is_dir($this->storage)) {
            if (!mkdir($this->storage, 0755, true)) {
                throw new SessionException("Configured save path [{$this->storage}] is not a directory, doesn't exist or cannot be created.");
            }
        } elseif (!is_writable($this->storage)) {
            throw new SessionException("Configured save path [{$this->storage}] is not writable by the PHP process.");
        }

        return true;
    }

    /**
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function read($id)
    {
        $filename = $this->storage.md5($id);

        if (file_exists($filename)) {
            $this->data = file_get_contents($filename);
        }

        return $this->data ?? '';
    }

    /**
     * @param string $id
     * @param mixed  $data
     *
     * @return bool
     */
    public function write($id, $data)
    {
        $filename = $this->storage.md5($id);

        // check if data has changed since first read
        if ($data !== $this->data) {
            return file_put_contents($filename, $data, LOCK_EX) === false ? false : true;
        } else {
            return touch($filename);
        }
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function destroy($id)
    {
        $filename = $this->storage.md5($id);
        if (file_exists($filename)) {
            unlink($filename);
        }

        return true;
    }

    /**
     * @param int $maxlifetime
     *
     * @return bool
     */
    public function gc($maxlifetime)
    {
        foreach (glob($this->storage) as $filename) {
            if (filemtime($filename) + $maxlifetime < time() && file_exists($filename)) {
                unlink($filename);
            }
        }

        return true;
    }
}
