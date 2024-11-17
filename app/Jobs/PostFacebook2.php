<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PostFacebook2 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $text;

    /**
     * Create a new job instance.
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $access_token = config('services.facebook.access_token');

        $client = new Client(['base_uri' => 'https://graph.facebook.com/v21.0/']);
        // Send a request to https://foo.com/api/test 

        try {  

            $long_lived_access_token = $access_token;

            $response = $client->request('POST', '488087137720800/feed', [
                'query' => [
                    'access_token' => $long_lived_access_token,
                    'message' => $this->text,
                    'published' => true
                ]
            ]);
            $response_body = (string) $response->getBody(); 
            
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            echo $ex->getResponse()->getBody()->getContents() ;
        }
    }
}
