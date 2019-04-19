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

namespace Octopy\Database;

use ArrayIterator;
use Octopy\Database;
use JsonSerializable;
use Octopy\Container;
use IteratorAggregate;

class Model implements IteratorAggregate, JsonSerializable
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @param mixed $data
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * @param  string $property
     * @return mixed
     */
    public function __get(string $property)
    {
        if (method_exists($this, $property)) {
            return $this->$property();
        }

        if (isset($this->data->$property)) {
            return $this->data->$property;
        }
    }

    /**
     * @param  string $method
     * @param  array  $args
     * @return mixed
     */
    public function __call(string $method, array $args = [])
    {
        $db = Container::unset(Database::class)->make(Database::class);
        $db->model(static::class);

        if (property_exists($this, 'table')) {
            $db->table($this->table);
        } else {
            $model = explode(BS, static::class);
            $db->table(strtolower(end($model)));
        }

        return $db->$method(...$args);
    }

    /**
     * @param  string $method
     * @param  array  $args
     * @return mixed
     */
    public static function __callStatic(string $method, array $args = [])
    {
        return (new static)->$method(...$args);
    }

    /**
     * @param  string $model
     * @param  string $foreign
     * @param  string $primary
     * @return Database
     */
    public function link(string $model, string $foreign, string $primary = 'id')
    {
        $db = Container::unset(Database::class)->make(Database::class);
        $db->model($model);

        if (property_exists($instance = Container::make($model), 'table')) {
            $db->table($instance->table);
        } else {
            $model = explode(BS, $model);
            $db->table(strtolower(end($model)));
        }

        return $db->where($foreign, $this->$primary);
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
