<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author :Supian M <supianidz@gmail.com>
 * @link   :www.octopy.xyz
 * @license:MIT
 */

namespace Octopy\Exception;

use Throwable;
use Octopy\Application;
use Octopy\HTTP\Request;
use Octopy\Console\Output\Color;

class ExceptionHandler
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
     * @param Throwable $exception
     */
    public function report(Throwable $exception)
    {
        //
    }

    /**
     * @param Throwable $exception
     */
    public function console(Throwable $exception)
    {
        $vars = $this->vars($exception);

        $color = new Color;

        $output = "\n";
        $output .= $color->apply('bg_red', ' ' . $vars['exception'] . ' ');
        $output .= $color->apply('white', ' : ');
        $output .= $color->apply('yellow', $vars['message']);
        $output .= "\n";
        $output .= "\n";
        $output .= $color->apply('light_gray', 'at ');
        $output .= $color->apply('green', $vars['file']);
        $output .= $color->apply('light_gray', ' on line ');
        $output .= $color->apply('green', $vars['line']);
        $output .= "\n";

        if (is_file($vars['file']) && is_readable($vars['file'])) {
            $output .= $this->app['syntax']->highlight($vars['file'], $vars['line'], 3, 3);
            $output .= "\n";
        }

        die($output);
    }

    /**
     * @param  Request   $request
     * @param  Throwable $exception
     * @return Response
     */
    public function render(Request $request, Throwable $exception)
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
     * @param  Throwable $exception
     * @return array
     */
    private function vars(Throwable $exception):array
    {
        $code = $exception->getCode();

        if ($code < 100 || $code > 500) {
            $code = 500;
        }

        return [
            'code'      => $code,
            'file'      => $exception->getFile(),
            'line'      => $exception->getLine(),
            'trace'     => $exception->getTrace(),
            'message'   => $exception->getMessage(),
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
        $view = $this->app->resolve('view', ['resource' => sprintf('%s/View/', __DIR__)]);

        return $view->render($name, array_merge($vars, [
            'app' => $this->app,
        ]));
    }
}
