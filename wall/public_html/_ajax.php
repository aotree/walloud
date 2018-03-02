<?php

require_once(__DIR__ . '/../config/config.php');

$sticky_note_app = new Wall\Model\StickyNote();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $res = $sticky_note_app->post();
    exit;
  } catch (Exception $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo $e->getMessage();
    exit;
  }
}
