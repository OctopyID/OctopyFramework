<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author :Supian M <supianidz@gmail.com>
 * @link   :framework.octopy.id
 * @license:MIT
 */

namespace Octopy\Exception;

use Exception;
use Octopy\Application;
use Octopy\HTTP\Request;
use Octopy\Console\Output\Color;

abstract class ExceptionHandler
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param Exception $exception
     */
    public function report(Exception $exception)
    {
        $logger = $this->app['logger'];
        $config = $this->app['config']['exception'];

        $threshold = $logger->handler->config['threshold'];
        if ($config['log'] && ! in_array($exception->getCode(), $config['ignored'])) {
            $logger->log($threshold, $exception->getMessage() . "\n[StackTrace]\n{TRACE}\n", [
                'TRACE' => $exception->getTraceAsString(),
            ]);
        }
    }

    /**
     * @param Exception $exception
     */
    public function console(Exception $exception)
    {
        $vars = $this->vars($exception);

        $color = new Color();

        $output = "\n";
        $output .= $color->apply('b:red', ' ' . $vars['exception'] . ' ');
        $output .= $color->apply('c:white', ' : ');
        $output .= $color->apply('c:yellow', $vars['message']);
        $output .= "\n\n";
        $output .= $color->apply('c:lightgray', 'at ');
        $output .= $color->apply('c:green', $vars['file']);
        $output .= $color->apply('c:lightgray', ' on line ');
        $output .= $color->apply('c:green', $vars['line']);
        $output .= "\n";

        if (is_file($vars['file']) && is_readable($vars['file'])) {
            $output .= $this->app['syntax']->highlight($vars['file'], $vars['line'], 2, 2);
            $output .= "\n";
        }

        if (! empty($vars['trace'])) {
            $output .= $color->apply('c:red', 'Stacktrace :');
            $output .= "\n";

            foreach ($vars['trace'] as $no => $trace) {
                $no++;
                $output .= $color->apply('c:lightgray', " $no. ");

                if (isset($trace['file'])) {
                    $output .= $color->apply('c:green', $trace['file']);
                    $output .= $color->apply('c:lightgray', ' ');
                    $output .= $color->apply('c:lightgray', $trace['line']);
                } elseif (isset($trace['class'])) {
                    $output .= $color->apply('c:green', $trace['class']);
                    $output .= $color->apply('c:lightgray', '::');
                    $output .= $color->apply('c:lightgray', $trace['function']);
                    $output .= $color->apply('c:lightgray', '(...)');
                }

                $output .= "\n";
            }
        } else {
            $output .= "\n";
        }

        die($output);
    }

    /**
     * @param  Request   $request
     * @param  Exception $exception
     * @return Response
     */
    public function render(Request $request, Exception $exception)
    {
        $vars = $this->vars($exception);

        if ($request->ajax()) {
            return [
                'code'      => $vars['code'],
                'message'   => $vars['message'],
                'exception' => $vars['exception'],
            ];
        }

        return $this->view($this->app->debug() ? 'debug' : 'error', $vars);
    }

    /**
     * @param  Exception $exception
     * @return array
     */
    private function vars(Exception $exception) : array
    {
        $code = $exception->getCode();

        if ($code < 100 || $code > 599) {
            $code = 500;
        }

        return [
            'code'      => $code,
            'file'      => $exception->getFile(),
            'line'      => $exception->getLine(),
            'trace'     => $exception->getTrace(),
            'message'   => head(explode("\n", $exception->getMessage())),
            'exception' => last(explode(BS, get_class($exception))),
        ];
    }

    /**
     * @param  string $name
     * @param  array  $vars
     * @return string
     */
    private function view(string $name, array $vars = []) : string
    {
        $view = $this->app->resolve('view', [
            'resource' => __DIR__ . '/View/',
        ]);

        return $view->render($name, array_merge($vars, [
            'app' => $this->app,
        ]));
    }
}
