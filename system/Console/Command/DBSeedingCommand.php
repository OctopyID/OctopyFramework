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

namespace Octopy\Console\Command;

use Octopy\Console\Argv;
use Octopy\Console\Output;
use Octopy\Console\Command;

class DBSeedingCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'db:seed';

    /**
     * @var string
     */
    protected $description = 'Seed the database with records';

    /**
     * @param  Argv   $argv
     * @param  Output $output
     * @return string
     */
    public function handle(Argv $argv, Output $output)
    {
        $directory = $this->app['path']->app->DB('Seeder');

        if (!is_dir($directory)) {
            return $output->warning('Nothing to seeds.');
        }

        $seed = !$argv->get('value') || !$argv->get('--seed') ? 'DatabaseSeeder' : $argv->get('value');
            
        if (!class_exists('App\DB\Seeder\DatabaseSeeder')) {
            $this->call('make:seeder', ['value' => 'DatabaseSeeder']);
        }

        return $this->seed($output, $seed);
    }

    /**
     * @param Output $output
     * @param string $seed
     */
    private function seed(Output $output, string $seed)
    {
        echo $output->success('Seeding : {white}' . $seed);

        call_user_func([$seeder = $this->app->make('App\\DB\\Seeder\\' . $seed), 'seed']);
       
        if (!empty($seeds = $seeder->call())) {
            foreach ($seeds as $i => $seed) {
                $this->seed($output, str_replace('App\\DB\\Seeder\\', '', $seed));
            }
        }
    }
}
