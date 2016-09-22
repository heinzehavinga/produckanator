<?php
if(isset($_GET['q'])){
    $query = $_GET['q'];

    require_once('TwitterAPIExchange.php');
    $settings = array(
        'oauth_access_token' => "14758647-LnHzlhZtNW5lJlwdv2oWhjLnBicTjhzdg9AhafEs",
        'oauth_access_token_secret' => "QsHt7YbhkWiRORvXnvtQoOKzmbZOCzz2L2quORjM7E",
        'consumer_key' => "jqVBfm8vx1vaqqfRr2aUdg",
        'consumer_secret' => "mFJofe8bj1Wcb4UGAwhreBEbqCmavwZlwXMvLzCWRhc"
    );

   $url = 'https://api.twitter.com/1.1/search/tweets.json';
   $getfield = '?q='.$query;
//   $getfield .= '&lang=nl';
    
   $requestMethod = 'GET';

   $twitter = new TwitterAPIExchange($settings);
   $response = $twitter->setGetfield($getfield)
   ->buildOauth($url, $requestMethod)
   ->performRequest();

   echo $response;
   }
?>