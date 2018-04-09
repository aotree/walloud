<?php

namespace Wall\Controller;

class Signup extends \Wall\Controller {

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
    // validate
    try {
      $this->_validate();
    } catch (\Wall\Exception\InvalidName $e) {
      // echo $e->getMessage();
      // exit;
      $this->setErrors('name', $e->getMessage());
    } catch (\Wall\Exception\InvalidNameEnglish $e) {
      // echo $e->getMessage();
      // exit;
      $this->setErrors('name', $e->getMessage());
    } catch (\Wall\Exception\InvalidDisplayName $e) {
      // echo $e->getMessage();
      // exit;
      $this->setErrors('display_name', $e->getMessage());
    } catch (\Wall\Exception\InvalidDisplayNameEnglish $e) {
      // echo $e->getMessage();
      // exit;
      $this->setErrors('display_name', $e->getMessage());
    } catch (\Wall\Exception\InvalidEmail $e) {
      // echo $e->getMessage();
      // exit;
      $this->setErrors('email', $e->getMessage());
    } catch (\Wall\Exception\InvalidEmailEnglish $e) {
      // echo $e->getMessage();
      // exit;
      $this->setErrors('email', $e->getMessage());
    } catch (\Wall\Exception\InvalidPassword $e) {
      // echo $e->getMessage();
      // exit;
      $this->setErrors('password', $e->getMessage());
    } catch (\Wall\Exception\InvalidPasswordEnglish $e) {
      // echo $e->getMessage();
      // exit;
      $this->setErrors('password', $e->getMessage());
    }

    $this->setValues('name', $_POST['name']);
    $this->setValues('display_name', $_POST['display_name']);
    $this->setValues('email', $_POST['email']);

    if ($this->hasError()) {
      return;
    } else {
      // create wall
      try {
        $userModel = new \Wall\Model\User();
        $userModel->create([
          'name' => $_POST['name'],  // Unique
          'display_name' => $_POST['display_name'],
          'email' => $_POST['email'],
          'password' => $_POST['password']
        ]);
      } catch (\Wall\Exception\ThirdEmail $e) {
        $this->setErrors('email', $e->getMessage());
        return;
      } catch (\Wall\Exception\ThirdEmailEnglish $e) {
        $this->setErrors('email', $e->getMessage());
        return;
      } catch (\Wall\Exception\DuplicateName $e) {
        $this->setErrors('name', $e->getMessage());
        return;
      } catch (\Wall\Exception\DuplicateNameEnglish $e) {
        $this->setErrors('name', $e->getMessage());
        return;
      }

      // redirect to Login
      header('Location: ' . SITE_URL . '/login.php');
      exit;
    }
  }

  private function _validate() {
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
      echo 'Invalid token!';
      return;
    }

    if (!preg_match('/\A[a-zA-Z0-9-_@\.]+\z/', $_POST['name'])) {
      if (strcmp(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2), 'ja') == 0) {
        // japanese
        throw new \Wall\Exception\InvalidName();
      } else {
        // japanese以外
        throw new \Wall\Exception\InvalidNameEnglish();
      }
    }

    if ((strlen(mb_convert_encoding($_POST['display_name'], 'SJIS', 'UTF-8')) > 20) || strlen(mb_convert_encoding($_POST['display_name'], 'SJIS', 'UTF-8')) === 0) {  // 全角...2, 半角...1
      if (strcmp(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2), 'ja') == 0) {
        // japanese
        throw new \Wall\Exception\InvalidDisplayName();
      } else {
        // japanese以外
        throw new \Wall\Exception\InvalidDisplayNameEnglish();
      }
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      if (strcmp(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2), 'ja') == 0) {
        // japanese
        throw new \Wall\Exception\InvalidEmail();
      } else {
        // japanese以外
        throw new \Wall\Exception\InvalidEmailEnglish();
      }
    }

    if (!preg_match('/\A[a-zA-Z0-9]{8,100}\z/', $_POST['password'])) {
      if (strcmp(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2), 'ja') == 0) {
        // japanese
        throw new \Wall\Exception\InvalidPassword();
      } else {
        // japanese以外
        throw new \Wall\Exception\InvalidPasswordEnglish();
      }
    }
  }

}
