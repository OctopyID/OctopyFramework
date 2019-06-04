<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : www.octopy.xyz
 * @license : MIT
 */

namespace Octopy\HTTP\Request;

use RuntimeException;

class Curl
{
    /**
     * @var resource(6) of type (curl)
     */
    protected $curl;

    /**
     * @var array
     */
    protected $retriable = [
        CURLE_COULDNT_CONNECT,
        CURLE_COULDNT_RESOLVE_HOST,
        CURLE_HTTP_NOT_FOUND,
        CURLE_HTTP_POST_ERROR,
        CURLE_OPERATION_TIMEOUTED,
        CURLE_READ_ERROR,
        CURLE_SSL_CONNECT_ERROR,
    ];

    /**
     * @var array
     */
    protected $option = [];

    /**
     * @var string
     */
    protected $response = '';

    /**
     *
     */
    public function __construct()
    {
        if (! $this->curl) {
            $this->init();
        }
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->response;
    }

    /**
     * @param  string $value
     * @return void
     */
    public function init() : void
    {
        if ($this->curl) {
            $this->close();
        }

        $this->curl = curl_init();
    }

    /**
     * @param  int   $option
     * @param  mixed $value
     * @return Curl
     */
    public function option($option, $value = null) : Curl
    {
        if (is_array($option)) {
            foreach ($option as $key => $value) {
                $this->option($key, $value);
            }
        } else {
            $this->option[$option] = $value;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * @param  string $url
     * @param  bool   $header
     * @return Curl
     */
    public function get(string $url, bool $header = false) : Curl
    {
        return $this->option([
            CURLOPT_HEADER => $header,
            CURLOPT_POST   => false,
            CURLOPT_URL    => $url,
        ]);
    }

    /**
     * @param  string $url
     * @param  array  $header
     * @return Curl
     */
    public function post(string $url, array $data = [], bool $header = false) : Curl
    {
        return $this->option([
            CURLOPT_HEADER     => $header,
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_URL        => $url,
        ]);
    }

    /**
     * @param  int  $retry
     * @param  bool $close
     * @return Curl
     */
    public function execute(int $retry = 5, bool $close = true) : Curl
    {
        curl_setopt_array($this->curl, $this->option);

        while ($retry--) {
            if ($this->response = curl_exec($this->curl) === false) {
                $errno = curl_errno($this->curl);

                if (false === in_array($errno, $this->retriable, true) || ! $retry) {
                    $error = curl_error($this->curl);

                    if ($close) {
                        $this->close();
                    }

                    throw new RuntimeException("Curl error (code $errno) : $error");
                }

                continue;
            }

            if ($close) {
                $this->close();
            }

            break;
        }

        return $this;
    }

    /**
     * @return Curl
     */
    public function close() : Curl
    {
        if ($this->curl) {
            curl_close($this->curl);
            $this->curl = null;
        }

        return $this;
    }
}
