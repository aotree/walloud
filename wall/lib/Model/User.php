<?php

namespace Wall\Model;

class User extends \Wall\Model {

  public function create($values) {
    $stmt = $this->db->prepare("select count(email) from walls where email = :email");
    $stmt->execute([
      ':email' => $values['email'],
    ]);
    $email_count = $stmt->fetch();
    if ((int)$email_count[0] >= 2) {
      if (strcmp(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2), 'ja') == 0) {
        // japanese
        throw new \Wall\Exception\ThirdEmail();
      } else {
        // japanese以外
        throw new \Wall\Exception\ThirdEmailEnglish();
      }
    } else {
      $stmt = $this->db->prepare("insert into walls (name, email, password, created, modified) values (:name, :email, :password, now(), now())");
      $res = $stmt->execute([
        ':name' => $values['name'],
        ':email' => $values['email'],
        ':password' => password_hash($values['password'], PASSWORD_DEFAULT)
      ]);
      $id = $this->db->lastInsertId();
      if ($res === false) {
        if (strcmp(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2), 'ja') == 0) {
          // japanese
          throw new \Wall\Exception\DuplicateName();
        } else {
          // japanese以外
          throw new \Wall\Exception\DuplicateNameEnglish();
        }
      } else {
        $stmt = $this->db->prepare("insert into sections (wall_id, section_id, display_name, created, modified) values (:wall_id, 1, :display_name, now(), now())");
        $res = $stmt->execute([
          ':wall_id' => $id,
          ':display_name' => $values['display_name']
        ]);
      }
    }
  }

  public function login($values) {
    $stmt = $this->db->prepare("select * from walls where name = :name");
    $stmt->execute([
      ':name' => $values['name'],
    ]);
    $stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
    $user = $stmt->fetch();

    if (empty($user)) {
      if (strcmp(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2), 'ja') == 0) {
        // japanese
        throw new \Wall\Exception\UnmatchNameOrPassword();
      } else {
        // japanese以外
        throw new \Wall\Exception\UnmatchNameOrPasswordEnglish();
      }
    }

    if (!password_verify($values['password'], $user->password)) {
      if (strcmp(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2), 'ja') == 0) {
        // japanese
        throw new \Wall\Exception\UnmatchNameOrPassword();
      } else {
        // japanese以外
        throw new \Wall\Exception\UnmatchNameOrPasswordEnglish();
      }
    }

    return $user;
  }

}
