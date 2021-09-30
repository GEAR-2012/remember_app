CREATE DATABASE remember;
-- 
CREATE TABLE remember.users (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(40) NOT NULL,
  email VARCHAR(40) NOT NULL,
  pwd VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)) ENGINE = InnoDB;
-- 
CREATE TABLE remember.tasklists (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(40) NOT NULL,
  user_id INT NOT NULL,
  PRIMARY KEY (id),
  INDEX user_ind (user_id),
  FOREIGN KEY (user_id)
  REFERENCES users(id)
  ON DELETE RESTRICT
  ON UPDATE CASCADE) ENGINE = InnoDB;
-- 
CREATE TABLE remember.tasks (
  id INT NOT NULL AUTO_INCREMENT,
  task_name VARCHAR(40) NOT NULL,
  task_status BOOLEAN NOT NULL DEFAULT false,
  created_at BIGINT(20) UNSIGNED NOT NULL,
  list_id INT NOT NULL,
  PRIMARY KEY (id),
  INDEX list_ind (list_id),
  FOREIGN KEY (list_id)
  REFERENCES tasklists(id)
  ON DELETE RESTRICT
  ON UPDATE CASCADE) ENGINE = InnoDB;
-- 