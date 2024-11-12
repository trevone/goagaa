<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FetchWorldNewsBak extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:world-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches data from World News API';

    protected $text = "london police";
    protected $limit = 1;


    public function rewrite($article)
    {
        $guzzle = new Client(['base_uri' => 'https://api.aimlapi.com']);

        $text = "Can you rewrite article on this and make sound exciting: ";
        //$string = str_replace(array("\n","\r"), '', $article->text);
 
        $text .= $article->title;

        $data = (object) [
            "model" => "gpt-4o",
            "messages" =>   [ 
                (object) [
                    "role"=> "user",
                    "content"=> $text
                ]
                
            ],
            "max_tokens"=> 512,
            "stream"=> false
        ];
        try {
            $raw_response = $guzzle->post('/chat/completions', [
                'headers' => [ 
                    'Authorization' => 'Bearer b135f89da42847e78bebcb7393e6a6cc',
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($data),
            ]);

            $response = $raw_response->getBody()->getContents();
            $res_obj = json_decode($response);
            echo $res_obj->choices[0]->message->content;
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            echo $ex->getResponse()->getBody()->getContents() ;
        }
    
        
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // https://worldnewsapi.com/docs/search-news/
        $guzzle = new Client(['base_uri' => 'https://api.worldnewsapi.com']);
        $search = urlencode($this->text);
        $today = Carbon::now()->format('Y-m-d');
        $raw_response = $guzzle->get('/search-news?text='.$search.'&earliest-publish-date='.$today.'&language=en&number='.$this->limit, [
            'headers' => [ 
                'x-api-key' => '08abbd6f105247b4a47e93133b642a32', 
                'Content-Type' => 'application/json'
            ], 
        ]);

        $response = json_decode($raw_response->getBody()->getContents()); 

        foreach($response->news as $article){
         
            $this->rewrite($article);
        }

        // title
        // text
        // summary
        // url
        // image
        // video
        // publish_date     
        /*
        $guzzle = new Client(['base_uri' => 'https://api.aimlapi.com']);

        $data = (object) [
            "model" => "gpt-4o",
            "messages" =>   [ 
                (object) [
                    "role"=> "user",
                    "content"=> "What kind of model are you?"
                ]
                
            ],
            "max_tokens"=> 512,
            "stream"=> false
        ];
        $raw_response = $guzzle->post('/chat/completions', [
            'headers' => [ 
                'Authorization' => 'Bearer b135f89da42847e78bebcb7393e6a6cc',
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($data),
        ]);
       
        $response = $raw_response->getBody()->getContents();
        echo 'done';
        */
    }
}
