<?php

namespace Wall\Exception;

class InvalidPassword extends \Exception {
  protected $message = 'パスワードの形式が正しくありません。';
}
