<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\HomeController;

class RunEveryFiveSeconds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-every-five-seconds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = now();
        $endTime = $startTime->copy()->addMinute(); // Run for 1 minute

        // Loop every 5 seconds until the minute is over
        while (now()->lessThan($endTime)) {
            // Call the method you want to run
            $callMethodFromHomeController = new HomeController();
            $callMethodFromHomeController->checkInternetConnection();
            // Wait for 5 seconds before the next execution
            sleep(5);
        }
    }
}
