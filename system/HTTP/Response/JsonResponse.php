<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @link    : www.octopy.xyz
 * @author  : Supian M <supianidz@gmail.com>
 * @license : MIT
 */

namespace Octopy\HTTP\Response;

use Exception;
use InvalidArgumentException;

use Octopy\HTTP\Response;

class JsonResponse extends Response
{
    /**
     * @var int
     */
    const DEFAULT_ENCODING = 15;

    /**
     * @param  mixed $data
     * @param  int   $status
     * @param  array $header
     * @param  int   $option
     */
    public function __construct($data = [], $status = 200, array $header = [], $option = 0)
    {
        parent::__construct('', $status, $header);

        $data = $this->encode($data, $option);
        if ($this->body($data)) {
            $this->header->set('Content-Type', 'application/json; charset=UTF-8', true);
        }
    }

    /**
     * @param  array $data
     * @param  int   $option
     * @return string
     */
    protected function encode($data, $option) : string
    {
        try {
            $data = json_encode($data, $option);
            if (!$this->validate(json_last_error(), $option)) {
                throw new InvalidArgumentException(json_last_error_msg());
            }
        } catch (Exception $exception) {
            if (get_class($exception) == 'Exception' && strpos($exception->getMessage(), 'Failed calling ') == 0) {
                throw $exception->getPrevious() ? : $exception;
            }
         
            throw $exception;
        }

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException(json_last_error_msg());
        }

        return $data;
    }

    /**
     * @param  int $error
     * @param  int $option
     * @return bool
     */
    protected function validate($error, $option) : bool
    {
        if ($error === JSON_ERROR_NONE) {
            return true;
        }

        return ($option & JSON_PARTIAL_OUTPUT_ON_ERROR) && in_array($error, [
            JSON_ERROR_RECURSION,
            JSON_ERROR_INF_OR_NAN,
            JSON_ERROR_UNSUPPORTED_TYPE,
       ]);
    }
}
