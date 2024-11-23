<?php

namespace App\Jobs;

use App\Jobs\BaseJob;

class ChatCompletionsV2 extends BaseJob
{ 
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $guzzle = new Client(['base_uri' => config('services.aiml.base_uri')]);
        $data = (object) [
            "model" => data_get($this->connector, 'model', 'gpt-4o'),
            "messages" =>   [ 
                (object) [
                    "role"=> "system",
                    "content"=> $this->input['system_role_content'] // add content system roles
                ],
                (object) [
                    "role"=> "user",
                    "content"=> $this->input['user_role_content'] // add content user roles
                ] 
            ],
            "stream"=> data_get($this->connector, "hidden.stream", false),
            "max_tokens"=> data_get($this->connector, "hidden.max_tokens", 512), // max_tokens  
            "stop" => data_get($this->connector, "hidden.stop", []), // Instructs the model to stop generating
            "temperature" => data_get($this->connector, "hidden.temperature", 0.7), // values up to 1 introduce more variation,
            "top_p" => data_get($this->connector, "hidden.top_p", 0.9), // Only tokens that contribute to the top 90%
            "top_k" => data_get($this->connector, "hidden.top_k", 40),  # The model will only consider the top 40 most probable next tokens.
            "repetition_penalty" => data_get($this->connector, "hidden.repetition_penalty", 1.2)  # Applies a penalty to discourage repetition.

        ];
        
        $process = Process::find($this->connector->process_id);

        try {
            $raw_response = $guzzle->post(data_get($process, 'data.endpoint'), [
                'headers' => [ 
                    'Authorization' => 'Bearer ' . config('services.aiml.api_key'),
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($data),
            ]); 
            $response = $raw_response->getBody()->getContents();
            $res_obj = json_decode($response); 
            $content = $res_obj->choices[0]->message->content; 
            $this->output["user_role_content"] = $content;
            $this->output["system_role_content"] = $this->input["system_role_content"];
            $this->post['text'] = $content;  
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            echo $ex->getResponse()->getBody()->getContents();
        } 
    } 
}