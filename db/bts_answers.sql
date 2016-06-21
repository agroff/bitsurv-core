CREATE TABLE IF NOT EXISTS `bts_answers` (
  `id` BIGINT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` VARCHAR(55) NOT NULL,
  `response_id` INT(11) NOT NULL,
  `answer` VARCHAR(255) NULL,
  `answer_blob` BLOB NULL,
  `updated` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `question_id` (`question_id` ASC),
  INDEX `response_id` (`response_id` ASC));