<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QzRmb;
use Illuminate\Support\Facades\Log;
class QzRmbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:rmb';

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
        $exercise = new QzRmb();
        $do_exercise = $exercise->Rmb();
        Log::info('这是人民币对应美元的价格'.$do_exercise);
    }
}