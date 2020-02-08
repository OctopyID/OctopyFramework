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

class ViewCollector extends Collector
{
    /**
     * @var string
     */
    public $name = 'Views';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @return array
     */
    public function collect()
    {
        if (empty($this->data)) {
            foreach ($this->app->view->template() as $file) {
                $this->data[] = $file->template();
            }
        }

        return $this->data;
    }

    /**
     * @return string
     */
    public function icon() : string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAMAAADXqc3KAAAA4VBMVEUAAAA0SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV7oubu7AAAASnRSTlMAAQQFBgcIDA0ODxAREhQYGhwdHh8nKSorLS4vMjM2PT9AQkNLTVhcXV5hYmhxdXl7f4CDhpebnaKjsre8wcPIyszc5Obr7e/z9QlAyiEAAADWSURBVBgZbcGJNkJRAEDR81Sm0ICiDBHJlHkuKiTn/z+IW0u9lfaGPaeoABtOUQSyTpEFFpxiHkioRWLKagKI1AoxB2rEr57W4Pjlz7t+ErxqA86MaRE86B0cGnNPcKlNYjp6QVDXD2K+9YigqjIWqfsEJWPmSKqbBDl1LbnkwAxpNUeQVjPgAOTVRYKkWoD18263UYCSmiSI1F1GqmrEQF+fywkGZnea2meobdCqZbL1tsEbQ09OeGTo2glXDEWrpx1H2if5iLHU9u2X9m62Uvy3sszYD18TV3GUlSYLAAAAAElFTkSuQmCC';
    }
}
