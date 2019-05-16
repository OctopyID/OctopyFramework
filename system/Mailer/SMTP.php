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

use Octopy\Application;
use Octopy\Mailer\Exception\InvalidRecepientException;
use Octopy\Mailer\Exception\AttachmentNotExistException;

class SMTP
{
    /**
     * @var array
     */
    protected $from = [];

    /**
     * @var array
     */
    protected $recepient = [];

    /**
     * @var string
     */
    protected $subject = 'No Subject';

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $boundary;

    /**
     * @var string
     */
    protected $multipart;

    /**
     * @var bool
     */
    protected $attachment = false;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->socket = $app->make(Socket::class, $app->config['mail']);

        $this->boundary = '--' . md5(uniqid(time()));
    }

    /**
     * @param  string $name
     * @param  string $email
     * @return Mailer
     */
    public function from(string $name, string $email)
    {
        $this->from = [$name, $this->email($email)];

        return $this;
    }

    /**
     * @param  array $email
     * @return Mailer
     */
    public function recepient($email)
    {
        if (is_array($email)) {
            foreach ($email as $address) {
                $this->recepient($address);
            }

            return $this;
        }

        $this->recepient[] = $this->email($email);

        return $this;
    }

    /**
     * @param  string $subject
     * @return Mailer
     */
    public function subject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @param  string $message
     * @return Mailer
     */
    public function message(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param  string $path
     * @return Mailer
     */
    public function attachment(string $path)
    {
        if (! is_file($path)) {
            throw new AttachmentNotExistException;
        }

        $fopen = fopen($path, 'rb');
        $data = fread($fopen, filesize($path));
        fclose($fopen);

        $filename = basename($path);

        $multipart = "\r\n--{$this->boundary}\r\n";
        $multipart .= "Content-Type: application/octet-stream; name=\"{$filename}\"\r\n";
        $multipart .= "Content-Transfer-Encoding: base64\r\n";
        $multipart .= "Content-Disposition: attachment; filename=\"{$filename}\"\r\n";
        $multipart .= "\r\n";
        $multipart .= chunk_split(base64_encode($data));

        $this->multipart .= $multipart;
        $this->attachment = true;

        return $this;
    }

    /**
     * @return
     */
    public function send()
    {
        return $this->socket->close($this->build(), $this->recepient);
    }

    /**
     * @param  string $email
     * @return string
     */
    private function email(string $email)
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidRecepientException;
        }

        return $email;
    }

    /**
     * @return string
     */
    private function build()
    {
        $headers = "MIME-Version: 1.0\r\n";
        $content = 'Date: ' . date('D, d M Y H:i:s') . " UT\r\n";
        $content .= 'Subject: =?utf-8?B?' . base64_encode($this->subject) . "=?=\r\n";

        if ($this->attachment === true) {
            $headers .= "Content-Type: multipart/mixed; boundary=\"{$this->boundary}\"\r\n";
        } else {
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
        }

        $headers .= "From: {$this->from[0]} <{$this->from[1]}>\r\n";
        $headers .= 'To: ' . implode(',', $this->recepient) . "\r\n";
        $content .= $headers . "\r\n";

        if ($this->attachment === true) {
            $content .= "--{$this->boundary}\r\n";
            $content .= "Content-Type: text/html; charset=utf-8\r\n";
            $content .= "Content-Transfer-Encoding: base64\r\n";
            $content .= "\r\n";
            $content .= chunk_split(base64_encode($this->message));
            $content .= $this->multipart;
            $content .= "\r\n--{$this->boundary}--\r\n";
        } else {
            $content .= $this->message . "\r\n";
        }

        return trim($content);
    }
}
