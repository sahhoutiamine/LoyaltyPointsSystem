-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 12, 2026 at 08:48 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `facileachatdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `points_transactions`
--

CREATE TABLE `points_transactions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `type` enum('earned','redeemed','expired') NOT NULL,
  `amount` int NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `balance_after` int NOT NULL,
  `createdat` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `points_transactions`
--

INSERT INTO `points_transactions` (`id`, `user_id`, `type`, `amount`, `description`, `balance_after`, `createdat`) VALUES
(1, 1, 'earned', 1000, 'Welcome bonus', 1000, '2026-01-12 08:44:10'),
(2, 1, 'earned', 300, 'Purchase reward', 1300, '2026-01-12 08:44:10'),
(3, 1, 'redeemed', -100, '5% discount coupon', 1200, '2026-01-12 08:44:10'),
(4, 2, 'earned', 500, 'Signup bonus', 500, '2026-01-12 08:44:10'),
(5, 2, 'redeemed', -200, '10% discount coupon', 300, '2026-01-12 08:44:10'),
(6, 3, 'earned', 200, 'Referral bonus', 200, '2026-01-12 08:44:10'),
(7, 3, 'expired', -200, 'Points expired', 0, '2026-01-12 08:44:10'),
(8, 4, 'earned', 800, 'Order cashback', 800, '2026-01-12 08:44:10'),
(9, 4, 'redeemed', -300, 'Free shipping reward', 500, '2026-01-12 08:44:10'),
(10, 5, 'earned', 300, 'Welcome bonus', 300, '2026-01-12 08:44:10'),
(11, 6, 'earned', 1500, 'Annual promotion', 1500, '2026-01-12 08:44:10'),
(12, 7, 'earned', 950, 'Loyalty reward', 950, '2026-01-12 08:44:10'),
(13, 8, 'earned', 200, 'Signup bonus', 200, '2026-01-12 08:44:10'),
(14, 9, 'earned', 400, 'Purchase points', 400, '2026-01-12 08:44:10'),
(15, 10, 'earned', 1000, 'Holiday bonus', 1000, '2026-01-12 08:44:10');

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `points_required` int NOT NULL,
  `description` text,
  `stock` int DEFAULT '-1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`id`, `name`, `points_required`, `description`, `stock`) VALUES
(1, '5% Discount Coupon', 150, '5% off your next order', -1),
(2, '10% Discount Coupon', 300, '10% off your next order', -1),
(3, 'Free Shipping', 500, 'Free shipping on one order', -1),
(4, 'Coffee Voucher', 700, 'Free coffee voucher', 100),
(5, 'Gift Card $5', 800, '$5 digital gift card', 50),
(6, 'Gift Card $10', 1000, '$10 digital gift card', 40),
(7, 'Premium Membership (1 Month)', 1500, '1 month premium access', 20),
(8, 'Premium Membership (3 Months)', 2500, '3 months premium access', 10),
(9, 'Exclusive Mug', 1200, 'Limited edition mug', 30),
(10, 'VIP Event Access', 3000, 'Access to VIP event', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `total_points` int DEFAULT '0',
  `createdat` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `name`, `total_points`, `createdat`) VALUES
(1, 'alice@example.com', '$2y$10$hashalice', 'Alice Johnson', 1200, '2026-01-12 08:43:31'),
(2, 'bob@example.com', '$2y$10$hashbob', 'Bob Smith', 500, '2026-01-12 08:43:31'),
(3, 'carol@example.com', '$2y$10$hashcarol', 'Carol White', 0, '2026-01-12 08:43:31'),
(4, 'david@example.com', '$2y$10$hashdavid', 'David Brown', 800, '2026-01-12 08:43:31'),
(5, 'emma@example.com', '$2y$10$hashemma', 'Emma Wilson', 300, '2026-01-12 08:43:31'),
(6, 'frank@example.com', '$2y$10$hashfrank', 'Frank Miller', 1500, '2026-01-12 08:43:31'),
(7, 'grace@example.com', '$2y$10$hashgrace', 'Grace Taylor', 950, '2026-01-12 08:43:31'),
(8, 'henry@example.com', '$2y$10$hashhenry', 'Henry Anderson', 200, '2026-01-12 08:43:31'),
(9, 'irene@example.com', '$2y$10$hashirene', 'Irene Thomas', 400, '2026-01-12 08:43:31'),
(10, 'jack@example.com', '$2y$10$hashjack', 'Jack Martin', 1000, '2026-01-12 08:43:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `points_transactions`
--
ALTER TABLE `points_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `points_transactions`
--
ALTER TABLE `points_transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `points_transactions`
--
ALTER TABLE `points_transactions`
  ADD CONSTRAINT `points_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
