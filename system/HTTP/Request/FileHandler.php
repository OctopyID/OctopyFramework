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

namespace Octopy\HTTP\Request;

use Exception;

class FileHandler
{
    /**
     * @var array
     */
    protected $file;

    /**
     * @param array $file
     */
    public function __construct(array $file)
    {
        $this->file = $file;
    }

    /**
    * @return string
    */
    public function name() : string
    {
        return $this->file['name'];
    }

    /**
     * @return string
     */
    public function type() : string
    {
        return $this->file['type'];
    }

    /**
     * @return int
     */
    public function size() : int
    {
        return $this->file['size'];
    }

    /**
     * @return int
     */
    public function error() : int
    {
        return $this->file['error'];
    }

    /**
     * @param  string $destination
     * @return bool
     */
    public function move(string $destination = null, bool $replace = false) : bool
    {
        if ($this->error() > 0) {
            return false;
        }
        
        if (is_null($destination)) {
            $destination = $this->name();
        }

        if (!$replace && file_exists($destination)) {
            return true;
        }

        try {
            return move_uploaded_file($this->file['tmp_name'], $destination);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
