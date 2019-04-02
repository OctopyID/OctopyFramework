<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @version : v1.0
 * @license : MIT
 */

namespace Octopy\Database\Migration\BluePrint;

use Octopy\Database\Migration\BluePrint;

class MySQL extends BluePrint
{
    /**
     * @return BluePrint
     */
    public function primary()
    {
        array_push($this->primary, $this->name);

        return $this;
    }

    /**
     * @return BluePrint
     */
    public function unique()
    {
        array_push($this->unique, $this->name);

        return $this;
    }

    /**
     * @return BluePrint
     */
    public function index()
    {
        array_push($this->index, $this->name);

        return $this;
    }

    /**
     * @param  string $name
     * @return BluePrint
     */
    public function increment(string $name)
    {
        return $this->set($name, 'INT NOT NULL AUTO_INCREMENT')->primary();
    }

    /**
     * @param  string $name
     * @return BluePrint
     */
    public function integer(string $name)
    {
        return $this->set($name, 'INT NOT NULL');
    }

    /**
     * @param  string $name
     * @param  int    $length
     * @return BluePrint
     */
    public function string(string $name, int $length = 255)
    {
        return $this->set($name, sprintf('VARCHAR(%s) NOT NULL', $length));
    }

    /**
     * @param  string $name
     * @return BluePrint
     */
    public function text(string $name)
    {
        return $this->set($name, 'TEXT NOT NULL');
    }

    /**
     * @param  string $name
     * @param  array  $data
     * @return BluePrint
     */
    public function enum(string $name, array $data)
    {
        foreach ($data as $index => $value) {
            $data[$index] = sprintf("'%s'", $value);
        }

        return $this->set($name, sprintf('ENUM(%s) NOT NULL', implode(', ', $data)));
    }

    /**
     * @param  string $name
     * @return BluePrint
     */
    public function time(string $name)
    {
        return $this->set($name, 'TIME NOT NULL');
    }

    /**
     * @param  string $name
     * @return BluePrint
     */
    public function date(string $name)
    {
        return $this->set($name, 'DATE NOT NULL');
    }

    /**
     * @param  string $name
     * @return BluePrint
     */
    public function year(string $name)
    {
        return $this->set($name, 'YEAR NOT NULL');
    }

    /**
     * @param  string $name
     * @return BluePrint
     */
    public function datetime(string $name)
    {
        return $this->set($name, 'DATETIME NOT NULL');
    }

    /**
     * @param  string $name
     * @return BluePrint
     */
    public function timestamp(string $name)
    {
        return $this->set($name, 'TIMESTAMP NOT NULL');
    }

    /**
     * @param  string $default
     * @return BluePrint
     */
    public function default($default = 'NULL')
    {
        $index = count($this->query) - 1;
        
        if (!in_array($default, ['NULL', 'CURRENT_TIMESTAMP'])) {
            $default = sprintf("'%s'", $default);
        }

        $this->query[$index] = $this->query[$index] . " DEFAULT $default";

        return $this;
    }

    /**
     * @param  string $parent
     * @param  string $primary
     * @return BluePrint
     */
    public function foreign(string $parent, string $primary)
    {
        $index = count($this->query) - 1;

        $this->query[$index] = $this->query[$index] . sprintf(', FOREIGN KEY (`%s`) REFERENCES `%s` (`%s`)', $this->name, $parent, $primary);

        return $this;
    }

    /**
     * @param  string $condition
     * @param  string $option
     * @return BluePrint
     */
    public function on(string $condition, string $option)
    {
        return $this->$condition($option);
    }

    /**
     * @param  string $option
     * @return BluePrint
     */
    public function update(string $option)
    {
        $index = count($this->query) - 1;

        $this->query[$index] = $this->query[$index] . " ON UPDATE $option";

        return $this;
    }

    /**
     * @param  string $option
     * @return BluePrint
     */
    public function delete(string $option)
    {
        $index = count($this->query) - 1;

        $this->query[$index] = $this->query[$index] . " ON DELETE $option";

        return $this;
    }

    /**
     * @return BluePrint
     */
    public function unsigned()
    {
        $index = count($this->query) - 1;

        $this->query[$index] = str_replace('NOT NULL', 'UNSIGNED NOT NULL', $this->query[$index]);

        return $this;
    }

    /**
     * @param  string $name
     * @param  string $query
     * @return BluePrint
     */
    public function set(string $name, string $query)
    {
        $this->name = $name;
        array_push($this->query, sprintf('`%s` %s', $name, $query));

        return $this;
    }

    /**
     * @param  string $table
     * @return string
     */
    public function create(string $table)
    {
        if (!empty($this->primary)) {
            $this->query[] = sprintf('PRIMARY KEY (%s)', implode(', ', array_map(function ($name) {
                return sprintf('`%s`', $name);
            }, $this->primary)));
        }

        if (!empty($this->unique)) {
            $this->query[] = sprintf('UNIQUE (%s)', implode(', ', array_map(function ($name) {
                return sprintf('`%s`', $name);
            }, $this->unique)));
        }

        if (!empty($this->index)) {
            $this->query[] = sprintf('INDEX (%s)', implode(', ', array_map(function ($name) {
                return sprintf('`%s`', $name);
            }, $this->index)));
        }

        $this->query(
            sprintf('CREATE TABLE `%s` (%s) ENGINE = InnoDB;', $table, implode(', ', $this->query))
        );

        $this->query = $this->primary = $this->unique = $this->index = [];
    }

    /**
     * @param  string $table
     * @return string
     */
    public function drop(string $table)
    {
        return $this->query(
            sprintf('SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS `%s`; SET FOREIGN_KEY_CHECKS = 1;', $table)
        );
    }
}
