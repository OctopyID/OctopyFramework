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

namespace Octopy\Logger\Handler;

use Octopy\Support\Arr;
use Octopy\Logger\Exception\InvalidLogPathException;

class FileHandler extends BaseHandler
{
    /**
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function config(string $key = null, $default = null)
    {
        return Arr::get($this->config, 'configuration.file.' . $key, $default);
    }

    /**
     * @param  mixed  $level
     * @param  string $message
     * @return bool
     */
    public function handle($level, $message) : bool
    {
        if (empty($filepath = $this->config('filepath'))) {
            throw new InvalidLogPathException();
        }

        if (! is_file($filepath)) {
            $fresh = true;

            if (! is_dir($directory = dirname($filepath))) {
                mkdir($directory, 0755, true);
            }
        }

        if (! $fp = @fopen($filepath, 'ab')) {
            return false;
        }

        $message = sprintf("[%s] %s --> %s \n", mb_strtoupper($level), $this->datetime, $message);

        flock($fp, LOCK_EX);

        for ($written = 0, $length = strlen($message); $written < $length; $written += $result) {
            if (($result = fwrite($fp, substr($message, $written))) === false) {
                // if we get this far, we'll never see this during travis-ci
                break;
            }
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        if (isset($fresh) && $fresh === true) {
            chmod($filepath, $this->config('permission') ?? 0644);
        }

        return is_int($result);
    }
}
