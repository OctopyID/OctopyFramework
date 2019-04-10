<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author :Supian M <supianidz@gmail.com>
 * @link   :www.octopy.xyz
 * @license:MIT
 */

namespace Octopy\Exception;

use Throwable;
use Octopy\Log\Logger;
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
    }

    /**
     * @param Throwable $exception
     */
    public function console(Throwable $exception)
    {
        $vars = $this->vars($exception);

        $color = new Color;

        $output  = PHP_EOL;
        $output .= $color->apply('bg_red', $vars['exception']);
        $output .= $color->apply('white', ' : ');
        $output .= $color->apply('yellow', $vars['message']);
        $output .= PHP_EOL;
        $output .= PHP_EOL;
        $output .= $color->apply('light_gray', 'at ');
        $output .= $color->apply('green', $vars['file']);
        $output .= $color->apply('light_gray', ' on line ');
        $output .= $color->apply('green', $vars['line']);
        $output .= PHP_EOL;

        if (is_file($vars['file']) && is_readable($vars['file'])) {
            $output .= $this->app['syntax']->highlight($vars['file'], $vars['line'], 3, 3);
            $output .= PHP_EOL;
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
            return array(
                'message'   => $vars['message'],
                'exception' => $vars['exception'],
            );
        }

        return $this->view($this->app->debug() ? 'debug':'error', $vars);
    }

    /**
     * @param  Throwable $exception
     * @return array
     */
    private function vars(Throwable $exception):array
    {
        return array('code' => $exception->getCode(), 'file' => $exception->getFile(), 'line' => $exception->getLine(), 'message' => $exception->getMessage(), 'exception' => last(explode(BS, get_class($exception))), 'trace' => $exception->getTrace(),
        );
    }

    /**
     * @param  string $name
     * @param  array  $vars
     * @return string
     */
    private function view(string $name, array $vars = []) :string
    {
        $view = $this->app->resolve('view', ['resource' => sprintf('%s/View/', __DIR__)]);

        return $view->render($name, array_merge($vars, [
            'app' => $this->app
        ]));
    }
}
