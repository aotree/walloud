<?php

spl_autoload_register(function($class) {
  $prefix = 'Wall\\'; // 一つ目のバックスラッシュはエスケープシーケンス
  if (strpos($class, $prefix) === 0) {
    $className = substr($class, strlen($prefix));
    $classFilePath = __DIR__ . '/../lib/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($classFilePath)) {
      require $classFilePath;
    }
  }
});
