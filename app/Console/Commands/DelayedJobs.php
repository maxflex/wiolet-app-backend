<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DelayedJob;

class DelayedJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:delayed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		// получить все текущие задачи
		$currentJobs = DelayedJob::whereRaw("DATE_FORMAT(run_at, '%Y-%m-%d %H:%i')='" . date('Y-m-d H:i') ."'")->get();

        $bar = $this->output->createProgressBar(count($currentJobs));
		foreach($currentJobs as $job) {
			$jobClass = new $job->class;
			$jobClass->handle($job->params);
			$job->delete();
            $bar->advance();
		}
        $bar->finish();
    }
}
