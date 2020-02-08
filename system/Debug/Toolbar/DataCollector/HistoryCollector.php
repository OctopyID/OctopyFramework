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

class HistoryCollector extends Collector
{
    /**
     * @var string
     */
    public $name = 'History';

    /**
     * @var array
     */
    protected $files = [];

    /**
     * @return array
     */
    public function collect()
    {
        if (empty($this->files)) {
            $histories = array_reverse(
                glob($this->app->config->get('toolbar.storage', 'storage') . '*.json')
            );

            $count = 0;
            $limit = $this->app->config['toolbar.history'];

            foreach ($histories as $history) {
                $count++;

                // oldest files will be deleted
                if ($limit >= 0 && $count > $limit) {
                    unlink($history);
                    continue;
                }

                $this->files[] = json_decode(file_get_contents($history));
            }
        }

        return $this->files;
    }

    /**
     * @return int
     */
    public function badge() : int
    {
        if (empty($this->files)) {
            $this->collect();
        }

        return count($this->files);
    }

    /**
     * @return string
     */
    public function icon() : string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAMAAADXqc3KAAAAZlBMVEUAAAA0SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV7HMclgAAAAIXRSTlMAAQIDBAYREhcYJyotL0BcX2FieYOGi5GXur7A6On5+/3xbMRYAAAA2klEQVQoz22S2ZaCMBBEK0JENmEcFoGw3P//SR8EEp2px7p9epfeiopmcG5oikihomph11JdvJ86Arnb4Zcb0OfWGJv3wFbu8RtMyRGVTLDdJCly8DQ+r3mCu0iqYTKSdIe7JJkJKile4aoQKIElUgmdPoF6KNRC9g1yaDSC/QYWBs1gTvC7NwYuACXw8MCn0uMgFoaguCc5NEG7O0n3dv2Ab3I/BlQNo19Veq7k/yVGkpQC45ntOsGW+gHoMmuMzbrgUH9OmwbP8LMe9lp//klctuM8j20Z78YL7BAc51Z72jsAAAAASUVORK5CYII=';
    }
}
