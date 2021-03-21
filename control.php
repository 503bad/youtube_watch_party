<?php
  include_once("lib/util.php");
  if(!empty($_POST['url'])){
    $fp = fopen("./proxy/nowplay.json","w");
    fputs(
      $fp,
      json_encode(array(
        "url"=>$_POST['url'],
        "time"=>time()
      ))
    );
  }
?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="<?php echo_filedate("./css/style.css");?>">
  <title>Youtube Watch Party Control</title>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script>

  </script>
</head>
<body>
  <h1>Youtube Watch Party Control</h1>
  <form action="" method="post">
    <input class="submit_id" name="url" placeholder="Input url" value="<?php echo h_($_POST['url']); ?>"><input type="submit" value="Submit">
    <br>
    <textarea class="memo_box" name="memo" placeholder="Memo"><?php echo h_($_POST['memo']); ?></textarea>

  </form>
</body>
</html>
