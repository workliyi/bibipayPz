<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\QzExercise;
use Illuminate\Support\Facades\Log;

class QzOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:qzorder';

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
        $exercise = new QzExercise();
        $do_exercise = $exercise->forceExecute();
        Log::info($do_exercise);
    }
}
