<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use App\Models\Content;
use App\Jobs\PostFacebook;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchAimlResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $article;

    /**
     * Create a new job instance.
     */
    public function __construct($article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $guzzle = new Client(['base_uri' => 'https://api.aimlapi.com']);

        $text = "Can you rewrite article on this and make sound exciting and provide format in json only no preamble with title and text only: ";
        //$string = str_replace(array("\n","\r"), '', $article->text);
 
        $text .= $this->article->title;
        //\Log::debug($text);

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
                    'Authorization' => 'Bearer ' . config('services.aiml.api_key'),
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($data),
            ]);

            $response = $raw_response->getBody()->getContents();
            $res_obj = json_decode($response);
            $clean = str_replace('```json','', $res_obj->choices[0]->message->content);
            $clean = str_replace('```','', $clean);
       
            $clean_json = json_decode(trim($clean));
            //echo $res_obj->choices[0]->message->content;
            if(isset($clean_json->text)){
                //\Log::debug($clean_json->text);
 

                // $data = [
                //     'worldnews_id' => $this->article->id,
                //     'title' => $this->article->title, 
                //     'text' => $this->article->text,
                //     'summary' => $this->article->summary,
                //     'url' => $this->article->url,
                //     'image' => $this->article->image,
                //     'video' => $this->article->video,
                //     'publish_date' => $this->article->publish_date
                // ];
                // $content = new Content();
                // $content->fill($data);
                // $content->save();
                dispatch(new PostFacebook($clean_json->text));
            } 
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            echo $ex->getResponse()->getBody()->getContents() ;
        }
    }
}
