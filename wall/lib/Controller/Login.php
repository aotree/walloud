<?php

namespace Wall\Controller;

class Login extends \Wall\Controller {

  public function run() {
    if ($this->isLoggedIn()) {
      // login
      header('Location: ' . SITE_URL);
      exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->postProcess();
    }
  }

  protected function postProcess() {
    try {
      $this->_validate();
    } catch (\Wall\Exception\EmptyPost $e) {
      $this->setErrors('login', $e->getMessage());
    }

    $this->setValues('name', $_POST['name']);

    if ($this->hasError()) {
      return;
    } else {
      try {
        $userModel = new \Wall\Model\User();
        $user = $userModel->login([
          'name' => $_POST['name'],
          'password' => $_POST['password']
        ]);
      } catch (\Wall\Exception\UnmatchNameOrPassword $e) {
        $this->setErrors('login', $e->getMessage());
        return;
      }

      // login処理
      session_regenerate_id(true); // sessionハイジャック対策
      $_SESSION['me'] = $user;

      // redirect to home
      header('Location: ' . SITE_URL);
      exit;
    }
  }

  private function _validate() {
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
      echo 'Invalid token!';
      exit;
    }

    if (!isset($_POST['name']) || !isset($_POST['password'])) {
      echo 'ウォール名とパスワードを入力してください';
      exit;
    }

    if (!$_POST['name'] === '' || $_POST['password'] === '') {
      throw new \Wall\Exception\EmptyPost();
    }
  }

}
