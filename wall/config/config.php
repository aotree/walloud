<?php

ini_set('display_errors', 1); // ブラウザにエラー表示

define('DSN', 'mysql:host=wall-utf8mb4.ce8ynb2r1wnl.ap-northeast-1.rds.amazonaws.com;dbname=wall');
define('DB_USERNAME', 'dbuser');
define('DB_PASSWORD', 's1kasika');

define('SITE_URL', 'https://' . $_SERVER['HTTP_HOST']);

require_once(__DIR__ . '/../lib/functions.php');
require_once(__DIR__ . '/autoload.php');

session_start();
