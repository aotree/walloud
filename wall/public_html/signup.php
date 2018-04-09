<?php

require_once(__DIR__ . '/../config/config.php');

$app = new Wall\Controller\Signup();

$app->run();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>walloud</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/signup.css">
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
          <p>walloud</p>
        </div>
        <div class="signup_area">
          <p id="description">　</p>
          <!-- <h1>sign up</h1> -->
          <form id="signup" action="" method="post">
            <div class="tooltip">
              <span id="span_name">
                ログインの際に使う名前です。<br>
                既に登録されている名前は使えません。<br>
                (半角英数字と記号(-_@.)で1~100文字)
              </span>
            </div>
            <p>
              <input id="name" class="text_area" type="text" name="name" placeholder="wall name" value="<?= isset($app->getValues()->name) ? h($app->getValues()->name) : ''; ?>">
            </p>
            <p class="error"><?= h($app->getErrors('name')) ?></p>
            <div class="tooltip">
              <span id="span_display_name">
                最初のセクションのタイトルとして画面上に表示する名前です。<br>
                セクションは1つのウォールに複数作成できます。<br>
                (1~20文字 ※全角は2文字分)
              </span>
            </div>
            <p>
              <input id="display_name" class="text_area" type="text" name="display_name" placeholder="1st section display name" value="<?= isset($app->getValues()->display_name) ? h($app->getValues()->display_name) : ''; ?>">
            </p>
            <p class="error"><?= h($app->getErrors('display_name')) ?></p>
            <div class="tooltip">
              <span id="span_email">
                パスワードのリセットに使う管理者用のメールアドレスです。<br>
                (xxx@xxx.xxx)
              </span>
            </div>
            <p>
              <input id="email" class="text_area" type="text" name="email" placeholder="email" value="<?= isset($app->getValues()->email) ? h($app->getValues()->email) : ''; ?>">
            </p>
            <p class="error"><?= h($app->getErrors('email')) ?></p>
            <div class="tooltip">
              <span id="span_password">
                ログインの際に使うパスワードです。<br>
                (半角英数字で8~100文字)
              </span>
            </div>
            <p>
              <input id="password" class="text_area" type="password" name="password" placeholder="password">
            </p>
            <p class="error"><?= h($app->getErrors('password')) ?></p>
            <div class="button" onclick="document.getElementById('signup').submit();"><button>Sign Up</button></div>
            <div id="login"><a href="/login.php">Log In</a></div>
            <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
          </form>
          <div id="sns_buttons">
            <div id="my_tw">
              <!-- <a href="javascript:ImageUp();" target="_blank" class="twitter-hashtag-button" data-text="wall - どこからでも見られる壁がここにあります。プロジェクトチームの掲示板、家族の伝言板、To-doリスト、好きな音楽のリスト...使い方はあなた次第です！
https://wall.aotree.blue" data-show-count="false"></a>
              <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script> -->
            </div>
            <div id="my_fb">
              <!-- <div class="fb-like" data-href="https://wall.aotree.blue" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div> -->
            </div>
          </div>
          <div id="footer">
            <!-- <a href="https://wall.aotree.blue/use_case.html" target="_blank">how to use?</a>
            |
            <a href="http://aotree.blue" target="_blank">aoki hiroki's HP</a>
            |
            <a href="mailto:aotree423_it@yahoo.co.jp">contact</a> -->
            <a href="mailto:aotree423_it@yahoo.co.jp?subject=[wall ****] ****&amp;body=<パスワード再設定 -Resetting a password- >%0d%0a特定のウォールアカウントのパスワードを忘れてしまった際は、%0d%0a- 件名 : [wall パスワード再設定] 対象のwall name%0d%0a- 本文 : 新しいパスワード : 任意のパスワード(半角英数字で8~100文字)%0d%0aとして、登録したメールアドレスから上記の宛先までメールの送付をお願いします。%0d%0a%0d%0aIf you forget the password for your specific wall account, please reply in the following format from the registered e-mail address.%0d%0a- Subject : [wall Resetting a password] specific wall name for which you want to change the password%0d%0a- Content : new password(8~100 letters)%0d%0a%0d%0a%0d%0a<ウォールアカウント削除 -Delete a wall account- >%0d%0a特定のウォールアカウントを削除したい際は、%0d%0a- 件名 : [wall ウォールアカウント削除] 対象のwall name%0d%0a- 本文 : 空%0d%0aとして、登録したメールアドレスから上記の宛先までメールの送付をお願いします。%0d%0a%0d%0aIf you want to delete your specific wall account, please reply in the following format from the registered e-mail address.%0d%0a- Subject : [wall Delete a wall account] specific wall name for which you want to delete%0d%0a- Content : empty%0d%0a%0d%0a%0d%0a<お問い合わせ -Inquiry- >%0d%0aお問い合わせの際は、%0d%0a- 件名 : [wall お問い合わせ] お問い合わせの概要%0d%0a- 本文 : お問い合わせの内容%0d%0aとして、登録したメールアドレスから上記の宛先までメールの送付をお願いします。%0d%0a%0d%0aIf you want to make some inquiry, please reply in the following format from the registered e-mail address.%0d%0a- Subject : [wall Inquiry] outline of inquiry%0d%0a- Content : contents of inquiry">Reset password | Delete walls | Contact me</a>
            <p>&copy; 2018 AOKI HIROKI</p>
          </div>
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="signup.js"></script>
  </body>
</html>
