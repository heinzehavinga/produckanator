<!DOCTYPE html>
<html>

<head>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
        }
        
        #soundcloud {
            position: absolute;
            height: 150px;
            width: 300px;
            top: 10px;
            left: 5px;
        }
        
        #giphy {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -100;
        }
        
        #tweets {
            width: 960px;
            height: 300px;
            margin: 20% auto;
            font-family: Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace;
            text-align: center;
            color: white;
            font-size: 2.5em;
            font-weight: bold;
        }
        
        #form {
            position: absolute;
            right: 0;
            top: 0;
        }
    </style>
</head>

<body>
    <div>
        <form id="form">
            <input id="search" name="search" type="text" value="robots" /> </form>
    </div>
    <div id="soundcloud"></div>
    <div id="giphy"></div>
    <div id="tweets"></div>
    <!--    Jquery because quick DOM things are fun-->
    <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <!--    Soundcloud SDK for soundcloud things-->
    <script src="https://connect.soundcloud.com/sdk/sdk-3.1.2.js"></script>
    <!--    Soundcloud player api, so we can listen to events in the player -->
    <script src="https://w.soundcloud.com/player/api.js"></script>
    <script>
        //Jquery tijd!
        var soundCloudKey = 'fdf47dd2dfcc83a5d11ab71942e4c476';
        var giphyKey = 'fdf47dd2dfcc83a5d11ab71942e4c476';
        var trackList;
        var curSoundIndex = 0;
        var widgetTest;
        var tweetInterval,tweets;
        var curTweetIndex = 0;
        var msg = new SpeechSynthesisUtterance();
        msg.volume = 1;
//        msg.lang = 'nl-NL';
        
        //cleanup regex
        var patt = /RT|:|@|#|http\S*/g;
        
        $(function () {
            
            //Sets up soundCloud
            SC.initialize({
                client_id: 'fdf47dd2dfcc83a5d11ab71942e4c476'
            });
            
            //Fire the first one for free!
            goCrazy($("#search").val());
            
            //Fire the produckanator every time you fire the form.
            $("form").submit(function (e, data) {
                e.preventDefault();
                goCrazy($("#search").val());
            })
        });

        //New round of the produckanator
        function goCrazy(text) {
            //Get tweets
            $.getJSON("twitter.php", {
                q: text
             }).done(function (data) {
                
                tweets = data.statuses;
                clearInterval(tweetInterval);
                tweetInterval = setInterval(function(){ 
                                        widgetTest.setVolume(0.5);
                                        var tweet = tweets[curTweetIndex].text.replace(patt,"")
                                        $("#tweets").text(tweet);
                                        msg.text = tweet;
                                        window.speechSynthesis.speak(msg);
                                        curTweetIndex++;
                                        if (curTweetIndex >= tweets.length) {
                                            curTweetIndex = 0;
                                        }
                                        }, 12000);
            });
            
            
            //Get gifs!
            $.getJSON("http://api.giphy.com/v1/gifs/search", {
                q: text
                , api_key: "dc6zaTOxFJmzC"
            , }).done(function (data) {
                $("#giphy").html("<img src='" + data.data[0].images.original.url + "' width='100%' height='100%'/>");
            });
            
            // find 120bpm instrumental tracks with a cc-by-sa license
            SC.get('/tracks', {
                genres: 'instrumental'
                , bpm: {
                    from: 120
                }
                , license: 'cc-by-sa'
            }).then(function (tracks) {
                trackList = tracks;
                
                setupPlayer(trackList[curSoundIndex]);
            });
        }
        
        //Setting up SoundCloud player
        function setupPlayer(track) {
            var track_url = track.permalink_url;
            SC.oEmbed(track_url, {
                auto_play: true
            }).then(function (oEmbed) {
                $("#soundcloud").html(oEmbed.html);
                $("#soundcloud iframe").attr("height", "100%")
                
                //Adding a listener object so we can start a new tracks once one is over.
                var iframeElement = document.querySelector('iframe');
                widgetTest = SC.Widget(iframeElement);
                
                widgetTest.bind(SC.Widget.Events.FINISH, nextTrack);                
                
                curSoundIndex++;
                if (curSoundIndex >= trackList.length()) {
                    curSoundIndex = 0;
                }
            });

        }

        function nextTrack(e) {
            setupPlayer(trackList[curIndex]);
        }
    </script>
</body>

</html>