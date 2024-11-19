<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostTwitter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $connector;
    protected $post;
    protected $input;

    /**
     * Create a new job instance.
     */
    public function __construct(Connector $connector, $post, $input = null)
    {
        $this->connector = $connector;
        $this->post = $post;
        $this->input = $input;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if(
            is_null($this->post['text']) 
            || empty($this->post['text']) 
            || !isset($this->post['text'])
        ){
            \Log::debug('not text to post');
            return;
        }

        $credentials = array(
            'bearer_token' => 'AAAAAAAAAAAAAAAAAAAAACzXwwEAAAAAbwSmNeHAAe9OKPvacf%2FLIOAmLzk%3D8hWDb4WbBgMYF8JIKCfVUvAzfzzLo7Oa8fwysc5JkI8o0b3Vtn',
            'consumer_key' => 'lu9vVbXiDbjiRmLrHDu3K5Cej',
            'consumer_secret' => 'MlBAQ2EWxU8GbRN15zkQIRWL9UOezYn2VlAAwQz5ip20dbTPb7',
            // if using oAuth 2.0 with PKCE
            // 'auth_token' => xxxxxx // OAuth 2.0 auth token
            //if using oAuth 1.0a
            'token_identifier' => '1856965653002964992-ZcDNaKZCmnyhkPpjd4JsOgIwADlYdc',
            'token_secret' => '3fk4fTil1lrlaOAzC62CqqjQZSzOP2Tzg4dXaCgKTg8QD',
        );
        
        //instantiate the object
        $twitter = new BirdElephant($credentials);
        
        //tweet something
        $tweet = (new \Coderjerk\BirdElephant\Compose\Tweet)->text($this->post['text']);
        
        $twitter->tweets()->tweet($tweet);
        // Check given state against previously stored one to mitigate CSRF attack
    }
}
