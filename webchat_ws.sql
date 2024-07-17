-- Adminer 4.8.1 MySQL 5.6.45 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

CREATE DATABASE `webchat_ws` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `webchat_ws`;

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `account_id` char(36) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `username` (`username`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB;


DROP TABLE IF EXISTS `chats`;
CREATE TABLE `chats` (
  `chat_id` char(36) NOT NULL,
  `text_sent` text,
  `sender_user_id` char(36) NOT NULL,
  `receiver_user_id` char(36) NOT NULL,
  `sent_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`chat_id`),
  FOREIGN KEY (`receiver_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;


DROP TABLE IF EXISTS `friends`;
CREATE TABLE `friends` (
  `friend_id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `to_user_id` char(36) NOT NULL,
  `status` enum('PENDING','FRIENDS') NOT NULL,
  PRIMARY KEY (`friend_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`to_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` char(36) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `picture` text,
  `last_active` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB;


-- 2024-07-17 17:01:16