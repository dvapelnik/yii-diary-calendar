CREATE TABLE IF NOT EXISTS `{%prefix%}event` (
  `id`        INT(11)              NOT NULL AUTO_INCREMENT,
  `owner`     INT(11)              NOT NULL,
  `timestamp` INT(11)              NOT NULL,
  `text`      TEXT                 NOT NULL,
  `type`      ENUM('note', 'appo') NOT NULL DEFAULT 'note',
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM;