<?php

// CSRF対策
// Token発行してSessionに格納
// フォームからもTokenを発行、送信
// Check

namespace Wall\Model;

class StickyNote {
  private $_db;

  public function __construct() {
    $this->_createToken();

    try {
      $this->_db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
      $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }

  private function _createToken() {
    if (!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    }
  }

  public function getAll() {
    $sql = sprintf("select * from sticky_notes where wall_id = %d order by id desc", $_SESSION['me']->id);
    $stmt = $this->_db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function post() {
    $this->_validateToken();

    if (!isset($_POST['mode'])) {
      throw new \Exceprion('mode not set!');
    }

    switch ($_POST['mode']) {
      case 'create':
        return $this->_create();
      case 'delete':
        return $this->_delete();
    }
  }

  private function _validateToken() {
    if (
      !isset($_SESSION['token']) ||
      !isset($_POST['token']) ||
      $_SESSION['token'] !== $_POST['token']
    ) {
      throw new \Exception('invalid token!');
    }
  }

  private function _create() {
    if (!isset($_POST['sentence']) || $_POST['sentence'] === '') {
      throw new \Exception('[create] sentence not set"');
    }
    // wall毎のidの最大値を取得
    $sql = sprintf("select max(id) from sticky_notes where wall_id = %d", $_SESSION['me']->id);
    $stmt = $this->_db->query($sql);
    $max_id = $stmt->fetch();

    $sql = sprintf("insert into sticky_notes (wall_id, id, sentence, created, modified) values (:wall_id, %d, :sentence, now(), now())", $max_id[0] + 1);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([
      ':sentence' => $_POST['sentence'],
      ':wall_id' => $_SESSION['me']->id
    ]);

    echo $max_id[0] + 1;
    return [
      'id' => $max_id[0] + 1
    ];
  }

  private function _delete() {
    if (!isset($_POST['id'])) {
      throw new \Exception('[delete] id not set"');
    }
    $sql = sprintf("delete from sticky_notes where wall_id = :wall_id and id = :id");
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([
      ':wall_id' => $_SESSION['me']->id,
      ':id' => $_POST['id']
    ]);

    return [];
  }
}
