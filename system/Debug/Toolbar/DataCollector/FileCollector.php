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

class FileCollector extends Collector
{
    /**
     * @var string
     */
    public $name = 'Files';

    /**
     * @var boolean
     */
    public $badge = true;

    /**
     * @return array
     */
    public function collect()
    {
        return get_included_files();
    }

    /**
     * @return int
     */
    public function badge() : int
    {
        return count(get_included_files());
    }

    /**
     * @return string
     */
    public function icon() : string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAMAAADXqc3KAAAAflBMVEUAAAA0SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV40SV7SQDzzAAAAKXRSTlMAAQQFBgcKDg8QFxofJSgrLS8xPUBGUHd7foWRnq2vwcPH2uLr7e/19wBvAYgAAACuSURBVCiRjdHJEoIwEATQFhcIiAoZo4KKiAv9/z/oAZQEcrBPqbyqZGoaCOmkVuijCStkm/ZHccEUzLwgODD1A4pWeaAUMayncOtm20xgeHAMSR54wZBN4EC8BQA0JHMHqrcCgAvJxAYh7wGA1aUx9h/zF8nrbDrViSR57K6XA8T9zvcAFmcZoPq2scbuwQGyX03PkrRA3BL/ACVOVAfat10NIKInIQBEWkbRIfABDgEkhSklNigAAAAASUVORK5CYII=';
    }
}
