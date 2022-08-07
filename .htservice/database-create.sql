--CREATE DATABASE `test_work_db` COLLATE 'utf8mb4_general_ci';
USE `test_work_db`;

CREATE TABLE `socks` (
	`color` VARCHAR(16) NOT NULL COMMENT 'Цвет носков',
	`cottonPart` INT(11) NOT NULL COMMENT 'Процент содержания хлопка в составе носков',
	`quantity` INT(11) NOT NULL DEFAULT '0' COMMENT 'Количество пар носков'
)
COMMENT='Учёт носков'
ENGINE=InnoDB
;
