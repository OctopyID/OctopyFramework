<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 *
 * @author  : Supian M <supianidz@gmail.com>
 *
 * @link    : www.octopy.xyz
 *
 * @license : MIT
 */

namespace Octopy\Console;

use Exception;
use Octopy\Application;

abstract class Command
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var string
     */
    protected $signature = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function __get(string $property)
    {
        return $this->$property ?? null;
    }

    /**
     * @param string $string
     *
     * @return array
     */
    protected function parse(string $string) : array
    {
        preg_match('/(.*)\/(.*)/', trim($string, '/'), $match);

        return [
            'location'  => $string.'.php',
            'classname' => $match[2] ?? $string,
            'namespace' => str_replace('/', '\\', $match[1] ?? null),
        ];
    }

    /**
     * @param string $location
     * @param string $stub
     * @param array  $data
     *
     * @return bool
     */
    protected function generate(string $location, string $stub, array $data = [])
    {
        try {
            $content = $this->app['filesystem']->get(
                sprintf('%s/stub/%s.stub', __DIR__, $stub)
            );
        } catch (Exception $exception) {
            throw $exception;
        }

        if (!empty($data)) {
            if (isset($data['DummyNameSpace']) && $data['DummyNameSpace'] == null) {
                unset($data['DummyNameSpace']);
                $data['\DummyNameSpace'] = '';
            }

            $content = str_replace(array_keys($data), array_values($data), $content);
        }

        try {
            if (!is_dir($basedir = dirname($location))) {
                $this->app['filesystem']->mkdir($basedir, 0755, true);
            }

            return $this->app['filesystem']->put($location, $content);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $command
     *
     * @return string
     */
    protected function call(string $command, array $option = [])
    {
        if ($this->app['console']->has($command)) {
            $this->app['argv']->option($option);

            try {
                return $this->app['console']->call($command);
            } catch (Exception $exception) {
                return $exception->getMessage();
            }
        }
    }
}
