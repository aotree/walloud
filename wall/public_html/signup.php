<?php

// 新規登録

require_once(__DIR__ . '/../config/config.php');

$app = new Wall\Controller\Signup();

$app->run();

?>
<!DOCTYPE html>
<html>
  <head
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>wall</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/signup.css">
    <link rel="icon" href="img/favicon.ico" type="image/vnd.microsoft.icon">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/vnd.microsoft.icon">
    <link rel="apple-touch-icon" sizes="152x152" href="img/apple-touch-icon.png">
  </head>
  <body>
    <div id="box">
      <div id="container">
        <div id="title">
          <p>wall</p>
        </div>
        <div class="signup_area">
          <h1>sign up</h1>
          <form id="signup" action="" method="post">
            <div class="tooltip">
              <span>
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
              <span>
                ウォールのタイトルとして画面上に表示する名前です。<br>
                (1~20文字 ※全角は2文字分)
              </span>
            </div>
            <p>
              <input id="display_name" class="text_area" type="text" name="display_name" placeholder="wall display name" value="<?= isset($app->getValues()->display_name) ? h($app->getValues()->display_name) : ''; ?>">
            </p>
            <p class="error"><?= h($app->getErrors('display_name')) ?></p>
            <div class="tooltip">
              <span>
                パスワードのリセット(※今後実装予定)に使う管理者用のメールアドレスです。<br>
                (xxx@xxx.xxx)
              </span>
            </div>
            <p>
              <input id="email" class="text_area" type="text" name="email" placeholder="email" value="<?= isset($app->getValues()->email) ? h($app->getValues()->email) : ''; ?>">
            </p>
            <p class="error"><?= h($app->getErrors('email')) ?></p>
            <div class="tooltip">
              <span>
                ウォールのタイトルとして表示する名前です。<br>
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
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="signup.js"></script>
  </body>
</html>
