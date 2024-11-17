<?php

namespace App\Jobs;

use App\Models\Post;
use GuzzleHttp\Client;
use App\Models\Content;
use App\Models\Connector;
use App\Jobs\PostFacebook;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FunctionChatCompletions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $connector;
    protected $input;
    protected $output;
    protected $post;

    /**
     * Create a new job instance.
     */
    public function __construct(Connector $connector, $post = [], $input = null)
    {
        $this->connector = $connector;
        $this->post = $post;
        $this->input = $input;
        if(!isset($this->input['content'])){
            $this->input['content'] = data_get($this->connector, 'data.input.content', '');
        } else {
            $this->input['content'] = str_replace('##output##', $this->input['content'], data_get($this->connector, 'data.input.content', ''));
        }
        $this->input['content'] .= " - no preamble";
        $this->output = [];
        \Log::debug('process: ' . $this->connector->id);
        \Log::debug($this->input);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
  
        $guzzle = new Client(['base_uri' => 'https://api.aimlapi.com']);

        $tools = [
            (object)[
              "type" => "function",
              "function" => (object) [
                "name"=> "format_blog_articles",
                "description"=> "Creates html markup from json input",
                "parameters"=> (object) [
                  "type"=> "object",
                  "properties"=>  [
                    "h1"=>(object)[
                      "type"=> "string",
                      "description"=> "for eatitles and headings"
                    ],
                    "p"=> (object) [
                      "type"=> "string",
                      "description"=> "for each paragraphs in the artitle"

                    ]
                  ]
                ] 
              ]
            ]
        ];
        $data = (object) [
            "model" => "gpt-4o",
            "messages" =>   [ 
                (object) [
                    "role"=> "system",
                    "content"=> "you are a teenage girl"
                ],
                (object) [
                    "role"=> "user",
                    "content"=> $this->input['content']
                ]
                
            ],
            "tools" => $tools,
            "max_tokens"=> 512,
            "stream"=> false,
            "stop" => [], // Instructs the model to stop generating
            "temperature" => 0.7, // values up to 1 introduce more variation ,
            "top_p" => 0.9, // Only tokens that contribute to the top 90%
            "top_k" => 40,  # The model will only consider the top 40 most probable next tokens.
            "repetition_penalty" => 1.2  # Applies a penalty to discourage repetition. 
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
            $content = $res_obj->choices[0]->message;
            \Log::debug(var_export($content,1));
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            echo $ex->getResponse()->getBody()->getContents();
        }

        
    }
}
