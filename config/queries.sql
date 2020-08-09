DROP DATABASE IF EXISTS `db_camagru`;

CREATE DATABASE IF NOT EXISTS `db_camagru`;

USE `db_camagru`;

CREATE TABLE IF NOT EXISTS `users` (
	`id`				INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`firstname`			VARCHAR(255) NOT NULL,
	`lastname`			VARCHAR(255) NOT NULL,
	`email`				VARCHAR(255) NOT NULL,
	`username`			VARCHAR(10) NOT NULL,
	`password`			VARCHAR(255) NOT NULL,
	`gender`			VARCHAR(255) NOT NULL,
	`address`			VARCHAR(255) NULL,
	`activationToken`	VARCHAR(255) NOT NULL,
	`recoveryToken`		VARCHAR(255) DEFAULT NULL,
	`notifEmail`		BOOLEAN DEFAULT 1,
	`createdat`			DATETIME DEFAULT CURRENT_TIMESTAMP,
	`modifyat`			DATETIME DEFAULT NULL,
	CONSTRAINT uk_email_username UNIQUE ( email, username )
);

CREATE TABLE IF NOT EXISTS `gallery` (
	`id`				INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`userid`			INT NOT NULL,
	`title`				VARCHAR(255) NOT NULL,
	`src`				VARCHAR(255) NOT NULL,
	`createdat`			DATETIME DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT fk_userid FOREIGN KEY ( userid ) REFERENCES users( id )
)