<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PostFacebook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $text;

    /**
     * Create a new job instance.
     */
    public function __construct($text)
    {
        $this->text = $text;
        //1497024704346160
        // EAAVRiTm4ZBDABOwQa35zFfTAlKq97DJRSZCfgyiQAoC3exLRVyl0OtQYENKxOzjdYod14HbbXkq2rE0rrsj5GGz3i0Fc8g3ChlP4hnZCqISs5CPuumZA28Mhia5aw5HrK16D2mbumjR98rfZBsCvcv2LTNaoelyJNaEx4d1TcJhvmIQqodH47huWm5KzykAOyBYLNeVnTZCzRSWTZBQ4AELeFCQ9rQu7Xko
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $access_token = "EAAIHTtbEnHkBO1Id3t1GFJPVsOepKuQ5delPniuCAPXCH3uAuNHo6tMjE5DmKSK3jYgkhE8fHDVZC1i4KlVbN6gsAEnjJBuZAdCYxAmbYXEZAbtHJKz5kaufMa6fbCqCucTBgEdsXwmzKQgJMHEOuhmrw7CRhdYJiZAY5mzzgXZBF5bS8YwsFFGXGGbXQLAg7uy8NujMt";

        $client = new Client(['base_uri' => 'https://graph.facebook.com/v21.0/']);
        // Send a request to https://foo.com/api/test 

        try { 
            // $response = $client->request('GET', '/oauth/access_token', [
            //     'query' => [
            //         'fb_exchange_token' => $access_token,
            //         'grant_type' => "fb_exchange_token",
            //         'client_id' => '570985145474169',
            //         'client_secret' => '506cec226ce6d4a8bfb82a74da405b11',
            //     ]
            // ]);
            // $response_json = json_decode( $response->getBody());

            $long_lived_access_token = $access_token;

            $response = $client->request('POST', '488087137720800/feed', [
                'query' => [
                    'access_token' => $long_lived_access_token,
                    'message' => $this->text,
                    'published' => true
                ]
            ]);
            $response_body = (string)$response->getBody();
            \Log::debug($response_body);
            
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            echo $ex->getResponse()->getBody()->getContents() ;
        }
    }
}
