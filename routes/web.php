<?php

use Carbon\Carbon;
use Inertia\Inertia;
use Facebook\Facebook;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

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
Route::get('/fb', function () {
    $fb = new Facebook([
        'app_id' => '1497024704346160',
        'app_secret' => 'EAAVRiTm4ZBDABOwQa35zFfTAlKq97DJRSZCfgyiQAoC3exLRVyl0OtQYENKxOzjdYod14HbbXkq2rE0rrsj5GGz3i0Fc8g3ChlP4hnZCqISs5CPuumZA28Mhia5aw5HrK16D2mbumjR98rfZBsCvcv2LTNaoelyJNaEx4d1TcJhvmIQqodH47huWm5KzykAOyBYLNeVnTZCzRSWTZBQ4AELeFCQ9rQu7Xko',
        'default_graph_version' => 'v2.10',
        ]);
      
      $helper = $fb->getRedirectLoginHelper();
      
      $permissions = ['email']; // Optional permissions
      $loginUrl = $helper->getLoginUrl('https://goayaa.com/fb-callback', $permissions);
      
      echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
}); 
Route::get('/fb-callback', function () {
    $fb = new Facebook([
        'app_id' => '{app-id}',
        'app_secret' => '{app-secret}',
        'default_graph_version' => 'v2.10',
        ]);
      
      $helper = $fb->getRedirectLoginHelper();
      
      try {
        $accessToken = $helper->getAccessToken();
      } catch(Facebook\Exception\ResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
      } catch(Facebook\Exception\SDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
      }
      
      if (! isset($accessToken)) {
        if ($helper->getError()) {
          header('HTTP/1.0 401 Unauthorized');
          echo "Error: " . $helper->getError() . "\n";
          echo "Error Code: " . $helper->getErrorCode() . "\n";
          echo "Error Reason: " . $helper->getErrorReason() . "\n";
          echo "Error Description: " . $helper->getErrorDescription() . "\n";
        } else {
          header('HTTP/1.0 400 Bad Request');
          echo 'Bad request';
        }
        exit;
      }
      
      // Logged in
      echo '<h3>Access Token</h3>';
      var_dump($accessToken->getValue());
      
      // The OAuth 2.0 client handler helps us manage access tokens
      $oAuth2Client = $fb->getOAuth2Client();
      
      // Get the access token metadata from /debug_token
      $tokenMetadata = $oAuth2Client->debugToken($accessToken);
      echo '<h3>Metadata</h3>';
      var_dump($tokenMetadata);
      
      // Validation (these will throw FacebookSDKException's when they fail)
      $tokenMetadata->validateAppId($config['app_id']);
      // If you know the user ID this access token belongs to, you can validate it here
      //$tokenMetadata->validateUserId('123');
      $tokenMetadata->validateExpiration();
      
      if (! $accessToken->isLongLived()) {
        // Exchanges a short-lived access token for a long-lived one
        try {
          $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        } catch (Facebook\Exception\SDKException $e) {
          echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
          exit;
        }
      
        echo '<h3>Long-lived</h3>';
        var_dump($accessToken->getValue());
      }
      
      $_SESSION['fb_access_token'] = (string) $accessToken;
      
      // User is logged in with a long-lived access token.
      // You can redirect them to a members-only page.
      //header('Location: https://example.com/members.php');
}); 
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');
});

Route::get('/news', function () {
    $guzzle = new Client(['base_uri' => 'https://api.worldnewsapi.com']);
    $search = urlencode("social media platforms");
    $today = Carbon::now()->format('Y-m-d');
    $raw_response = $guzzle->get('/search-news?text='.$search.'&earliest-publish-date='.$today.'&language=en', [
        'headers' => [ 
            'x-api-key' => '08abbd6f105247b4a47e93133b642a32', 
            'Content-Type' => 'application/json'
        ], 
    ]);

    $response = $raw_response->getBody()->getContents();
    $res_obj = json_decode($response); 

    foreach($res_obj->news as $n){
        echo $n->publish_date . ' '.$n->title . '<br>';
    }

    // title
    // text
    // summary
    // url
    // image
    // video
    // publish_date
});

Route::get('/test', function () {
    $guzzle = new Client(['base_uri' => 'https://api.aimlapi.com']);

    $data = (object) [
        "model" => "gpt-4o",
        "messages" =>   [ 
            (object) [
                "role"=> "user",
                "content"=> "give me short article on best practice for hammer and nail?"
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
    $res_obj = json_decode($response);
    dd($res_obj->choices[0]->message->content);
});


Route::get('/image', function () {
    $guzzle = new Client(['base_uri' => 'https://api.aimlapi.com']);

    $data = (object) [
        
        "prompt"=> "fluffy dog",  
        "n"=> 1, 
        "response_format"=> "url",
        "size"=> "1024x1024", 
    ];
    
   
    try {
        $raw_response = $guzzle->post('/images/generations', [
            'headers' => [ 
                'Authorization' => 'Bearer b135f89da42847e78bebcb7393e6a6cc',
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($data),
        ]);
    } catch (\GuzzleHttp\Exception\RequestException $ex) {
        dd($ex->getResponse()->getBody()->getContents());
    }

    $response = $raw_response->getBody()->getContents();
    $res_obj = json_decode($response);
    
});