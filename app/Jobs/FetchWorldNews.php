<?php

namespace App\Jobs;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchWorldNews implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $campaign;
    protected $limit;

    /**
     * Create a new job instance.
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
        $this->limit = 1;
        \Log::debug('starting job: ' . $this->campaign->prompt);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $guzzle = new Client(['base_uri' => 'https://api.worldnewsapi.com']);
        
        $prompts = [
            'london police',
            'social media',
            'online trends',
            'sports headlines',
            'fitness routine',
            'beauty products',
            'music show',
            'travel destination', 
        ];
        
        $rand = rand(0,7);
        $search = urlencode($prompts[$rand]); 

        $today = Carbon::now()->format('Y-m-d');
        $raw_response = $guzzle->get(
                '/search-news?text='.$search
                .'&earliest-publish-date='.$today
                .'&language=en&number='.$this->limit, 
        [
            'headers' => [ 
                'x-api-key' => config('services.worldnews.api_key'), 
                'Content-Type' => 'application/json'
            ], 
        ]);

        $response = json_decode($raw_response->getBody()->getContents());  

        foreach($response->news as $article){  
            dispatch(new FetchAimlResult($article));
        }
    }
}
