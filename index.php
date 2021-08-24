<?php
  include_once("lib/util.php");

?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Igarashi's Youtube Watch Party</title>
  <link rel="stylesheet" href="<?php echo_filedate("./css/style.css");?>">

  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script src="https://www.youtube.com/iframe_api"></script>
  <script>
  // 2. This code loads the IFrame Player API code asynchronously.
  var tag = document.createElement('script');
  var time_open = <?php echo h_(time());?>;
  var time_load = 0;

  /* //////////////////////////////
  ここを編集して使う
  ////////////////////////////// */
  var tweet_hash = "503bad,deathconnected";//ツイートボタンで付加するハッシュタグをカンマ区切りで入力
  var tweet_user = "503_bad";//ツイート時のメンション設定
  var tweet_message = "メタルMVを同時視聴中";//ツイートの文面

  tag.src = "https://www.youtube.com/iframe_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  // 3. This function creates an <iframe> (and YouTube player)
  //    after the API code downloads.
  var player;
  function onYouTubeIframeAPIReady(id) {

  }

  // 4. The API will call this function when the video player is ready.
  function onPlayerReady(event) {
    event.target.mute();
    event.target.playVideo();
    $(".control button").addClass("btn_active");

    // setTimeout(function(){click_event();},2000);
    // event.setVolume(1);
  }

  // 5. The API calls this function when the player's state changes.
  //    The function indicates that when playing a video (state=1),
  //    the player should play for six seconds and then stop.
  var done = false;
  function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.PLAYING && !done) {
      console.log(time_load-time_open);
      player.seekTo(time_open-time_load);
      done = true;
    }
  }
  function stopVideo() {
    player.stopVideo();
  }

  function unmute_loop(){
    player.unMute();
    setTimeout(function(){
      unmute_loop();
    },1000);
  }



  var now_play = "";
  $(function(){
    check_state();
  });

  function check_state(){
    setTimeout(function(){
      $.ajax({
        type: 'GET',
        dataType: 'json',
        "url":"proxy/ctl.php",
        success: function(data) {
          if(now_play != data.url){
            now_play = data.url;
            time_load = data.time;

            var temp_text = encodeURI("https://www.youtube.com/watch?v="+now_play);
            $("#tw_icon").attr("href","https://twitter.com/intent/tweet?via="+tweet_user+"&hashtags="+tweet_hash+"&url="+temp_text+"&text="+encodeURI(tweet_message));
            $(".live_at").attr("href","https://www.youtube.com/watch?v="+data.live);

            if(!player){
              player = new YT.Player('player', {
                height: '360',
                width: '640',
                videoId: now_play,
                playerVars: { 'autoplay': 50},
                events: {
                  'onReady': onPlayerReady,
                  'onStateChange': onPlayerStateChange
                }
              });


            }else{
              player.mute();

              player.stopVideo();
              player.cueVideoById(now_play,0);
              player.playVideo();
              $(".control button").addClass("btn_active");

              // setTimeout(function(){click_event();},2000);
            // setTimeout(function(){player.playVideo();},5000);
            }
          }
        },
        error: function(data){
          console.log("error");
        }
      });

      check_state();
    },1000);
  }


  </script>

  <style>
    .overlay{
      position: fixed;
      width:100%;
      height: 100%;
      top:0px;
      left:0px;
      background-color: rgba(0,0,0,0.7);
    }

    .overlay button{
      position: fixed;
      top:calc(50% - 20px);
      width:200px;
      left:calc(50% - 100px);
    }

    .live_at{
        float:right;

    }
  </style>
</head>
<body>
  <header>
    <h1>Igarashi's Youtube Watch Party</h1>
  </header>
  <main>
    <div id="player"></div>
    <p class="notes">
      <a id="tw_icon" class="tw_icon" target="_blank"><img src="./img/tw_icon.png"><span>再生中の動画をシェア</span></a>
      <a class="live_at" href="" target="_blank">同時視聴ライブ配信はこちら</a>
    </p>
    <p class="notes">
      ご利用方法：<br>
      同じ動画を同時に見るためのツールです。<br>
      途中参加の方は自動的に再生位置までシークされるのでそのまま視聴できます。<br>
      再生位置がずれたらブラウザのリロードをしましょう。
    </p>
  </main>

  <footer>"Igarashi's Youtube Watch Party" (C)<a href="https://www.youtube.com/c/503badgateway" target="_blank">ヘヴィメタルバンド "503 bad gateway"</a></footer>

  <div class="overlay">
    <button class="btn_active" onclick="unmute_loop();$('.overlay').remove();">視聴開始</button>
  </div>
</body>
</html>
