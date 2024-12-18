<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Campaign; 
use App\Events\LogUpdated;
use Illuminate\Console\Command;

class DispatchJobChains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dispatch-job-chains';

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
            foreach($campaign->connectors as $connector){ 
                $class = 'App\\Jobs\\' . $connector->process->class;   
                $class::dispatch($connector, [], ['campaign_id' => $campaign->id])->onQueue("default");
            } 
        }
    }
}
