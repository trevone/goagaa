<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Jobs\FetchWorldNews;
use Illuminate\Console\Command;

class DispatchJobChains3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dispatch-job-chains3';

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
        $campaigns = Campaign::all();

        foreach($campaigns as $campaign){ 
            dispatch(new FetchWorldNews($campaign));
        }
    }
}
