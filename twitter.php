<?php
//Grab the library
require_once('TwitterAPIExchange.php');

//If you don't set a query, this won't return a thing
if(isset($_GET['q'])){
    $query = $_GET['q'];
    
    $settings = array(
        'oauth_access_token' => "14758647-LnHzlhZtNW5lJlwdv2oWhjLnBicTjhzdg9AhafEs",
        'oauth_access_token_secret' => "QsHt7YbhkWiRORvXnvtQoOKzmbZOCzz2L2quORjM7E",
        'consumer_key' => "jqVBfm8vx1vaqqfRr2aUdg",
        'consumer_secret' => "mFJofe8bj1Wcb4UGAwhreBEbqCmavwZlwXMvLzCWRhc"
    );
    //Endpoint URL we are going to get the tweets from
   $url = 'https://api.twitter.com/1.1/search/tweets.json';
   //Add the query
    $getfield = '?q='.$query;
    //There is a ton of cool filters you can use, like this one below to set the language of the tweets
//   $getfield .= '&lang=nl';
    //Be sure to check out https://dev.twitter.com/rest/reference/get/search/tweets
    
    //This is a GET request
   $requestMethod = 'GET';

    //Making the actual call
   $twitter = new TwitterAPIExchange($settings);
   $response = $twitter->setGetfield($getfield)
   ->buildOauth($url, $requestMethod)
   ->performRequest();

    //Showing the data returning
   echo $response;
   }
?>