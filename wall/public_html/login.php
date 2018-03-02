<?php

// ログイン

require_once(__DIR__ . '/../config/config.php');

$app = new Wall\Controller\Login();

$app->run();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>wall</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="img/favicon.ico" type="image/vnd.microsoft.icon">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/vnd.microsoft.icon">
    <link rel="apple-touch-icon" sizes="152x152" href="img/apple-touch-icon.png">
  </head>
  <body>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = 'https://connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.12';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <div id="box">
      <div id="container">
        <div id="title">
          <p>wall</p>
        </div>
        <div class="login_area">
          <p id="description">どこからでも見られる壁が、ここに</p>
          <!-- <h1>log in</h1> -->
          <form id="login" action="" method="post">
            <p>
              <input class="text_area" type="text" name="name" placeholder="wall name" value="<?= isset($app->getValues()->name) ? h($app->getValues()->name) : ''; ?>">
            </p>
            <p>
              <input class="text_area" type="password" name="password" placeholder="password">
            </p>
            <p class="error"><?= h($app->getErrors('login')); ?></p>
            <div class="button" onclick="document.getElementById('login').submit();"><button>Log In</f></div>
            <div id="signup"><a href="/signup.php">Sign Up</a></div>
            <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
          </form>
          <div id="sns_buttons">
            <div id="my_tw">
              <a href="javascript:ImageUp();" target="_blank" class="twitter-hashtag-button" data-text="wall - どこからでも見られる壁がここにあります。プロジェクトチームの掲示板、家族の伝言板、To-doリスト、好きな音楽のリスト...使い方はあなた次第です！
https://wall.aotree.blue" data-show-count="false"></a>
              <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>
            <div id="my_fb">
              <div class="fb-like" data-href="https://wall.aotree.blue" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
            </div>
          </div>
          <div id="footer">
            <a href="https://wall.aotree.blue/use_case.html" target="_blank">how to use?</a>
            |
            <a href="http://aotree.blue" target="_blank">aoki hiroki's HP</a>
            |
            <a href="mailto:aotree423_it@yahoo.co.jp">contact</a>
            <p>&copy; 2018 AOKI HIROKI</p>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
