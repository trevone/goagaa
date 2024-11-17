<?php

namespace App\Jobs;

use App\Models\Post;
use GuzzleHttp\Client;
use App\Models\Content;
use App\Models\Process;
use App\Models\Connector;
use App\Jobs\PostFacebook;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ChatCompletions implements ShouldQueue
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
        $this->output = [];
 
        if(!isset($this->input['user_role_content'])){
            $this->input['user_role_content'] = data_get($this->connector, 'data.user_role_content', null);
        } else {
            $this->input['user_role_content'] = str_replace( '##output##', 
                $this->input['user_role_content'], data_get($this->connector, 
                'data.user_role_content', ''));
        }
        if(!isset($this->input['system_role_content'])){
            $this->input['system_role_content'] = data_get($this->connector, 'data.system_role_content', null);
        } else {
            $this->input['system_role_content'] = str_replace( '##output##', 
                $this->input['system_role_content'], data_get($this->connector, 
                'data.system_role_content', ''));
        }
        $this->input['user_role_content'] .= " - no preamble";
    }
 

    /**
     * Execute the job.
     */
    public function handle(): void
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
            if(!is_null($this->connector->connector_id)){
                $new_connector = Connector::find($this->connector->connector_id);
                $class = 'App\\Jobs\\' . $new_connector->process->class;  
                $class::dispatch($new_connector, $this->post, $this->output )->onQueue("default");
            }  
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            echo $ex->getResponse()->getBody()->getContents();
        } 
    }
}


// 'codellama/CodeLlama-34b-Instruct-hf' 
// | 'upstage/SOLAR-10.7B-Instruct-v1.0' 
// | 'meta-llama/Meta-Llama-Guard-3-8B' 
// | 'google/gemma-2b-it' 
// | 'Gryphe/MythoMax-L2-13b' 
// | 'meta-llama/LlamaGuard-2-8b' 
// | 'mistralai/Mistral-7B-Instruct-v0.1' 
// | 'mistralai/Mistral-7B-Instruct-v0.2' 
// | 'deepseek-ai/deepseek-llm-67b-chat' 
// | 'Qwen/Qwen2-72B-Instruct' 
// | 'mistralai/Mistral-7B-Instruct-v0.3' 
// | 'meta-llama/Llama-2-13b-chat-hf' 
// | 'Meta-Llama/Llama-Guard-7b' 
// | 'meta-llama/Meta-Llama-3-70B-Instruct-Lite' 
// | 'google/gemma-2-27b-it' 
// | 'meta-llama/Llama-3-8b-chat-hf' 
// | 'mistralai/Mixtral-8x7B-Instruct-v0.1' 
// | 'microsoft/WizardLM-2-8x22B' 
// | 'meta-llama/Llama-3-70b-chat-hf' 
// | 'llava-hf/llava-v1.6-mistral-7b-hf' 
// | 'databricks/dbrx-instruct' 
// | 'NousResearch/Nous-Hermes-2-Mixtral-8x7B-DPO' 
// | 'meta-llama/Meta-Llama-3-8B-Instruct-Turbo' 
// | 'meta-llama/Meta-Llama-3-8B-Instruct-Lite' 
// | 'Gryphe/MythoMax-L2-13b-Lite' 
// | 'Qwen/Qwen1.5-110B-Chat' 
// | 'meta-llama/Meta-Llama-3-70B-Instruct-Turbo' 
// | 'meta-llama/Llama-2-7b-chat-hf' 
// | 'meta-llama/Meta-Llama-3.1-405B-Instruct-Turbo' 
// | 'Qwen/Qwen1.5-72B-Chat' 
// | 'meta-llama/Llama-3.2-90B-Vision-Instruct-Turbo' 
// | 'Qwen/Qwen2.5-7B-Instruct-Turbo' 
// | 'meta-llama/Llama-Vision-Free' 
// | 'Qwen/Qwen2.5-72B-Instruct-Turbo' 
// | 'google/gemma-2-9b-it' 
// | 'meta-llama/Meta-Llama-3.1-70B-Instruct-Turbo' 
// | 'mistralai/Mixtral-8x22B-Instruct-v0.1' 
// | 'meta-llama/Llama-Guard-3-11B-Vision-Turbo' 
// | 'meta-llama/Llama-3.2-3B-Instruct-Turbo' 
// | 'meta-llama/Meta-Llama-3.1-8B-Instruct-Turbo' 
// | 'meta-llama/Llama-3.2-11B-Vision-Instruct-Turbo' 
// | 'togethercomputer/CodeLlama-34b-Instruct' 
// | 'togethercomputer/llama-2-13b-chat' 
// | 'togethercomputer/llama-2-7b-chat' 
// | 'claude-3-opus-20240229' 
// | 'claude-3-sonnet-20240229' 
// | 'claude-3-haiku-20240307' 
// | 'claude-3-5-sonnet-20240620' 
// | 'claude-3-5-sonnet-20241022' 
// | 'claude-3-5-haiku-20241022' 
// | 'anthropic/claude-3.5-sonnet-20240620' 
// | 'anthropic/claude-3.5-sonnet-20241022' 
// | 'anthropic/claude-3.5-sonnet' 
// | 'claude-3-5-sonnet-latest' 
// | 'anthropic/claude-3-haiku-20240307' 
// | 'anthropic/claude-3-haiku' 
// | 'claude-3-haiku-latest' 
// | 'anthropic/claude-3-opus-20240229' 
// | 'anthropic/claude-3-opus' 
// | 'claude-3-opus-latest' 
// | 'anthropic/claude-3-sonnet-20240229' 
// | 'anthropic/claude-3-sonnet' 
// | 'claude-3-sonnet-latest' 
// | 'anthropic/claude-3-5-haiku-20241022' 
// | 'anthropic/claude-3-5-haiku' 
// | 'claude-3-5-haiku-latest' 
// | 'gpt-4o' 
// | 'gpt-4o-2024-08-06' 
// | 'gpt-4o-2024-05-13' 
// | 'gpt-4o-mini' 
// | 'gpt-4o-mini-2024-07-18' 
// | 'chatgpt-4o-latest' 
// | 'gpt-4-turbo' 
// | 'gpt-4-turbo-2024-04-09' 
// | 'gpt-4' 
// | 'gpt-4-0125-preview' 
// | 'gpt-4-1106-preview' 
// | 'gpt-3.5-turbo' 
// | 'gpt-3.5-turbo-0125' 
// | 'gpt-3.5-turbo-1106' 
// | 'o1-preview' 
// | 'o1-preview-2024-09-12' 
// | 'o1-mini' 
// | 'o1-mini-2024-09-12' 
// | 'gemini-1.5-flash' 
// | 'gemini-1.5-pro'
// | 'gemini-pro' 
// | 'mistralai/mistral-tiny' 
// | 'x-ai/grok-beta' 
// | 'mistralai/mistral-nemo' 
// | 'neversleep/llama-3.1-lumimaid-70b' 
// | 'anthracite-org/magnum-v4-72b' 
// | 'nvidia/llama-3.1-nemotron-70b-instruct' 
// | 'eva-unit-01/eva-qwen-2.5-14b' 
// | 'cohere/command-r-plus' 
// | 'ai21/jamba-1-5-mini'