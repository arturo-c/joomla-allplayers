DROP TABLE IF EXISTS #__twitter_consumer;
CREATE TABLE #__twitter_consumer (
  `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT ,
  `key` VARCHAR( 100 ) NOT NULL ,
  `secret` VARCHAR( 100 ) NOT NULL
) ENGINE = InnoDB;

DROP TABLE IF EXISTS #__twitter_mapping;
CREATE TABLE #__twitter_mapping (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `twitterid` INT( 255 ) NOT NULL UNIQUE,
  `userid` INT NOT NULL UNIQUE
) ENGINE = InnoDB;
