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
        $this->limit = 3;
        \Log::debug('starting job: ' . $this->campaign->prompt);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $guzzle = new Client(['base_uri' => 'https://api.worldnewsapi.com']);
        $search = urlencode($this->campaign->prompt);
        $today = Carbon::now()->format('Y-m-d');
        $raw_response = $guzzle->get('/search-news?text='.$search.'&earliest-publish-date='.$today.'&language=en&number='.$this->limit, [
            'headers' => [ 
                'x-api-key' => '08abbd6f105247b4a47e93133b642a32', 
                'Content-Type' => 'application/json'
            ], 
        ]);

        $response = json_decode($raw_response->getBody()->getContents()); 
        \Log::debug('Found: ' . count($response->news));

        foreach($response->news as $article){
         
             \Log::debug($article->title);
             dispatch(new FetchAimlResult($article));
        }
    }
}
