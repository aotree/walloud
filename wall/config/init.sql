create database wall;

grant all on wall.* to dbuser@localhost identified by 'P@ssw0rd';

use wall

CREATE TABLE walls (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(128) DEFAULT NULL,
  display_name varchar(128) DEFAULT NULL,
  password varchar(128) DEFAULT NULL,
  email varchar(128) DEFAULT NULL,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY name (name)
);

CREATE TABLE sticky_notes (
  wall_id int(10) unsigned NOT NULL,
  id int(10) unsigned NOT NULL DEFAULT '0',
  sentence varchar(128) DEFAULT NULL,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (wall_id,id),
  CONSTRAINT fk_sticky_notes FOREIGN KEY (wall_id) REFERENCES walls (id)
);

desc wall;
