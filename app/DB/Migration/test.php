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

namespace App\DB\Migration;

use Octopy\Database\Migration;
use Octopy\Support\Facade\Schema;
use Octopy\Database\Migration\BluePrint;

class test extends Migration
{
    /**
     * @var int
     */
    public static $timestamp = 1557841177;

    /**
     * @return void
     */
    public function create()
    {
        Schema::create('test', function (BluePrint $table) {
            $table->increment('id');
        });
    }

    /**
     * @return void
     */
    public function drop()
    {
        Schema::drop('test');
    }
}
