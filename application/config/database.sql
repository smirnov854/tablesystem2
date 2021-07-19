DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users`(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `email` VARCHAR(50),
  `name` VARCHAR(100),
  `password` VARCHAR(200),
  `role_id` INT,
  `is_delete` INT
);
ALTER TABLE `users` ADD INDEX (`role_id`);
INSERT INTO `users` (`id`, `email`, `name`, `password`, `role_id`, `is_delete`) VALUES (1, 'admin@admin.com', NULL, '$2y$10$rj6PDNYmQ/r3UTadlMoGSuyNGFyMRe1/.EwROuEp/Af/cctCvIclW', '1', NULL);

DROP TABLE IF EXISTS `user_object`;
CREATE TABLE IF NOT EXISTS `user_object`(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `object_id` INT
);

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role`(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100)
);

INSERT INTO `role` (`id`, `name`) VALUES (NULL, 'Администратор');
INSERT INTO `role` (`id`, `name`) VALUES (NULL, 'Клиент');
INSERT INTO `role` (`id`, `name`) VALUES (NULL, 'Инженер');
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
  `address` VARCHAR(250),
  `description` VARCHAR(500),
  `is_delete` INT
);



ALTER TABLE `user_object` ADD INDEX (`user_id`);
ALTER TABLE `user_object` ADD INDEX (`object_id`);

DROP TABLE IF EXISTS `type_of_work`;
CREATE TABLE IF NOT EXISTS `type_of_work`(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(250)  
);
ALTER TABLE `type_of_work` ADD `is_delete` INT NOT NULL AFTER `name`;
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
  `common_date` INT
);

ALTER TABLE `requests` ADD INDEX (`type_id`);
ALTER TABLE `requests` ADD INDEX (`id_user_add`);
ALTER TABLE `requests` ADD INDEX (`object_id`);
ALTER TABLE `requests` ADD INDEX (`id_user_done`);
ALTER TABLE `requests` ADD INDEX (`id_user_common_check`);

DROP TABLE IF EXISTS `request_files`;
CREATE TABLE `request_files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` INT,
  `file_path` varchar(250),    
   PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

