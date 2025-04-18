-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 18, 2025 at 11:13 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testing`
--

-- --------------------------------------------------------

--
-- Table structure for table `registered_users`
--

DROP TABLE IF EXISTS `registered_users`;
CREATE TABLE IF NOT EXISTS `registered_users` (
  `full_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `resettoken` varchar(255) DEFAULT NULL,
  `resettokenexpire` date DEFAULT NULL,
  `verification_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `is_verified` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `registered_users`
--

INSERT INTO `registered_users` (`full_name`, `username`, `email`, `role`, `password`, `resettoken`, `resettokenexpire`, `verification_code`, `is_verified`) VALUES
('hi', 'hello', 'example@gmail.com', '', '1234', NULL, NULL, '', '0'),
('web', 'website', 'website@gmail.com', '', '12345', NULL, NULL, '', '0'),
('Test User', 'testuser', 'testuser@example.com', '', '$2y$10$CQVzu8FsUFxXXiBzpKYFxuM/MlvG.E1Q9RgVsTDD67SUuvPksmSOS', NULL, NULL, '', '0'),
('abc', 'abc', 'abc@gmail.com', '', '$2y$10$2vFzDKRGmPT7JxZhqD.6Z.rFwkcY5X9xpxH6bcqtkicer/agehds.', NULL, NULL, '12ee819ecbd99a00310a4971ac5629aa', '0'),
('Ina', '', 'ina526062@gmail.com', '', '$2y$10$gKq36.rj2R0xsg.zq/CBxeYnx1SdgNajoc.bt/UAPgOaHjSNpX7my', NULL, NULL, 'ab7832e3f9fd9b4cbc01636cce17d148', '0'),
('ina', 'ina ', 'ina52@gmail.com', '', '$2y$10$9fPR5jzX/yEEzUx.ek/9DebjWWefv4ffGkEj99xjLka8ggII4hzh2', NULL, NULL, '2e7dbd3809920312f130189fa08973bc', '0'),
('Yeontan', 'Bangtan', 'bangtanyeon777@gmail.com', 'user', '$2y$10$4B6YOUVAqM/qoVZdxB.qzOTNLxYk21BjVhH8SNS9Kn9NPyj07iSBO', NULL, NULL, '13a1f4103880e5ca67c14e02d09396a3', '1'),
('Admin User', 'admin', 'admin@example.com', 'admin', '<hashed_password>', NULL, NULL, '', '1');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
