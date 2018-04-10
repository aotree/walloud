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
    // 20180410 add
      case 'sort':
        return $this->_sort();
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

  private function _sort() {
    if($_POST['sort_values']){
      //$_POST['result']内にはliのIDが上から順番コンマ区切りの文字列で格納されています。
      //1,2,3,4 だったものを 4,2,1,3　と並び替えれば　$_POST['result']="4,2,1,3"です。

      $sort_values = $_POST['sort_values'];

      //順に処理するために配列に格納
      // foreach ($sort_values as $sort_value) {
      //   var_dump($sort_value[0]);
      // }
      // $result_array = explode(',', $result);
      // var_dump($result_array);
      // $nom = 1; //idに対して番号を１からふる
      // foreach($result_array as $no){
      //   //この中で適宜DBに格納する処理
      //   //"UPDATE テーブル名 SET no='$nom' WHERE id='$no'";
      //   $nom++;//$nomを１つずつ増やしていく
      // }
      $sql = sprintf("select * from sticky_notes where wall_id = %d and section_id = %d order by id", $_SESSION['me']->id, $_SESSION['section']);
      $stmt = $this->_db->query($sql);
      $fetch_all = $stmt->fetchAll(\PDO::FETCH_OBJ);

      $fetch_created = array();
      $fetch_modified = array();
      $fetch_id = array();
      $sort_i = 0;
      foreach ($fetch_all as $fetch_value) {
        foreach ($sort_values as $sort_value) {
          if ($sort_value == $fetch_value->sentence) {
            echo $sort_value;
            echo $fetch_value->sentence;
            echo "----------";
            $fetch_created[$fetch_value->sentence] = $fetch_value->created;
            $fetch_modified[$fetch_value->sentence] = $fetch_value->modified;
            // $fetch_id[$sort_i] = $fetch_value->id;
          }
        }
        $sort_i++;
      }
      // var_dump($fetch_id);
      // var_dump($fetch_modified);
      $sort_i = 0;
      // var_dump($fetch_created[$fetch_id[$sort_i]]);
      foreach ($fetch_all as $fetch_value) {
        // $sort_i_before = $fetch_id[$sort_i];
        // echo $sort_i_before;
        // echo $fetch_created[$fetch_value->sentence];
      //   // echo $fetch_value->id;
      //   // echo $sort_values[$sort_i];

        $sql = sprintf("update sticky_notes set sentence = :sentence, created = :created, modified = :modified where wall_id = :wall_id and section_id = :section_id and id = :id");
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([
          ':sentence' => $sort_values[$sort_i],
          ':wall_id' => $_SESSION['me']->id,
          ':section_id' => $_SESSION['section'],
          ':created' => $fetch_created[$sort_values[$sort_i]],
          ':modified' => $fetch_modified[$sort_values[$sort_i]],
          ':id' => $fetch_value->id
        ]);
        $sort_i++;
      }

      return [];
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
