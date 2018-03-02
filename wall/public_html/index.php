<?php

require_once(__DIR__ . '/../config/config.php');

$app = new Wall\Controller\Index();

$app->run();

// get sentences
$sticky_note_app = new Wall\Model\StickyNote();
$sticky_notes = $sticky_note_app->getAll();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>wall</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/favicon.ico" type="image/vnd.microsoft.icon">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/vnd.microsoft.icon">
    <link rel="apple-touch-icon" sizes="152x152" href="img/apple-touch-icon.png">
  </head>
  <body>
    <div class="box">
      <div id="container">
        <h1><?= h($app->me()->display_name); ?></h1>
        <form action="logout.php" method="post">
          <button class="fas fa-sign-out-alt font-awesome" id="logout" type="submit"></button>
          <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
        </form>
        <div class="clear"></div>
        <form action="" id="new_sticky_note_form">
          <input type="text" id="new_sticky_note" placeholder="...">
          <button class="font-awesome" type="submit">&#xf0fe;</button>
        </form>
        <ul id="sticky_notes">
        <?php foreach ($sticky_notes as $sticky_note) : ?>
          <li id="sticky_note_<?= h($sticky_note->id); ?>" data-id="<?= h($sticky_note->id); ?>">
            <span class="sticky_note_sentence"><?= h($sticky_note->sentence); ?></span>
            <i class="far fa-check-circle font-awesome delete_sticky_note"></i>
          </li>
        <?php endforeach; ?>
          <li id="sticky_note_template" data-id="">
            <span class="sticky_note_sentence"></span>
            <i class="far fa-check-circle font-awesome delete_sticky_note"></i>
          </li>
        </ul>
      </div>
      <input type="hidden" id="token" value="<?= h($_SESSION['token']); ?>">
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="sticky_note.js"></script>
  </body>
</html>
