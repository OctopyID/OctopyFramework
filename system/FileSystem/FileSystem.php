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

use FilesystemIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Octopy\FileSystem\Exception\FileNotFoundException;

class FileSystem
{
    /**
     * @param  string $path
     * @return string
     */
    public function hash($path)
    {
        return md5_file($path);
    }

    /**
     * @param  string $path
     * @param  bool   $lock
     * @return string
     */
    public function get($path, $lock = false)
    {
        if (is_file($path)) {
            if ($lock == true) {
                $handle = fopen($path, 'rb');
                $content = '';

                if ($handle) {
                    try {
                        if (flock($handle, LOCK_SH)) {
                            clearstatcache(true, $path);

                            $content = fread($handle, $this->size($path) ?: 1);

                            flock($handle, LOCK_UN);
                        }
                    } finally {
                        fclose($handle);
                    }
                }

                return $content;
            }

            return file_get_contents($path);
        }

        throw new FileNotFoundException("File does not exist at path {$path}");
    }

    /**
     * @param  string $path
     * @param  string $content
     * @param  bool   $lock
     * @return int
     */
    public function put(string $path, string $content, bool $lock = false)
    {
        return file_put_contents($path, $content, $lock ? LOCK_EX : 0);
    }

    /**
     * @param string $path
     * @param string $content
     */
    public function replace(string $path, $content)
    {
        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;

        $temp = tempnam(dirname($path), basename($path));

        $this->chmod($temp, 0777 - umask());

        $this->put($temp, $content);

        $this->move($temp, $path);
    }

    /**
     * @param  string $path
     * @param  string $data
     * @return int
     */
    public function prepend($path, $data)
    {
        if (file_exists($path)) {
            $data .= $this->get($path);
        }

        return $this->put($path, $data);
    }

    /**
     * @param  string $path
     * @param  string $data
     * @return int
     */
    public function append($path, $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }

    /**
     * @param  string $path
     * @param  octal  $permission
     * @return mixed
     */
    public function chmod(string $path, int $permission = null)
    {
        if (! is_null($permission)) {
            return chmod($path, $permission);
        }

        return substr(sprintf('%o', fileperms($path)), -4);
    }

    /**
     * @param  string $path
     * @param  bool   $preserve
     * @return bool
     */
    public function delete(string $path, bool $preserve = false)
    {
        if (is_file($path)) {
            return @unlink($path);
        } elseif (is_dir($path)) {
            $items = new FilesystemIterator($path);

            foreach ($items as $item) {
                if ($item->isFile()) {
                    $status = $this->delete($item);
                } elseif ($item->isDir() && ! $item->isLink()) {
                    if ($this->delete($item)) {
                        $status = @rmdir($item);
                    }
                }
            }

            if ($preserve) {
                @rmdir($path);
            }

            return $status;
        }
    }

    /**
     * @param  string $path
     * @param  string $target
     * @return bool
     */
    public function move($path, $target)
    {
        return rename($path, $target);
    }

    /**
     * @param  string $directory
     * @param  string $destination
     * @param  int    $flag
     * @return bool
     */
    public function copy(string $directory, string $destination, int $flag = null)
    {
        if (! is_dir($directory) && ! is_file($directory)) {
            return false;
        }

        if (is_dir($directory)) {
            $flag = $flag ?: FilesystemIterator::SKIP_DOTS;

            if (! is_dir($destination)) {
                $this->mkdir($destination, 0755, true);
            }

            $items = new FilesystemIterator($directory, $flag);

            foreach ($items as $item) {
                $target = $destination . '/' . $item->getBasename();

                if ($item->isDir()) {
                    if (! $this->copy($item->getPathname(), $target, $flag)) {
                        return false;
                    }
                } else {
                    if (! $this->copy($item->getPathname(), $target, $flag)) {
                        return false;
                    }
                }
            }

            return true;
        }

        return @copy($directory, $destination);
    }

    /**
     * @param  string $path
     * @param  int    $permission
     * @param  bool   $recursive
     * @return bool
     */
    public function mkdir(string $path, int $permission = 0755, bool $recursive = true)
    {
        if (is_dir($path) || is_file($path)) {
            return true;
        }

        return @mkdir($path, $permission, $recursive);
    }

    /**
     * @param  string $path
     * @param  int    $flag
     * @return RecursiveIteratorIterator
     */
    public function iterator(string $path, int $flag = null)
    {
        if (! is_dir($path)) {
            return [];
        }

        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, $flag ?? RecursiveDirectoryIterator::SKIP_DOTS)
        );
    }
}
