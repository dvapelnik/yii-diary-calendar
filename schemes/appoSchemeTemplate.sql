CREATE TABLE IF NOT EXISTS `{%prefix%}appo` (
  `id`        INT               NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `owner`     INT               NOT NULL,
  `timestamp` INT               NOT NULL,
  `text`      TEXT              NOT NULL,
  `email`     VARCHAR(255)      NOT NULL,
  `send`      ENUM('yes', 'no') NOT NULL
)
  ENGINE = MYISAM