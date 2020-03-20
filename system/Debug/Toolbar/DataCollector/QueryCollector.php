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

use Exception;

class QueryCollector extends Collector
{
    /**
     * @var string
     */
    public $name = 'Query';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @return array
     */
    public function collect()
    {
        try {
            return $this->app->database->queries();
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @return string
     */
    public function icon() : string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAMAAADXqc3KAAABO1BMVEUAAAA0SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV6dfK01AAAAaHRSTlMAAQIDBAUGBwgJCwwNEBESExQWGBobHB0fICEjJCUnKCorLC4vMDEyNTY4OkJDRkdKS0xVVldbXV9hY2drcXR1d3+LjI6PkZKVnZ6ipqirr7S3urzDyMrMztHX2dzg4ubo7e/z9ff5/cxGgDsAAAD/SURBVBgZbcEJNwJhGAbQ5y3T2LNkySB8ZE1R9qWsg1CJshRKmuf//wJ11KHOdy/qjED0NF0oOZWPfDIR8gl+LWfYyrn0A3BnqXEOhKllIUYthRi1FCLUmoLxTI07AWQtxzbpIIBOAF3B/etchTXlx6vtGQ8gXpzMoUkEDeatwj0zKyb+k9FElQqvrHk721qdHh8ZsxYiR2mHNQrv1LIQqFLDFohnp8hWjj0BA1ED6Fncsx+KZef7M5+Mh3wCbCrclDY8aCVWigpPJLO78wOmC4B09PrXL75IKpSpNYtD6hQNYOmF7arHBur6w3aBTZXUwaQLf8TsGxwe8na70fADA0edTV3bqnEAAAAASUVORK5CYII=';
    }
}
