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

use Exception;

class TableFormatter
{
    /**
    * @var array
    */
    private $data = [];

    /**
     * @var array
     */
    private $header = [];

    /**
     * @var array
     */
    private $length = [];

    /**
     * @var int
     */
    private $column;

    /**
     * @var string
     */
    private $line;

    /**
     * @var string
     */
    private $table;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;

        $this->prepare();
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->display();
    }

    /**
     * @return string
     */
    public function display() : string
    {
        $table = '';

        for ($int = 0; $int < $this->column; $int++) {
            $table .= '+';
            $length = $this->length[$int] - 7; //ensures that the longest string has a space after it
            $table .= sprintf("%'-{$length}s", '');
        }

        $table .= "+\n";

        $this->line = $table; //the first and the last line of the header

        //column names
        for ($int = 0; $int < $this->column; $int++) {
            $length = $this->length[$int] + 1; //ensures that the longest string has a space after it
            $table .= '| ';
            $table .= sprintf("%' -{$length}s", $this->header[$int]);
        }

        $table .= "|\n";

        //add the ending line
        $table .= $this->line;

        $this->table = $table;

        foreach ($this->data as $row) {
            $int = 0;
            foreach ($row as $field) {
                $length = $this->length[$int] + 1; //ensures that the longest string has a space after it
                $this->table .= '| ' . sprintf("%' -{$length}s", $field);
                $int++;
            }

            $this->table .= "|\n";
        }

        $this->table .= $this->line;

        return rtrim($this->table, "\n");
    }

    /**
     * @return void
     */
    private function prepare() : void
    {
        if (! is_array($this->data)) {
            throw new Exception('Data passed must be an array');
        }

        if (is_object($this->data[0])) {
            $this->header();
            $temp = [];

            foreach ($this->data as $obj) {
                $arr = [];

                foreach ($obj as $item) {
                    $arr[] = $item;
                }

                $temp[] = $arr;
            }

            $this->data = $temp;
        } elseif (is_array($this->data[0])) {
            $this->header();
        } else {
            throw new Exception('Passed data must be array of objects or arrays');
        }

        for ($int = 0; $int < $this->column; $int++) {
            $this->length[$int] = 0;

            foreach ($this->header as $field) {
                if (mb_strlen($field) > $this->length[$int]) {
                    $this->length[$int] = mb_strlen($field);
                }
            }
        }

        foreach ($this->data as $row) {
            $int = 0;
            foreach ($row as $field) {
                if (mb_strlen($field) > $this->length[$int]) {
                    $this->length[$int] = mb_strlen($field);
                }

                $int++;
            }
        }
    }

    /**
     * @return void
     */
    private function header() : void
    {
        if (! $this->header) {
            $temp = [];

            foreach ($this->data[0] as $key => $item) {
                $temp[] = $key;
            }

            $this->header = $temp;

            $this->column = count($temp);
        }
    }
}
