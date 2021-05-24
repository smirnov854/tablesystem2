DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users`(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `email` VARCHAR(50),
  `name` VARCHAR(100),
  `password` VARCHAR(200),
  `role_id` INT
);
ALTER TABLE `u0507831_test`.`users` ADD INDEX (`role_id`);

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role`(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100)
);

INSERT INTO `role` (`id`, `name`) VALUES (NULL, 'Администратор');
INSERT INTO `role` (`id`, `name`) VALUES (NULL, 'Клиент');
INSERT INTO `role` (`id`, `name`) VALUES (NULL, 'Работник');

DROP TABLE IF EXISTS `role_rights`;
CREATE TABLE IF NOT EXISTS `role_rights`(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100)
);

DROP TABLE IF EXISTS `objects`;
CREATE TABLE IF NOT EXISTS `objects`(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100),
  `address` VARCHAR(250)
);

DROP TABLE IF EXISTS `user_object`;
CREATE TABLE IF NOT EXISTS `user_object`(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `object_id` INT
);

ALTER TABLE `u0507831_test`.`user_object` ADD INDEX (`user_id`);
ALTER TABLE `u0507831_test`.`user_object` ADD INDEX (`object_id`);

DROP TABLE IF EXISTS `type_of_work`;
CREATE TABLE IF NOT EXISTS `type_of_work`(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(250)  
);

INSERT INTO `type_of_work` (`id`, `name`) VALUES (NULL, 'Техническое обслуживание1');
INSERT INTO `type_of_work` (`id`, `name`) VALUES (NULL, 'Техническое обслуживание2');
INSERT INTO `type_of_work` (`id`, `name`) VALUES (NULL, 'Техническое обслуживание3');

DROP TABLE IF EXISTS `requests`;
CREATE TABLE IF NOT EXISTS `requests`(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `type_id` INT,
  `id_user_add` INT,
  `description` TEXT,
  `date_add` INT,
  `object_id` INT,
  `type_of_work` INT,
  `done_work` VARCHAR(250),
  `id_user_done` INT,
  `user_done_date` INT,
  `id_user_check` INT,
  `user_check_date` INT,
  `id_user_common_check` INT,
  `id_user_common_date` INT
);

ALTER TABLE `u0507831_test`.`requests` ADD INDEX (`type_id`);
ALTER TABLE `u0507831_test`.`requests` ADD INDEX (`id_user_add`);
ALTER TABLE `u0507831_test`.`requests` ADD INDEX (`object_id`);
ALTER TABLE `u0507831_test`.`requests` ADD INDEX (`id_user_done`);
ALTER TABLE `u0507831_test`.`requests` ADD INDEX (`id_user_common_check`);

