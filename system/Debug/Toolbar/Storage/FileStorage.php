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

namespace Octopy\Debug\Toolbar\Storage;

use Octopy\Debug\Toolbar\Storage;

class FileStorage extends Storage
{
    /**
     * @return void
     */
    public function write()
    {
        $content = $this->content();
        $storage = $this->app->config->get('toolbar.storage', 'storage');
        $history = $this->app->toolbar->time() . '.json';

        if ($this->app->filesystem->mkdir($storage)) {
            $this->app->filesystem->put($storage . $history, $content);
        }
    }
}
