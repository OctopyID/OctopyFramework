<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

namespace Octopy\Debug\Toolbar\DataCollector;

class VarsCollector extends Collector
{
    /**
     * @var string
     */
    public $name = 'Vars';

    /**
     * @var boolean
     */
    public $badge = false;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @return array
     */
    public function collect()
    {
        return [
            'session'  => $this->app->session->all(),
            'request'  => [
                'input'  => $this->app->request->all(),
                'header' => $this->app->request->header(),
                'cookie' => $this->app->request->cookie(),
            ],
            'response' => $this->response(),
        ];
    }

    /**
     * @return string
     */
    public function icon() : string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYAgMAAACdGdVrAAAACVBMVEUAAAA0SV40SV7ssyshAAAAAnRSTlMAQABPjKgAAAAiSURBVAhbY1i1gAEEVi0QDQ0NBVJaq1atQlBQQQacgF7aARFEKp3Ixp/5AAAAAElFTkSuQmCC';
    }

    /**
     * @return array
     */
    private function response() : array
    {
        $data = [];
        foreach ($this->app->response->headers() as $key => $value) {
            $data[$key] = implode(';', $value);
        }

        return $data;
    }
}
