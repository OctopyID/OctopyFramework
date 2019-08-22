<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

namespace Octopy\HTTP\Response;

use Exception;
use Octopy\HTTP\Response;
use Octopy\FileSystem\Exception\FileNotFoundException;

class DownloadResponse extends Response
{
    /**
     * @param string $filepath
     * @param string $filename
     * @param string $disposition
     */
    public function __construct(string $filepath, string $filename = null, string $disposition = 'attachment')
    {
        $content = $this->readfile($filepath);

        if (! $filename) {
            $filename = last(explode(DS, $filepath));
        }

        parent::__construct($content, 200, [
            'Cache-Control'       => 'must-revalidate',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => sprintf('%s; filename="%s"', $disposition, $filename),
            'Content-Length'      => filesize($filepath),
            'Content-Type'        => mime_content_type($filepath),
            'Expires'             => 0,
            'Pragma'              => 'public',
        ]);
    }

    /**
     * @param  string $filepath
     * @return string
     */
    public function readfile(string $filepath) : string
    {
        if (file_exists($filepath)) {
            try {
                return file_get_contents($filepath);
            } catch (Exception $exception) {
                throw $exception;
            }
        }

        throw new FileNotFoundException();
    }
}
