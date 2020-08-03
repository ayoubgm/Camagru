DROP DATABASE IF EXISTS `db_camagru`;

CREATE DATABASE IF NOT EXISTS `db_camagru`;

USE `db_camagru`;

CREATE TABLE IF NOT EXISTS `users` (
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`firstname` VARCHAR(255) NOT NULL,
	`lastname` VARCHAR(255) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`username` VARCHAR(10) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`address` VARCHAR(255) NOT NULL,
	`activationToken` VARCHAR(255) DEFAULT NULL,
	`recoveryToken` VARCHAR(255) DEFAULT NULL,
	`createdat` DATETIME DEFAULT CURRENT_TIMESTAMP,
	`modifyat` DATETIME DEFAULT NULL,
	CONSTRAINT uk_email_username UNIQUE ( email, username )
);

INSERT INTO `users`(`firstname`, `lastname`, `email`, `username`, `password`, `address`)
values ( "ayoub", "guismi", "i.guismi@gmail.com", "aguismi", "safaa123ayoub", "385 hay el phsphate ...");