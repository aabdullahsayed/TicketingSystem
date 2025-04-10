-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 07:47 PM
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
-- Database: `ticketing_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'abdullah@gmail.com', '6969');

-- --------------------------------------------------------

--
-- Table structure for table `bus`
--

CREATE TABLE `bus` (
  `bus_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `total_seats` int(11) NOT NULL,
  `available_seats` int(11) NOT NULL,
  `status` enum('active','cancelled','maintenance') DEFAULT 'active',
  `bus_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`bus_id`, `route_id`, `total_seats`, `available_seats`, `status`, `bus_name`) VALUES
(1, 1, 50, 45, 'active', 'Voyager'),
(2, 1, 50, 31, 'active', 'Metro'),
(3, 6, 40, 39, 'active', 'Rider'),
(5, 9, 50, 50, 'active', 'Monzil');

-- --------------------------------------------------------

--
-- Table structure for table `new_users`
--

CREATE TABLE `new_users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `new_users`
--

INSERT INTO `new_users` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`, `city`, `country`) VALUES
(1, 'Abdullah', 'Al Sayed', 'sayedtheidiot@gmail.com', '112233', '2024-12-30 02:08:00', 'Dhaka', 'Bangladesh'),
(2, 'Aqeeb', 'Sarwar', 'aqeeb@email.com', '0000', '2024-12-30 06:15:53', 'Dhaka', 'Bangladesh');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `bus_id` int(11) DEFAULT NULL,
  `seat_number` int(11) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `route_id` int(11) NOT NULL,
  `source_location` varchar(100) NOT NULL,
  `destination_location` varchar(100) NOT NULL,
  `distance` decimal(10,2) NOT NULL,
  `estimated_duration` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`route_id`, `source_location`, `destination_location`, `distance`, `estimated_duration`) VALUES
(1, 'Dhaka', 'Chittagong', 250.00, '05:30:00'),
(2, 'Dhaka', 'Sylhet', 200.00, '05:00:00'),
(4, 'Chittagong', 'Dhaka', 250.00, '05:30:00'),
(6, 'Sylhet', 'Dhaka', 200.00, '05:00:00'),
(8, 'Cox&#039;s Bazar', 'Dhaka', 400.00, '07:00:00'),
(9, 'Barishal', 'Khulna', 100.00, '03:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `Schedule_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `going_from` varchar(100) NOT NULL,
  `going_to` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `duration` time NOT NULL,
  `ticket_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`Schedule_id`, `route_id`, `bus_id`, `going_from`, `going_to`, `date`, `departure_time`, `arrival_time`, `duration`, `ticket_price`) VALUES
(1, 1, 1, 'Abdullahpur , Dhaka', 'GEC,Chittagong', '2024-12-07', '07:00:00', '06:45:00', '05:30:00', 680.00),
(8, 2, 2, 'Abdullahpur , Dhaka', 'Sylhet', '2024-12-28', '15:00:00', '14:50:00', '05:00:00', NULL),
(9, 1, 2, 'Dhaka', 'Chittagong', '2024-12-29', '18:00:00', '17:45:00', '05:00:00', 500.00),
(11, 2, 3, 'Dhaka', 'Sylhet', '2024-12-31', '15:00:00', '14:50:00', '05:00:00', 500.00),
(12, 6, 3, 'Dhaka', 'Sylhet', '2024-12-31', '15:00:00', '14:50:00', '05:00:00', 500.00),
(13, 9, 5, 'Barishal ', 'Khulna', '2024-12-31', '14:30:00', '14:15:00', '05:30:00', 400.00);

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `seat_number` int(11) DEFAULT NULL,
  `ticket_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `route_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`ticket_id`, `user_id`, `bus_id`, `date`, `seat_number`, `ticket_price`, `created_at`, `route_id`) VALUES
(2, 6, 2, '2024-12-28', 42, 0.00, '2024-12-27 13:50:37', 1),
(3, 6, 2, '2024-12-28', 19, 0.00, '2024-12-27 13:53:57', 1),
(4, 6, 2, '2024-12-28', 9, 0.00, '2024-12-27 14:01:59', 1),
(5, 1, 2, '2024-12-29', 18, 0.00, '2024-12-30 02:10:10', 1),
(7, 1, 2, '2024-12-29', 8, 0.00, '2024-12-30 03:26:51', 1),
(8, 1, 2, '2024-12-29', 20, 0.00, '2024-12-30 03:32:55', 1),
(9, 1, 2, '2024-12-29', 12, 0.00, '2024-12-30 03:34:27', 1),
(10, 1, 2, '2024-12-29', 33, 0.00, '2024-12-30 03:39:01', 1),
(11, 1, 2, '2024-12-29', 25, 0.00, '2024-12-30 03:40:39', 1),
(12, 1, 2, '2024-12-29', 35, 0.00, '2024-12-30 03:42:24', 1),
(13, 1, 2, '2024-12-29', 21, 0.00, '2024-12-30 03:51:08', 1),
(14, 1, 2, '2024-12-29', 2, 0.00, '2024-12-30 04:22:29', 1),
(15, 1, 2, '2024-12-29', 3, 0.00, '2024-12-30 04:27:34', 1),
(16, 1, 2, '2024-12-29', 4, 0.00, '2024-12-30 06:08:13', 1),
(17, 1, 2, '2024-12-29', 9, 0.00, '2024-12-30 06:12:55', 1),
(18, 2, 1, '2024-12-07', 12, 0.00, '2024-12-30 06:48:15', 1),
(19, 2, 3, '2024-12-31', 16, 0.00, '2024-12-30 07:23:17', 6),
(20, 1, 2, '2024-12-29', 34, 0.00, '2024-12-30 14:13:50', 1),
(21, 1, 2, '2024-12-29', 14, 0.00, '2024-12-30 14:18:55', 1),
(22, 1, 2, '2024-12-29', 16, 0.00, '2024-12-30 14:41:53', 1),
(23, 1, 2, '2024-12-29', 10, 0.00, '2024-12-30 14:45:40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`) VALUES
(2, 'aqeeb@email.com', '0000'),
(1, 'sayedtheidiot@gmail.com', '112233');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`bus_id`),
  ADD KEY `route_id` (`route_id`);

--
-- Indexes for table `new_users`
--
ALTER TABLE `new_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email_password` (`email`,`password`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`Schedule_id`),
  ADD KEY `route_id` (`route_id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `fk_route_id` (`route_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `email` (`email`,`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `bus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `new_users`
--
ALTER TABLE `new_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `Schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bus`
--
ALTER TABLE `bus`
  ADD CONSTRAINT `bus_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `route` (`route_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`bus_id`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `route` (`route_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`bus_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `fk_route_id` FOREIGN KEY (`route_id`) REFERENCES `route` (`route_id`),
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `ticket_ibfk_2` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`bus_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`email`,`password`) REFERENCES `new_users` (`email`, `password`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
