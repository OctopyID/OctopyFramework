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

use Throwable;
use Octopy\Logger\Handler\BaseHandler;
use Octopy\Logger\Exception\InvalidLogLevelException;

class Logger
{
    /**
     * @var BaseHandler
     */
    protected $handler;

    /**
     * @var array
     */
    protected $level = [
        'emergency' => 1,
        'alert'     => 2,
        'critical'  => 3,
        'error'     => 4,
        'warning'   => 5,
        'notice'    => 6,
        'info'      => 7,
        'debug'     => 8,
    ];

    /**
     * @var array
     */
    protected $loggable;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        if (! $this->handler) {
            $this->handler($app['config']['logger.handler']);
        }

        if (is_array($this->handler->config['threshold'])) {
            $this->loggable = $this->handler->config['threshold'];
        } else {
            $this->loggable = range(1, (int) $this->handler->config['threshold']);
        }

        // Now convert loggable levels to strings.
        // We only use numbers to make the threshold setting convenient for users.
        if ($this->loggable) {
            $temp = [];
            foreach ($this->loggable as $level) {
                $temp[] = array_search((int) $level, $this->level);
            }

            $this->loggable = $temp;
            unset($temp);
        }
    }

    /**
     * @param  string $property
     * @return mixed
     */
    public function __get(string $property)
    {
        return $this->$property ?? null;
    }

    /**
     * @param  string $key
     * @return mixed
     */
    public function config(string $key = null)
    {
        return $this->handler->config($key);
    }

    /**
     * @param  string $default
     * @return BaseHandler
     */
    public function handler(string $default) : BaseHandler
    {
        $handler = [
            'file' => \Octopy\Logger\Handler\FileHandler::class,
        ];

        if (isset($handler[$default])) {
            return $this->handler = Container::make($handler[$default], [
                'handler' => $default,
                'config'  => Container::make('config')->get('logger'),
            ]);
        }
    }

    /**
     * @param  string $message
     * @param  array  $context
     * @return bool
     */
    public function emergency($message, array $context = []) : bool
    {
        return $this->log('emergency', $message, $context);
    }

    /**
     * @param  string $message
     * @param  array  $context
     * @return bool
     */
    public function alert($message, array $context = []) : bool
    {
        return $this->log('alert', $message, $context);
    }

    /**
     * @param  string $message
     * @param  array  $context
     * @return bool
     */
    public function critical($message, array $context = []) : bool
    {
        return $this->log('critical', $message, $context);
    }

    /**
     * @param  string $message
     * @param  array  $context
     * @return bool
     */
    public function error($message, array $context = []) : bool
    {
        return $this->log('error', $message, $context);
    }

    /**
     * @param  string $message
     * @param  array  $context
     * @return bool
     */
    public function warning($message, array $context = []) : bool
    {
        return $this->log('warning', $message, $context);
    }

    /**
     * @param  string $message
     * @param  array  $context
     * @return bool
     */
    public function notice($message, array $context = []) : bool
    {
        return $this->log('notice', $message, $context);
    }

    /**
     * @param  string $message
     * @param  array  $context
     * @return bool
     */
    public function info($message, array $context = []) : bool
    {
        return $this->log('info', $message, $context);
    }

    /**
     * @param  string $message
     * @param  array  $context
     * @return bool
     */
    public function debug($message, array $context = []) : bool
    {
        return $this->log('debug', $message, $context);
    }

    /**
     * @param  string $level
     * @param  string $message
     * @param  array  $context
     * @return bool
     */
    public function log($level, $message, array $context = []) : bool
    {
        if (is_numeric($level)) {
            $level = array_search((int) $level, $this->level);
        }

        // is the level a valid level?
        if (! array_key_exists($level, $this->level)) {
            throw new InvalidLogLevelException();
        }

        // Does the app want to log this right now?
        if (! in_array($level, $this->loggable)) {
            return false;
        }

        $message = $this->interpolate($message, $context);

        if (! is_string($message)) {
            $message = print_r($message, true);
        }

        $this->handler->handle($level, $message);

        return true;
    }

    /**
     * @param  mixed $message
     * @param  array $context
     * @return string
     */
    protected function interpolate($message, array $context)
    {
        if (! is_string($message)) {
            return $message;
        }

        $replace = [];
        foreach ($context as $key => $value) {
            if ($value instanceof Throwable) {
                $value = $value->getMessage() . ' ' . $value->getFile() . ' : ' . $value->getLine();
            }

            // todo - sanitize input before writing to file?
            $replace['{' . $key . '}'] = $value;
        }

        // Add special placeholders
        $replace['{ENV}'] = '$_ENV : ' . print_r($_ENV, true);
        $replace['{GET}'] = '$_GET : ' . print_r($_GET, true);
        $replace['{POST}'] = '$_POST : ' . print_r($_POST, true);

        // Allow us to log the file/line that we are logging from
        if (strpos($message, '{FILE}') !== false) {
            [$file, $line] = $this->determine();

            $replace['{FILE}'] = $file;
            $replace['{LINE}'] = $line;
        }

        // Match up environment variables in {ENV:FOO} tags.
        if (strpos($message, 'ENV:') !== false) {
            preg_match('/ENV:[^}]+/', $message, $matches);

            if ($matches) {
                foreach ($matches as $str) {
                    $replace["{{$str}}"] = $_ENV[str_replace('ENV:', '', $str)] ?? 'n/a';
                }
            }
        }

        if (isset($_SESSION)) {
            $replace['{SESSION}'] = '$_SESSION : ' . print_r($_SESSION, true);
        }

        return strtr($message, $replace);
    }

    /**
     * @return array
     */
    public function determine() : array
    {
        // Determine the file and line by finding the first
        // backtrace that is not part of our logging system.
        $trace = debug_backtrace();
        $file = null;
        $line = null;

        foreach ($trace as $row) {
            if (in_array($row['function'], ['interpolate', 'determineFile', 'log', 'log_message'])) {
                continue;
            }

            $file = $row['file'] ?? isset($row['object']) ? get_class($row['object']) : 'unknown';
            $line = $row['line'] ?? $row['function'] ?? 'unknown';
            break;
        }

        return compact('file', 'line');
    }
}
