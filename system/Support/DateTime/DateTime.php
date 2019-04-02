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

namespace Octopy\Support;

use DateTimeZone;

class DateTime extends \DateTime
{
    /**
     * @param int    $time
     * @param string $timezone
     */
    public function __construct(?string $time = 'now', $timezone = null)
    {
        if (!$timezone instanceof DateTimeZone) {
            $timezone = new DateTimeZone($timezone ?? 'UTC');
        }

        parent::__construct($time, $timezone);
    }

    /**
     * @param  string $timezone
     * @return $this
     */
    public function now(string $timezone = null)
    {
        return $this->parse('now', $timezone);
    }

    /**
     * @param  string $timezone
     * @return $this
     */
    public function today(string $timezone = null)
    {
        return $this->parse('today', $timezone);
    }

    /**
     * @param  string $timezone
     * @return $this
     */
    public function yesterday(string $timezone = null)
    {
        return $this->parse('yesterday', $timezone);
    }

    /**
     * @param  string $timezone
     * @return $this
     */
    public function tomorrow(string $timezone = null)
    {
        return $this->parse('tomorrow', $timezone);
    }

    /**
     * @param  integer $second
     * @return $this
     */
    public function second(int $second)
    {
        return $this->parse((int)$second . ' second');
    }

    /**
     * @param  integer $minute
     * @return $this
     */
    public function minute(int $minute)
    {
        return $this->parse((int)$minute . ' minute');
    }

    /**
     * @param  integer $hour
     * @return $this
     */
    public function hour(int $hour)
    {
        return $this->parse((int)$hour . ' hour');
    }

    /**
     * @param  integer $day
     * @return $this
     */
    public function day(int $day)
    {
        return $this->parse((int)$day . ' day');
    }

    /**
     * @param  integer $week
     * @return $this
     */
    public function week(int $week)
    {
        return $this->parse((int)$week . ' week');
    }

    /**
     * @param  integer $month
     * @return $this
     */
    public function month(int $month)
    {
        return $this->parse((int)$month . ' month');
    }

    /**
     * @param  integer $year
     * @return $this
     */
    public function year(int $year)
    {
        return $this->parse((int)$year . ' year');
    }

    /**
     * @param  string $time
     * @param  string $timezone
     * @return $this
     */
    public function parse(string $time, string $timezone = null)
    {
        return new static($time, $timezone);
    }

    /**
     * @param  string $timezone
     * @return mixed
     */
    public function timezone(string $timezone = null)
    {
        if (is_null($timezone)) {
            return $this->getTimezone()->getName();
        }

        return $this->setTimezone($timezone);
    }

    /**
     * @return string
     */
    public function timestamp() : string
    {
        return $this->getTimestamp();
    }

    /**
     * @return string
     */
    public function cookie() : string
    {
        return $this->format(DateTime::COOKIE);
    }
}
