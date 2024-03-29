DROP TABLE IF EXISTS #__allplayers_auth;
CREATE TABLE #__allplayers_auth (
  `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT ,
  `key` VARCHAR( 100 ) NOT NULL ,
  `secret` VARCHAR( 100 ) NOT NULL,
  `oauthurl` VARCHAR(200) NOT NULL,
  `verifypeer` BOOLEAN NOT NULL
) ENGINE = InnoDB;

DROP TABLE IF EXISTS #__allplayers_auth_mapping;
CREATE TABLE #__allplayers_auth_mapping (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `allplayersid` VARCHAR( 200 ) NOT NULL UNIQUE,
  `userid` INT NOT NULL UNIQUE
) ENGINE = InnoDB;


DROP TABLE IF EXISTS #__allplayers_profile;
CREATE TABLE #__allplayers_profile (
  `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT ,
  `group` VARCHAR( 50 ) NOT NULL ,
  `values` TEXT NOT NULL
) ENGINE = InnoDB;



