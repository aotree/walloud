<?php

namespace Wall\Model;

class StickyNote {
  private $_db;

  public function __construct() {
    // CSRF対策
    // (1) StickyNote.phpにてTokenを発行してSessionに格納(Sessionに対して最初の1回のみ格納)
    // (2) index.phpにて(1)で作成したTokenをフォームにhidden値として格納して送信
    // (3) (1)と(2)が一致しているかCheckする
    $this->_createToken(); // CSRF対策-(1)

    try {
      $this->_db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD); // config.phpで設定した定数
      $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // エラーレポート: 例外を投げる
    } catch (\PDOException $e) { // 接続に失敗した場合
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
    $sql = sprintf("select * from sticky_notes where wall_id = %d and section_id = %d order by id", $_SESSION['me']->id, $_SESSION['section']);
    $stmt = $this->_db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  // 20180309 add
  public function getSections() {
    $sql = sprintf("select * from sections where wall_id = %d", $_SESSION['me']->id);
    $stmt = $this->_db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  // 20180309 add
  public function getSectionDisplayName() {
    if (!isset($_SESSION['section'])) {
      $sql = sprintf("select section_id from sections where wall_id = %d order by section_id", $_SESSION['me']->id);
      $stmt = $this->_db->query($sql);
      $section = $stmt->fetch();
      $_SESSION['section'] = $section['section_id'];
    }
    $sql = sprintf("select display_name from sections where wall_id = %d and section_id = %d", $_SESSION['me']->id, $_SESSION['section']);
    $stmt = $this->_db->query($sql);
    $result = $stmt->fetch();
    if ($result !== false) {
      return $result;
    } else {
      $sql = sprintf("select display_name, section_id from sections where wall_id = %d order by section_id limit 1", $_SESSION['me']->id);
      $stmt = $this->_db->query($sql);
      $result = $stmt->fetch();
      $_SESSION['section'] = $result[1];
      return $result;
    }
  }

  public function post() {
    $this->_validateToken();

    if (!isset($_POST['mode'])) {
      throw new \Exceprion('mode not set!');
    }

    switch ($_POST['mode']) {
      case 'create':
        return $this->_create();
        // 20180327 add
      case 'update':
        return $this->_update();
      case 'delete':
        return $this->_delete();
        // 20180309 add
      case 'section':
        return $this->_set_section();
        // 20180309 add
      case 'delete_section':
        return $this->_delete_section();
        // 20180309 add
      case 'create_section':
        return $this->_create_section();
        // 20180327 add
      case 'update_section':
        return $this->_update_section();
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
    $sql = sprintf("select max(id) from sticky_notes where wall_id = %d and section_id = %d", $_SESSION['me']->id, $_SESSION['section']);
    $stmt = $this->_db->query($sql);
    $max_id = $stmt->fetch();

    $sql = sprintf("insert into sticky_notes (wall_id, section_id, id, sentence, created, modified) values (:wall_id, :section_id, %d, :sentence, now(), now())", $max_id[0] + 1);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([
      ':sentence' => $_POST['sentence'],
      ':wall_id' => $_SESSION['me']->id,
      ':section_id' => $_SESSION['section']
    ]);

    echo $max_id[0] + 1;
    return [
      'id' => $max_id[0] + 1
    ];
  }

  // 20180327 add
  private function _update() {
    if (!isset($_POST['id'])) {
      throw new \Exception('[update] id not set"');
    }
    $sql = sprintf("update sticky_notes set sentence = :sentence, modified = now() where wall_id = :wall_id and section_id = :section_id and id = :id");
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([
      ':sentence' => $_POST['sentence'],
      ':wall_id' => $_SESSION['me']->id,
      ':section_id' => $_SESSION['section'],
      ':id' => $_POST['id']
    ]);

    return [];
  }

  private function _delete() {
    if (!isset($_POST['id'])) {
      throw new \Exception('[delete] id not set"');
    }
    $sql = sprintf("delete from sticky_notes where wall_id = :wall_id and section_id = :section_id and id = :id");
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([
      ':wall_id' => $_SESSION['me']->id,
      ':section_id' => $_SESSION['section'],
      ':id' => $_POST['id']
    ]);

    return [];
  }

  // 20180309 add
  private function _set_section() {
    $_SESSION['section'] = $_POST['section_id'];
    return [];
  }

  // 20180309 add
  private function _delete_section() {
    if (!isset($_POST['section_id'])) {
      throw new \Exception('[delete] section_id not set"');
    }
    $sql = sprintf("delete from sticky_notes where wall_id = :wall_id and section_id = :section_id");
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([
      ':wall_id' => $_SESSION['me']->id,
      ':section_id' => $_POST['section_id']
    ]);

    $sql = sprintf("delete from sections where wall_id = :wall_id and section_id = :section_id");
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([
      ':wall_id' => $_SESSION['me']->id,
      ':section_id' => $_POST['section_id']
    ]);

    return [];
  }

  private function _create_section() {
    if (!isset($_POST['display_name']) || $_POST['display_name'] === '') {
      throw new \Exception('[create] display_name not set"');
    }
    // wall毎のidの最大値を取得
    $sql = sprintf("select max(section_id) from sections where wall_id = %d", $_SESSION['me']->id);
    $stmt = $this->_db->query($sql);
    $max_id = $stmt->fetch();

    $sql = sprintf("insert into sections (wall_id, section_id, display_name, created, modified) values (:wall_id, %d, :display_name, now(), now())", $max_id[0] + 1);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([
      ':display_name' => $_POST['display_name'],
      ':wall_id' => $_SESSION['me']->id
    ]);

    echo $max_id[0] + 1;
    return [
      'id' => $max_id[0] + 1
    ];
  }

  // 20180327 add
  private function _update_section() {
    if (!isset($_POST['display_name']) || $_POST['display_name'] === '') {
      throw new \Exception('[update] display_name not set"');
    }
    $sql = sprintf("update sections set display_name = :display_name, modified = now() where wall_id = :wall_id and section_id = :section_id");
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([
      ':display_name' => $_POST['display_name'],
      ':wall_id' => $_SESSION['me']->id,
      ':section_id' => $_POST['section_id']
    ]);

    return [];
  }
}
