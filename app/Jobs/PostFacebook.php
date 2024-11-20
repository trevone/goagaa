<?php

namespace App\Jobs;

use App\Models\Post;
use GuzzleHttp\Client;
use App\Models\Process;
use App\Models\Connector;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PostFacebook implements ShouldQueue
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


        $this->post['text'] = $this->post['image'] . " " . $this->post['text'];

        $access_token = config('services.facebook.access_token');

        $client = new Client(['base_uri' => config('services.facebook.base_uri')]); 
        $process = Process::find($this->connector->process_id);
        $endpoint = data_get($process, 'data.page_id') . '/feed';
        try {    
            $response = $client->request('POST', $endpoint, [
                'query' => [
                    'access_token' => $access_token,
                    'message' => $this->post['text'],
                    'published' => true
                ]
            ]);
            $response_body = (string) $response->getBody();  
            $post = new Post();
            $post->fill(
                $this->post
            );
            $post->save(); 
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            echo $ex->getResponse()->getBody()->getContents() ;
        }
    }
}
