<?php

require_once(__DIR__ . '/../config/config.php');

$app = new Wall\Controller\Index();

$app->run();

// get sections
$sticky_note_app = new Wall\Model\StickyNote();
$sections = $sticky_note_app->getSections();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>walloud</title>
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
        <h1>± sections</h1>
        <ul id="normal" class="dropmenu">
          <li><a href="" id="logout" class="fas fa-bars font-awesome"></a>
            <ul id="sections">
              <?php foreach ($sections as $section) : ?>
              <li class="section" id="section_<?= h($section->section_id); ?>" data-id="<?= h($section->section_id); ?>"><?= h($section->display_name) ?></li>
              <?php endforeach; ?>
              <li class="special" id="li_section"><a href="">± sections</a></li>
              <li class="special">
                <form action="logout.php" method="post">
                  <button type="submit">logout</button>
                  <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
                </form>
              </li>
              <li class="section" id="section_templete" data-id=""></li>
            </ul>
          </li>
        </ul>
        <div class="clear"></div>
        <form action="" id="new_sticky_note_form">
          <input type="text" id="new_sticky_note" placeholder="display name">
          <button class="font-awesome" type="submit">&#xf0fe;</button>
        </form>
        <ul id="sticky_notes">
        <?php foreach ($sections as $section) : ?>
          <li id="sticky_note_<?= h($section->section_id); ?>" data-id="<?= h($section->section_id); ?>">
            <span class="sticky_note_sentence" style="background: #ff9"><?= h($section->display_name); ?>
              <i class="fas fa-pencil-alt font-awesome update_sticky_note" style="color: #fc0 !important"></i>
            </span>
            <i class="far fa-check-circle font-awesome delete_sticky_note"></i>
          </li>
        <?php endforeach; ?>
          <li id="sticky_note_template" data-id="">
            <span class="sticky_note_sentence" style="background: #ff9">
              <i class="fas fa-pencil-alt font-awesome update_sticky_note" style="color: #fc0 !important"></i>
            </span>
            <i class="far fa-check-circle font-awesome delete_sticky_note"></i>
          </li>
        </ul>
      </div>
      <input type="hidden" id="token" value="<?= h($_SESSION['token']); ?>">
    </div>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <!-- SweetAlert2(本体) -->
    <script src="sweetalert2.all.js"></script>
    <!-- SweetAlert2(CSS) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.css">
    <!-- SweetAlert2(IE11やAndroidブラウザ用のjsファイル) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <script src="manage_sections.js"></script>
  </body>
</html>
