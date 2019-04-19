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

namespace Octopy;

use ArrayIterator;
use JsonSerializable;
use IteratorAggregate;
use Octopy\Database\Connection;
use Octopy\Database\Exception\DBException;

class Database implements IteratorAggregate, JsonSerializable
{
    /**
     * @var Octopy\Application
     */
    protected $app;

    /**
     * @var string
     */
    protected $query;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var PDO
     */
    protected $driver;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        if (! $this->driver) {
            $this->driver($app['config']['database.default']);
        }
    }

    /**
     * @param  string $property
     * @return mixed
     */
    public function __get(string $property)
    {
        if (! is_array($this->data)) {
            return $this->data->$property ?? null;
        }
    }

    /**
     * @param  string $driver
     * @return
     */
    public function driver(string $driver)
    {
        $connector = new Connection($driver, $this->app['config']['database.connection.' . $driver]);

        try {
            $this->driver = $connector->connect();
        } catch (DBException $exception) {
            throw $exception;
        }

        return $this;
    }

    /**
     * @param  string $model
     * @return $this
     */
    public function model(string $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param  string $table
     * @return $this
     */
    public function table(string $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @param  string $query
     * @return
     */
    public function query(string $query)
    {
        $this->query = trim($query);

        return $this->execute();
    }

    /**
     * @param  array $column
     * @return $this
     */
    public function select(...$column)
    {
        if (empty($column) || in_array('*', $column)) {
            unset($column);
            $column[] = '*';
        } else {
            foreach ($column as $key => $value) {
                $column[$key] = $this->escape($value);
            }
        }

        if ($this->match('INSERT|UPDATE|DELETE')) {
            $this->reset();
        }

        return $this->set('SELECT %s FROM %s %s', implode(', ', $column), $this->escape($this->table), $this->reset());
    }

    /**
     * @param  string $column
     * @param  mixed  $value
     * @param  string $operator
     * @return $this
     */
    public function where($column, $value = null, string $operator = '=')
    {
        // If the column is an array, we will assume it is an array of key-value pairs
        // and can add them each as a where clause. We will maintain the boolean we
        // received when the method was called and pass it into the nested where.
        if (is_array($column)) {
            foreach ($column as $key => $value) {
                $this->where($key, $value);
            }

            return $this;
        }

        $clause = 'WHERE';
        if ($this->match('WHERE')) {
            $clause = 'AND';
        }

        return $this->set(' %s %s %s %s', $clause, $this->escape($column), $operator, $this->quote($value));
    }

    /**
     * @param  string $column
     * @param  mixed  $value
     * @param  string $operator
     * @return $this
     */
    public function or($column, $value = null, string $operator = '=')
    {
        if (is_array($column)) {
            foreach ($column as $key => $value) {
                $this->or($key, $value);
            }

            return $this;
        }

        if (! $this->match('WHERE')) {
            return $this->where($column, $value, $operator);
        }

        return $this->set(' OR %s %s %s', $this->escape($column), $operator, $this->quote($value));
    }

    /**
     * @param  int $limit
     * @return $this
     */
    public function limit(int $limit)
    {
        if (! $this->match('SELECT')) {
            $this->select('*');
        }

        return $this->set(' LIMIT %s', $limit);
    }

    /**
     * @param  int $start
     * @param  int $end
     * @return $this
     */
    public function offset(int $start, int $end)
    {
        if (! $this->match('LIMIT')) {
            $this->limit($start);
        }

        return $this->set(' OFFSET %s', $end);
    }

    /**
     * @param  string $column
     * @param  string $order
     * @return $this
     */
    public function order(string $column, string $order = 'ASC')
    {
        if (! $this->match('SELECT')) {
            $this->select('*');
        }

        return $this->set(' ORDER BY %s %s', $this->escape($column), $order);
    }

    /**
     * @return $this
     */
    public function first()
    {
        if (! $this->match('SELECT')) {
            $this->select('*');
        }

        if (! $this->match('LIMIT')) {
            $this->limit(1);
        }

        return $this->data($this->execute()->fetch());
    }

    /**
     * @return $this
     */
    public function get()
    {
        if (! $this->match('SELECT')) {
            $this->select('*');
        }

        return $this->data($this->execute()->fetchAll());
    }

    /**
     * @param  array $data
     * @return bool
     */
    public function insert(array $data) : bool
    {
        $this->reset();

        $column = implode(', ', array_map(function ($column) {
            return $this->escape($column);
        }, array_keys($data)));

        $value = implode(', ', array_map(function ($value) {
            return $this->quote($value);
        }, array_values($data)));

        $this->set('INSERT INTO %s (%s) VALUES (%s)', $this->escape($this->table), $column, $value);

        return $this->execute() ? true : false;
    }

    /**
     * @param  mixed $data
     * @return $this
     */
    protected function data($data)
    {
        if (! empty($model = $this->model)) {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = new $model($value);
                }
            } else {
                $data = new $model($data);
            }
        }

        $this->data = $data;

        return $this;
    }

    /**
     * @return PDO
     */
    protected function execute()
    {
        try {
            return $this->driver->query($this->query);
        } catch (DBException $exception) {
            throw new DBException($this->query);
        }
    }

    /**
     * @param  string $search
     * @return bool
     */
    protected function match(string $search) : bool
    {
        return preg_match('/' . $search . '/', $this->query);
    }

    /**
     * @param  mixed $value
     * @return mixed
     */
    protected function quote($value)
    {
        if (is_string($value)) {
            return $this->driver->quote($value);
        }

        return $value;
    }

    /**
     * @param  string $value
     * @return string
     */
    protected function escape(string $value) : string
    {
        return sprintf('`%s`', $value);
    }

    /**
     * @param string $format
     * @param array  $value
     */
    protected function set(string $format, ...$value)
    {
        $this->query = preg_replace('/\s+/', ' ', $this->query .= sprintf($format, ...$value));

        return $this;
    }

    /**
     * @return string
     */
    protected function reset() : string
    {
        $query = $this->query;

        $this->query = null;

        return trim($query);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->data;
    }
}
