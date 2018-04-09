<?php

namespace Wall\Exception;

class ThirdEmail extends \Exception {
  protected $message = 'すでに2つのウォールが登録されているメールアドレスです。';
}
