<?php

namespace Wall\Model;

class User extends \Wall\Model {

  public function create($values) {
    $stmt = $this->db->prepare("insert into walls (name, display_name, email, password, created, modified) values (:name, :display_name, :email, :password, now(), now())");
    $res = $stmt->execute([
      ':name' => $values['name'],
      ':display_name' => $values['display_name'],
      ':email' => $values['email'],
      ':password' => password_hash($values['password'], PASSWORD_DEFAULT)
    ]);
    if ($res === false) {
      throw new \Wall\Exception\DuplicateName();
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
      throw new \Wall\Exception\UnmatchNameOrPassword();
    }

    if (!password_verify($values['password'], $user->password)) {
      throw new \Wall\Exception\UnmatchNameOrPassword();
    }

    return $user;
  }

}
