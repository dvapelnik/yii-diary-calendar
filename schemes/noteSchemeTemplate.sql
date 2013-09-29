CREATE TABLE IF NOT EXISTS `{%prefix%}note` (
  `id`        INT  NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `owner`     INT  NOT NULL,
  `timestamp` INT  NOT NULL,
  `text`      TEXT NOT NULL
)
  ENGINE = MYISAM