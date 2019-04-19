<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @link    : www.octopy.xyz
 * @author  : Supian M <supianidz@gmail.com>
 * @license : MIT
 */

namespace Octopy\Bootstrap;

use Exception;
use Throwable;
use ErrorException;
use Octopy\Application;
use App\Exception\Handler;

class RegisterExceptionHandler
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var App\Exception\Handler
     */
    protected $handler;

    /**
     * @param Handler $handler
     */
    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param  Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $this->app = $app;

        error_reporting(-1);

        set_error_handler([$this, 'error']);

        set_exception_handler([$this, 'exception']);

        register_shutdown_function([$this, 'shutdown']);

        if (! $app->env('testing')) {
            ini_set('display_errors', 'Off');
        }
    }

    /**
     * @param  Throwable  $exception
     * @return void
     */
    public function exception(Throwable $exception)
    {
        if (! $exception instanceof Exception) {
            $exception = new ErrorException($exception);
        }

        try {
            $this->handler->report($exception);
        } catch (Exception $exception) {
        }

        if ($this->app->console()) {
            $this->handler->console($exception);
        } else {
            try {
                ob_clean();
                $response = $this->handler->render($this->app['request'], $exception);
            } catch (Throwable $exception) {
                $response = $exception->getMessage();
            }

            $this->app['response']->make($response, $exception->getCode())->send();
        }
    }

    /**
     * @param int    $level
     * @param string $message
     * @param string $file
     * @param int    $line
     */
    public function error(int $level, string $message, string $file = '', int $line = 0)
    {
        if (error_reporting() & $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     *
     */
    public function shutdown()
    {
        $type = [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR, E_PARSE];
        if (! is_null($error = error_get_last()) && in_array($error['type'], $type)) {
            $this->exception(new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
        }
    }
}
