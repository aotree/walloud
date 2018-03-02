<?php

namespace Wall\Exception;

class DuplicateName extends \Exception {
  protected $message = 'すでに登録されているウォール名です。';
}
