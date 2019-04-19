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

namespace Octopy\Mailer;

use Exception;
use Octopy\Mailer\Exception\SMTPConnectionException;
use Octopy\Mailer\Exception\SMTPAuthorizationException;
use Octopy\Mailer\Exception\ErrorSendingCommandException;

class Socket
{
    /**
     * @var socket
     */
    protected $sock;

    /**
     * @var array
     */
    protected $auth;

    /**
     * @param string $host
     * @param int    $port
     * @param array  $auth
     */
    public function __construct(string $host, int $port, array $auth)
    {
        $this->auth = $auth;

        try {
            $this->sock = fsockopen($host, $port, $errno, $errstr);
        } catch (Exception $exception) {
            throw new SMTPConnectionException($exception->getMessage());
        }
    }

    /**
     * @param  array $recepient
     * @return array
     */
    public function close(string $content, array $recepient)
    {
        if (! $this->parse($this->sock, 220)) {
            throw new SMTPConnectionException;
        }

        $server = $_SERVER['SERVER_NAME'];

        fputs($this->sock, "EHLO $server\r\n");
        if (! $this->parse($this->sock, 250)) {
            fputs($this->sock, "HELO $server\r\n");
            if (! $this->parse($this->sock, 250)) {
                fclose($this->sock);
                throw new ErrorSendingCommandException;
            }
        }

        fputs($this->sock, "AUTH LOGIN\r\n");
        if (! $this->parse($this->sock, 334)) {
            fclose($this->sock);
            throw new SMTPAuthorizationException;
        }

        fputs($this->sock, base64_encode($this->auth['username']) . "\r\n");
        if (! $this->parse($this->sock, 334)) {
            fclose($this->sock);
            throw new SMTPAuthorizationException;
        }

        fputs($this->sock, base64_encode($this->auth['password']) . "\r\n");
        if (! $this->parse($this->sock, 235)) {
            fclose($this->sock);
            throw new SMTPAuthorizationException;
        }

        fputs($this->sock, 'MAIL FROM: <' . $this->auth['username'] . ">\r\n");
        if (! $this->parse($this->sock, 250)) {
            fclose($this->sock);
            throw new ErrorSendingCommandException;
        }

        foreach ($recepient as $email) {
            fputs($this->sock, "RCPT TO: <{$email}>\r\n");
            if (! $this->parse($this->sock, 250)) {
                fclose($this->sock);
                throw new ErrorSendingCommandException;
            }
        }

        fputs($this->sock, "DATA\r\n");
        if (! $this->parse($this->sock, 354)) {
            fclose($this->sock);
            throw new ErrorSendingCommandException;
        }

        fputs($this->sock, $content . "\r\n.\r\n");
        if (! $this->parse($this->sock, 250)) {
            fclose($this->sock);
            throw new FailedSendingEmailException;
        }

        fputs($this->sock, "QUIT\r\n");

        return fclose($this->sock);
    }

    /**
     * @param  resource $sock
     * @param  int      $response
     * @return bool
     */
    private function parse($sock, int $response)
    {
        $search = '';
        while (substr($search, 3, 1) != ' ') {
            if (! ($search = fgets($sock, 256))) {
                return false;
            }
        }

        if (substr($search, 0, 3) !== $response) {
            return false;
        }

        return true;
    }
}
