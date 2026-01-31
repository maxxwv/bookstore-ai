<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class PHPStanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lint:phpstan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
		$defaultBranch = trim(Process::run("git remote show origin | sed -n '/HEAD branch/s/.*: //p'")->output());
		$files = array_filter(explode(PHP_EOL, Process::run('git diff --name-only --diff-filter=d ' . $defaultBranch)->output()));

		if(empty($files)){
			$this->info('No changed files to test.');
			return self::SUCCESS;
		}

		$files = array_map(function($path){
			return str_replace(config('app.install_folder'), '', $path);
		}, $files);

		Process::timeout(0)
			->tty()
			->run('./vendor/bin/phpstan analyse --memory-limit=2G  --no-progress ' . join(" ", $files), function(string $type, string $output){
				$this->getOutput()->write($output);
			});
		return self::SUCCESS;
    }
}
