<?php

require_once(__DIR__ . '/../config/config.php');

$app = new Wall\Controller\Index();

$app->run();

// get sections, sticky_notes
$sticky_note_app = new Wall\Model\StickyNote();
$sections = $sticky_note_app->getSections();
$section_display_name = $sticky_note_app->getSectionDisplayName();
$sticky_notes = $sticky_note_app->getAll();

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
        <h1><?= h($section_display_name['display_name']); ?></h1>
        <ul id="normal" class="dropmenu">
          <li><a href="" id="logout" class="fas fa-bars font-awesome"></a>
            <ul id="menu">
              <?php foreach ($sections as $section) : ?>
              <li class="section" data-id="<?= h($section->section_id); ?>"><?= h($section->display_name) ?></li>
              <?php endforeach; ?>
              <li><a href="manage_sections.php">± sections</a></li>
              <li>
                <form action="logout.php" method="post">
                  <button type="submit">logout</button>
                  <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
                </form>
              </li>
            </ul>
          </li>
        </ul>
        <div class="clear"></div>
        <form action="" id="new_sticky_note_form">
          <input type="text" id="new_sticky_note" placeholder="...">
          <button class="font-awesome" type="submit">&#xf0fe;</button>
        </form>
        <form action="" id="sort" method="post">
        <ul class="sortable" id="sticky_notes">
        <?php foreach ($sticky_notes as $sticky_note) : ?>
          <li id="sticky_note_<?= h($sticky_note->id); ?>" data-id="<?= h($sticky_note->id); ?>" value="<?= h($sticky_note->sentence); ?>">
            <span class="sticky_note_sentence"><?= h($sticky_note->sentence); ?>
              <i class="fas fa-pencil-alt font-awesome update_sticky_note"></i>
            </span>
            <i class="far fa-check-circle font-awesome delete_sticky_note"></i>
          </li>
        <?php endforeach; ?>
          <li id="sticky_note_template" data-id="" value="templete">
            <span class="sticky_note_sentence"></span>
            <i class="far fa-check-circle font-awesome delete_sticky_note"></i>
          </li>
        </ul>
        </form>
      </div>
      <input type="hidden" id="token" value="<?= h($_SESSION['token']); ?>">
    </div>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <!-- <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script> -->
    <script src="jquery-ui-1.11.4/jquery-ui.min.js"></script>
    <script src="jquery.ui.touch-punch.min.js"></script>
    <!-- SweetAlert2(本体) -->
    <script src="sweetalert2.all.js"></script>
    <!-- SweetAlert2(CSS) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.css">
    <!-- SweetAlert2(IE11やAndroidブラウザ用のjsファイル) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <script src="sticky_note.js"></script>
  </body>
</html>
