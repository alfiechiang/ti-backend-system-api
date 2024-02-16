<?php

namespace App\Console\Commands;

use App\Http\Services\SettlementService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CaculateSettlement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "caculate:settle {date?}";

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
     * @return int
     */
    public function handle()
    {

        $date = $this->argument('date');
        if (is_null($date)) {
            $date = Carbon::yesterday()->format("Y-m-d");
        }
        $service = new SettlementService($date);
        Log::info("執行結算指令 php artisan caculate:settle $date");
        $service->caculate($date);
    }
}
