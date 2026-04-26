-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2026 at 05:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- Drop existing tables in correct order (foreign key deps)
DROP TABLE IF EXISTS `activity_log`;
DROP TABLE IF EXISTS `cards`;
DROP TABLE IF EXISTS `decks`;
DROP TABLE IF EXISTS `users`;

-- --------------------------------------------------------
-- Users table
-- --------------------------------------------------------
CREATE TABLE `users` (
  `user_id`    INT(11)      NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(30)  NOT NULL,
  `email`      VARCHAR(100) NOT NULL,
  `password`   VARCHAR(255) NOT NULL,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Decks table
-- --------------------------------------------------------
CREATE TABLE `decks` (
  `deck_id`    INT(11)      NOT NULL AUTO_INCREMENT,
  `user_id`    INT(11)      NOT NULL,
  `deck_name`  VARCHAR(50)  NOT NULL,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`deck_id`),
  KEY `fk_deck_user` (`user_id`),
  CONSTRAINT `fk_deck_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Cards table
-- --------------------------------------------------------
CREATE TABLE `cards` (
  `card_id`    INT(11)       NOT NULL AUTO_INCREMENT,
  `deck_id`    INT(11)       NOT NULL,
  `question`   VARCHAR(2000) NOT NULL,
  `answer`     VARCHAR(2000) NOT NULL,
  `created_at` DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`card_id`),
  KEY `fk_card_deck` (`deck_id`),
  CONSTRAINT `fk_card_deck` FOREIGN KEY (`deck_id`) REFERENCES `decks` (`deck_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Activity log for streak tracking
-- --------------------------------------------------------
CREATE TABLE `activity_log` (
  `log_id`     INT(11)  NOT NULL AUTO_INCREMENT,
  `user_id`    INT(11)  NOT NULL,
  `activity`   VARCHAR(50) NOT NULL DEFAULT 'session',
  `log_date`   DATE     NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  UNIQUE KEY `unique_user_date` (`user_id`, `log_date`),
  KEY `fk_log_user` (`user_id`),
  CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;