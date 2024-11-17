<?php

use Carbon\Carbon;
use Pusher\Pusher;
use Inertia\Inertia;
use Facebook\Facebook;
use GuzzleHttp\Client;
use App\Models\Campaign;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Resources\Campaign\CampaignEdit;
use App\Http\Controllers\Auth\FacebookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
}); 
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
 
Route::get('login/facebook', [FacebookController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('login/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);
Route::get('/test', function () {
    $campaigns = Campaign::all();

    foreach($campaigns as $campaign){ 
        
        $connectors = $campaign->connectors ;
      
        foreach($campaign->connectors  as $connector){
            dd($connector->process);
            $class = "App\Jobs\{$connector->process->class}";

                $class::dispatch($campaign)->onQueue("default");
        } 
    }
});
Route::get('/broadcast', function() {
    
    // New Pusher instance with our config data
    $pusher = new Pusher(
        config('broadcasting.connections.pusher.key'),
        config('broadcasting.connections.pusher.secret'),
        config('broadcasting.connections.pusher.app_id'),
        config('broadcasting.connections.pusher.options')
    );
        
     
        
    // Your data that you would like to send to Pusher
    $data = ['text' => 'hello world from Laravel 5.34'];
        
    // Sending the data to channel: "test_channel" with "my_event" event
    $pusher->trigger( 'my-channel', 'my-event', $data);
        
    return 'ok'; 
});
Route::get('/exchange-longlived', function () {
    //from graph explorer
    //$access_token = "EAAIHTtbEnHkBO8rwrqyjfj4qoJASitSYIyOtQ35s9b4hB9aixOPZBv1ghHcz4J2ZBK9tXaz0R4lJZCAvIZCZAWfvTZCfrMJRIjzOe9IA5KZCzSzWFey04CljCV8PlF3ogCEdOQT67xM8TWYB7q9lCMJXXmakwZCUtrjltlZBbbitqNDRsZCymEusaiS3Yp4CbNyIBiLNZAm1MEWXUvOkKOF3nqKzVOH3Ri4ZBGHL";
    $access_token = "EAAIHTtbEnHkBO4i0kWPjZAB3r5aukQoXouj1R0D82aTd1ZCrhSZCUJiTD64kQwdA3iENhCOjLiGXcdlhEHTS484VwnSrfhoQk4V2ZAkAbAwrFmZBxeGWnnPr7JrCPfOusLA8UCxn6nGqrrjp27Kxk59x0PtesswbDeCbppiUOCDaDEdHPbONULOzZADcIVL47ZCHyiggZAGufgZCKyjxI0wWDtf2Ru2lNyRnz";

    $client = new GuzzleHttp\Client(['base_uri' => 'https://graph.facebook.com/v21.0/']);
    // Send a request to https://foo.com/api/test 

    try { 
        $response = $client->request('GET', '/oauth/access_token', [
            'query' => [
                'fb_exchange_token' => $access_token,
                'grant_type' => "fb_exchange_token",
                'client_id' => '570985145474169',
                'client_secret' => '506cec226ce6d4a8bfb82a74da405b11',
            ]
        ]);
        $response_json = json_decode( $response->getBody());

        $long_lived_access_token = $response_json->access_token;
        echo $long_lived_access_token;

        // $response = $client->request('POST', '488087137720800/feed', [
        //     'query' => [
        //         'access_token' => $long_lived_access_token,
        //         'message' => "long lived token",
        //         'published' => true
        //     ]
        // ]);
        // $response_body = (string)$response->getBody();
         
    } catch (\GuzzleHttp\Exception\RequestException $ex) {
        echo $ex->getResponse()->getBody()->getContents() ;
    }
    /*
    try { 
        $response = $client->request('POST', '488087137720800/feed', [
            'query' => [
                'access_token' => $access_token,
                'message' => "testing the graph api",
                'published' => true
            ]
        ]);
        $response_body = (string)$response->getBody();
        var_dump($response_body);
    } catch (\GuzzleHttp\Exception\RequestException $ex) {
        echo $ex->getResponse()->getBody()->getContents() ;
    }*/
   

    // Read Response
    
});

//https://graph.facebook.com/v21.0/user_id/accounts?access_token=user_access_token
//EAAIHTtbEnHkBO2s9mEHqhdZBZAy2ZAwTZAQ30QgLZBslC2YUCidhTiCvAQn5ZBU5ZCqcT4xZAZAo3IoE69dlwGiUIRuKCsBVoZBdy7iFiOS5FPTXXmXgBcTJkc51zjK8lQhgdC4JbD2TL5ksd8uYFBNUHvC9aSj8yuNPvsB7lPc1e6XIVk3tsnmq2VPYtoTE4adm1I4k6GD6otiRWQKPUYt83VOFZAxCQZDZD