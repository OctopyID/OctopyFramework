<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : www.octopy.xyz
 * @license : MIT
 */

namespace Octopy\HTTP;

use ArrayObject;
use JsonSerializable;
use UnexpectedValueException;

use Octopy\Support\Macroable;
use Octopy\HTTP\Response\Header;
use Octopy\HTTP\Response\JsonResponse;
use Octopy\HTTP\Response\RedirectResponse;

class Response
{
    use Macroable;

    /**
     * @var Octopy\HTTP\Response\Header;
     */
    protected $header;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var string
     */
    protected $reason;

    /**
     * @var array
     */
    protected $message = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Unused',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m A Teapot',
        419 => 'Authentication Timeout',
        420 => 'Enhance Your Calm',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        424 => 'Method Failure',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        444 => 'No Response',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        451 => 'Unavailable For Legal Reasons',
        494 => 'Request Header Too Large',
        495 => 'Cert Error',
        496 => 'No Cert',
        497 => 'HTTP to HTTPS',
        499 => 'Client Closed Request',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        598 => 'Network Read Timeout Error',
        599 => 'Network Connect Timeout Error'
   ];

    /**
     * @param mixed $data
     * @param int   $status
     * @param array $header
     */
    public function __construct($data = '', $status = 200, $header = [])
    {
        $this->header = new Header($header);
        $this->status = $status;

        try {
            $this->body($data);
        } catch (UnexpectedValueException $exception) {
            throw $exception;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $this->send();
    }

    /**
     * @param  string $property
     * @return mixed
     */
    public function __get(string $property)
    {
        return $this->$property ?? null;
    }

    /**
     * @param  mixed $data
     * @param  int   $status
     * @param  array $header
     * @return Response
     */
    public function make($data, int $status = 200, array $header = [])
    {
        if ($data instanceof Response) {
            return $data;
        }

        if (is_array($data) || $data instanceof ArrayObject || $data instanceof JsonSerializable) {
            return $this->json($data, $status, $header);
        }

        foreach ($header as $key => $value) {
            $this->header($key, $value);
        }

        return $this->status($status)->body($data);
    }

    /**
     * @param  string $body
     * @return $this
     */
    public function body($body)
    {
        if (!is_null($body) && !is_string($body) && !is_numeric($body) && !is_callable([$body, '__toString'])) {
            throw new UnexpectedValueException(
                'The response body must be a string or object implementing __toString() given ' . gettype($body)
            );
        }

        if (is_callable([$body, '__toString'])) {
            $body = call_user_func($body);
        }

        $this->body = $body;

        return $this;
    }

    /**
     * @param  array $data
     * @param  int   $status
     * @param  array $header
     * @param  int   $option
     * @return JsonResponse
     */
    public function json(array $data = [], int $status = 200, array $header = [], int $option = 0)
    {
        return new JsonResponse($data, $status, $header, $option);
    }

    /**
     * @param  string $data
     * @param  int    $status
     * @param  array  $header
     * @return RedirectResponse
     */
    public function redirect(string $data = '/', int $status = 302, array $header = [])
    {
        return new RedirectResponse($data, $status, $header);
    }

    /**
     * @param  int    $status
     * @param  string $reason
     * @return $this
     */
    public function status(int $status, string $reason = null)
    {
        $this->status = $status;
        $this->reason = $reason ?? $this->reason($status);

        return $this;
    }

    /**
     * @param  int $status
     * @return string
     */
    public function reason(int $status) : string
    {
        if ($status < 99 || $status > 599) {
            $status = 500;
        }

        return $this->message[$status] ?? 'Unknown';
    }

    /**
     * @param  array $header
     * @return $this
     */
    public function header(...$header)
    {
        if (isset($header[0]) && is_array($header[0])) {
            foreach ($header[0] as $key => $value) {
                $this->header($key, $value);
            }

            return $this;
        }

        $this->header->set(...$header);
        
        return $this;
    }

    /**
     * @return array
     */
    public function headers() : array
    {
        return $this->header->all();
    }

    /**
     * @return void
     */
    public function send()
    {
        if (!headers_sent()) {
            $status = $this->status;
            $server = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.0';

            foreach ($this->header->all() as $name => $values) {
                foreach ($values as $value) {
                    header($name . ':' . $value, strcasecmp($name, 'Content-Type') === 0, $status);
                }
            }

            header(sprintf('%s %s %s', $server, $status, $this->reason), true, $status);
        }

        echo $this->body;
    }
}
