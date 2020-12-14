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
	`activationToken`	VARCHAR(255) NULL,
	`recoveryToken`		VARCHAR(255) DEFAULT NULL,
	`notifEmail`		BOOLEAN DEFAULT 1,
	`createdat`			DATETIME DEFAULT CURRENT_TIMESTAMP,
	`modifyat`			DATETIME DEFAULT NULL,
	CONSTRAINT uk_email_username UNIQUE ( email, username )
);

CREATE TABLE IF NOT EXISTS `gallery` (
	`id`				INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`description`		VARCHAR(255) NOT NULL,
	`userid`			INT NOT NULL,
	`src`				VARCHAR(255) NOT NULL,
	`createdat`			DATETIME DEFAULT CURRENT_TIMESTAMP,
	`countlikes`		INT DEFAULT 0,
	`countcomments`		INT DEFAULT 0,
	CONSTRAINT fk_userid_img FOREIGN KEY ( userid ) REFERENCES users( id ) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `likes` (
	`id`				INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`userid`			INT NOT NULL,
	`imgid`				INT NOT NULL,
	`createdat`			DATETIME DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT fk_userid_like FOREIGN KEY ( userid ) REFERENCES users( id ) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_imgid_like FOREIGN KEY ( imgid ) REFERENCES gallery( id ) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `comments` (
	`id`				INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`content`			VARCHAR(255) NOT NULL,
	`userid`			INT NOT NULL,
	`imgid`				INT NOT NULL,
	`createdat`			DATETIME DEFAULT CURRENT_TIMESTAMP,
	`modifyat`			DATETIME DEFAULT NULL,
	CONSTRAINT fk_userid_comment FOREIGN KEY ( userid ) REFERENCES users( id ) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_imgid_comment FOREIGN KEY ( imgid ) REFERENCES gallery( id ) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `notifications` (
	`id`				INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`content`			VARCHAR (255) NOT NULL,
	`userid`			INT NOT NULL,
	`likeid`			INT,
	`commentid`			INT,
	`createdat`			DATETIME DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT fk_userid_notif FOREIGN KEY ( userid ) REFERENCES users( id ) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_likeid_notif FOREIGN KEY ( likeid ) REFERENCES likes( id ) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_commentid_notif FOREIGN KEY ( commentid ) REFERENCES comments( id ) ON DELETE CASCADE ON UPDATE CASCADE
);

-- TIGGERS

DELIMITER //

-- Trigger for increment count likes of an image and create a notification for author
CREATE TRIGGER tr_insert_like
AFTER INSERT ON likes
FOR EACH ROW
BEGIN
	DECLARE author_id INT DEFAULT 0;
    
	-- Increment cout likes of the image liked
	UPDATE gallery SET countlikes = countlikes + 1
	WHERE id = NEW.imgid;
	-- Create notification for author image
    SELECT userid
	INTO @author_id
	FROM gallery
	WHERE id = NEW.imgid;
    IF ( NEW.userid <> @author_id ) THEN
    	INSERT INTO notifications (content, userid, likeid)
    	VALUES ("New like on your picture !", @author_id, NEW.id);
	END IF;
END//

-- Trigger for decrement count likes of an image and remove notification of the like
CREATE IF NOT EXISTS TRIGGER tr_delete_like
AFTER DELETE on likes
FOR EACH ROW
BEGIN
	-- Decrement count likes of the image
	UPDATE gallery SET countlikes = countlikes - 1 WHERE id = OLD.imgid;
	-- Remove like notification 
	DELETE FROM notifications WHERE likeid = OLD.id;
END//