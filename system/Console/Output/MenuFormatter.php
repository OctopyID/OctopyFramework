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

namespace Octopy\Console\Output;

class MenuFormatter
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var int
     */
    protected $state = 0;

    /**
     * @var int
     */
    protected $prev = 0;

    /**
     * @var array
     */
    protected $margin = [];

    /**
     * @param  int $margin
     */
    public function margin(int $margin = 0)
    {
        $this->prev = $margin;
    }

    /**
     * @param  array $keys
     * @param  array $data
     * @param  array $color
     */
    public function add(array $keys, array $data, array $color = [])
    {
        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $this->margin[$this->state] = $this->prev;

                $this->data[$this->state][] = $data[$key];
            }
        }

        $this->state++;
    }

    /**
     * @return string
     */
    public function render() : string
    {
        $column = [];
        foreach ($this->data as $rkey => $row) {
            foreach ($row as $ckey => $cell) {
                $length = mb_strlen($cell);
                if (empty($column[$ckey]) || $column[$ckey] < $length) {
                    $column[$ckey] = $length;
                }
            }
        }

        $table = '';
        foreach ($this->data as $rkey => $row) {
            $table .= str_pad(' ', $this->margin[$rkey]);
            foreach ($row as $ckey => $cell) {
                $table .= str_replace("\n", '', str_pad($cell, $column[$ckey]));
            }

            $table .= "\n";
        }

        $this->data = [];
        $this->margin = [];
        $this->margin(
            $this->state = 0
        );

        return $table;
    }
}
