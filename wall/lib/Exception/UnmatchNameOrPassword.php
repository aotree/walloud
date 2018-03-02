<?php

namespace Wall\Exception;

class UnmatchNameOrPassword extends \Exception {
  protected $message = 'ウォール名もしくはパスワードが異なります';
}
