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
            console.log(now_play);
            time_load = data.time;
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
</head>
<body>
  <header>
    <h1>Igarashi's Youtube Watch Party</h1>
  </header>
  <main>
    <div id="player"></div>
    <br>
    <div class="control">
      <button onclick="player.unMute();$('.control button').removeClass('btn_active');">ミュート解除（動画が始まったら押してね！）</button>
    </div>
    <p class="notes">
      ご利用方法：<br>
      伊達のライブ配信中に同じ動画を見るためのツールです。<br>
      配信中には動画の再生が自動的に始まりますが、Googleの仕様上都度ミュートになります。<br>
      始まったらミュートを解除しましょう。<br>
      途中参加の方は自動的に再生位置までシークされるのでそのまま視聴できます。<br>
    </p>
  </main>

  <footer>Powered by <a href="https://www.youtube.com/c/503badgateway" target="_blank">503 bad gateway</a></footer>
</body>
</html>
