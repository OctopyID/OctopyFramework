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

namespace Octopy\Session\Handler;

use SessionHandlerInterface;

class NullSessionHandler implements SessionHandlerInterface
{
    /**
     * @@param  string $storage
     * @@param  string $name
     * @@return bool
     */
    public function open($storage, $name)
    {
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
     * @param  string $id
     * @return mixed
     */
    public function read($id)
    {
        return true;
    }


    /**
     * @param  string $id
     * @param  mixed  $data
     * @return bool
     */
    public function write($id, $data)
    {
        return true;
    }

    /**
     * @param  string $id
     * @return bool
     */
    public function destroy($id)
    {
        return true;
    }

    /**
     * @param  int $maxlifetime
     * @return bool
     */
    public function gc($maxlifetime)
    {
        return true;
    }
}
