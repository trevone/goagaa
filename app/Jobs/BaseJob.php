<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BaseJob implements ShouldQueue
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

        $this->log(['input' => $this->input]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }

    public function wasSuccessful(): void
    {

        $this->log(['output'=> $this->output]);
        //LogUpdated::dispatchNow('hello world');

        if(isset($this->input['broadcasting'])){
            $pusher = new Pusher(
                config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                config('broadcasting.connections.pusher.options')
            ); 
            $pusher->trigger('my-channel', 'my-event', ["id" => $this->connector->id, "output" => $content]); 
        }
        if(!isset($this->input['broadcasting']) && !is_null($this->connector->connector_id)){
            $new_connector = Connector::find($this->connector->connector_id);
            $class = 'App\\Jobs\\' . $new_connector->process->class;  
            $class::dispatch($new_connector, $this->post, $this->output )->onQueue("default");
        } 
    }

    public function wasNotSuccessful(): void
    {

    }

    /**
     * General log.
     */
    public function log($data): void
    { 
        $log = new Log;  
        $log->campaign_id = data_get($this->input, 'campaign_id', null);
        $log->connector_id = $this->connector->id;
        $log->data = $data;
        $log->save();
    }
}
