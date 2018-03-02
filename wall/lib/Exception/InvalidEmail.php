<?php

namespace Wall\Exception;

class InvalidEmail extends \Exception {
  protected $message = 'Emailの形式が正しくありません。';
}
