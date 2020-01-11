<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

namespace Octopy\Console\Command;

use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;

class DatabaseMigrateCommand extends Command
{
    /**
     * @var string
     */
    protected $command = 'database:migrate';

    /**
     * @var array
     */
    protected $options = [
        '-s, --seed'    => 'Seed the database with record',
        '-r, --refresh' => 'Reset and re-run all migrations',
    ];

    /**
     * @var array
     */
    protected $argument = [];

    /**
     * @var string
     */
    protected $description = 'Run the database migrations';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        if ($name = $argv->get('name')) {
            // Single
            $data = [
                'name'  => $name,
                'class' => '\\App\\DB\\Migration\\' . $name,
            ];

            if ($argv->get('-r') || $argv->get('--refresh')) {
                $this->drop($output, $data);

                echo $output->white(str_repeat('-', 40));
            }

            $this->create($output, $data);
        } else {
            // Multiple
            $directory = $this->app['path']->app->DB('Migration');

            if (! is_dir($directory)) {
                return $output->warning('Nothing to migrate.');
            }

            $iterator = $this->app['filesystem']->iterator($directory);

            $migration = [];
            foreach ($iterator as $row) {
                if ($row->isDir()) {
                    continue;
                }

                $class = '\\App\\DB\\Migration\\' . ($file = mb_substr($row->getFilename(), 0, -4));
                $migration[ $class::$timestamp ] = [
                    'name'  => $file,
                    'class' => $class,
                ];
            }

            if (empty($migration)) {
                return $output->warning('Nothing to migrate.');
            }

            // refresh
            if ($argv->get('-r') || $argv->get('--refresh')) {
                foreach ($migration as $data) {
                    $this->drop($output, $data);
                }

                echo $output->white(str_repeat('-', 40));
            }

            // Migrating
            // sort($migration);

            foreach ($migration as $data) {
                $this->create($output, $data);
            }
        }

        /**
         * Database Seeding
         */
        if ($argv->get('-s') || $argv->get('--seed')) {
            $argv->remove('name');
            echo $output->white(str_repeat('-', 40));
            echo $this->call('database:seed');
        }
    }

    /**
     * @param Output $output
     * @param array  $data
     */
    protected function create(Output $output, array $data)
    {
        echo $output->warning('Migrating : <c:white>' . $data['name']);
        $this->app->make($data['class'])->create();
        echo $output->success('Migrated  : <c:white>' . $data['name']);
    }

    /**
     * @param Output $output
     * @param array  $data
     */
    protected function drop(Output $output, array $data)
    {
        echo $output->warning('Dropping Table : <c:white>' . $data['name']);
        $this->app->make($data['class'])->drop();
        echo $output->success('Table Dropped  : <c:white>' . $data['name']);
    }
}
