-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2025 at 12:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apollodb`
--

-- --------------------------------------------------------

--
-- Table structure for table `airplanes`
--

CREATE TABLE `airplanes` (
  `id` int(11) NOT NULL,
  `model` varchar(50) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT NULL,
  `Users_idUsers` int(11) DEFAULT NULL,
  `Flights_idFlights` int(11) DEFAULT NULL,
  `Flights_Airplanes_idAirplanes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

CREATE TABLE `flights` (
  `id` int(11) NOT NULL,
  `flight_number` varchar(10) DEFAULT NULL,
  `destination` varchar(50) DEFAULT NULL,
  `departure_time` datetime DEFAULT NULL,
  `arrival_time` datetime DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `origin` varchar(50) DEFAULT NULL,
  `Airplanes_idAirplanes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `comment` varchar(45) DEFAULT NULL,
  `Bookings_id` int(11) DEFAULT NULL,
  `Bookings_Users_idUsers` int(11) DEFAULT NULL,
  `Bookings_Flights_idFlights` int(11) DEFAULT NULL,
  `Bookings_Flights_Airplanes_idAirplanes` int(11) DEFAULT NULL,
  `Payment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` enum('admin','employee','passenger') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `Roles_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `airplanes`
--
ALTER TABLE `airplanes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Users_idUsers` (`Users_idUsers`),
  ADD KEY `Flights_idFlights` (`Flights_idFlights`),
  ADD KEY `Flights_Airplanes_idAirplanes` (`Flights_Airplanes_idAirplanes`);

--
-- Indexes for table `flights`
--
ALTER TABLE `flights`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `flight_number` (`flight_number`),
  ADD KEY `Airplanes_idAirplanes` (`Airplanes_idAirplanes`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Bookings_id` (`Bookings_id`),
  ADD KEY `Bookings_Users_idUsers` (`Bookings_Users_idUsers`),
  ADD KEY `Bookings_Flights_idFlights` (`Bookings_Flights_idFlights`),
  ADD KEY `Bookings_Flights_Airplanes_idAirplanes` (`Bookings_Flights_Airplanes_idAirplanes`),
  ADD KEY `Payment_id` (`Payment_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `Roles_id` (`Roles_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `airplanes`
--
ALTER TABLE `airplanes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flights`
--
ALTER TABLE `flights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`Users_idUsers`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`Flights_idFlights`) REFERENCES `flights` (`id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`Flights_Airplanes_idAirplanes`) REFERENCES `airplanes` (`id`);

--
-- Constraints for table `flights`
--
ALTER TABLE `flights`
  ADD CONSTRAINT `flights_ibfk_1` FOREIGN KEY (`Airplanes_idAirplanes`) REFERENCES `airplanes` (`id`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`Bookings_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`Bookings_Users_idUsers`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `invoice_ibfk_3` FOREIGN KEY (`Bookings_Flights_idFlights`) REFERENCES `flights` (`id`),
  ADD CONSTRAINT `invoice_ibfk_4` FOREIGN KEY (`Bookings_Flights_Airplanes_idAirplanes`) REFERENCES `airplanes` (`id`),
  ADD CONSTRAINT `invoice_ibfk_5` FOREIGN KEY (`Payment_id`) REFERENCES `payment` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`Roles_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
