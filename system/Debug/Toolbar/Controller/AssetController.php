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

namespace Octopy\Debug\Toolbar\Controller;

use Octopy\HTTP\Response;

class AssetController
{
    /**
     * @param  Response $response
     * @return Response
     */
    public function stylesheet(Response $response)
    {
        return $response->make($this->content('toolbar.css'), 200, [
            'Content-Type' => 'text/css',
        ]);
    }

    /**
     * @param  string   $filename
     * @param  Response $response
     * @return Response
     */
    public function javascript($filename, Response $response)
    {
        $content = $this->content($filename);
        if ($filename === 'octopy.js') {
            $content = str_replace('{{ ROUTE }}', route('toolbar.detail', ''), $content);
        }

        return $response->make($content, 200, [
            'Content-Type' => 'text/javascript',
        ]);
    }

    /**
     * @param  string $filename
     * @return string
     */
    protected function content(string $filename) : string
    {
        $filename = __DIR__ . '/../Asset/' . $filename;

        if (! file_exists($filename)) {
            return '';
        }

        return file_get_contents($filename);
    }
}
