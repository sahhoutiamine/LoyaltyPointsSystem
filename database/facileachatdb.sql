-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 13, 2026 at 11:12 AM
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
  `type` enum('earned','redeemed','expired') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance_after` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `points_transactions`
--

INSERT INTO `points_transactions` (`id`, `user_id`, `type`, `amount`, `description`, `balance_after`, `created_at`) VALUES
(1, 1, 'earned', 20, 'Points earned from purchase #1', 20, '2024-01-16 10:20:00'),
(2, 1, 'earned', 10, 'Points earned from purchase #2', 30, '2024-02-05 13:30:00'),
(3, 1, 'earned', 40, 'Points earned from purchase #3', 70, '2024-03-12 15:45:00'),
(4, 1, 'earned', 50, 'Welcome bonus points', 120, '2024-01-15 09:30:00'),
(5, 1, 'earned', 100, 'Birthday bonus', 220, '2024-05-09 22:00:00'),
(6, 1, 'redeemed', -500, 'Redeemed: 5$ Discount Voucher', 850, '2024-06-01 12:20:00'),
(7, 2, 'earned', 30, 'Points earned from purchase #4', 30, '2024-02-21 09:15:00'),
(8, 2, 'earned', 50, 'Points earned from purchase #5', 80, '2024-03-15 12:40:00'),
(9, 2, 'earned', 30, 'Points earned from purchase #6', 110, '2024-04-02 13:20:00'),
(10, 2, 'earned', 50, 'Welcome bonus points', 160, '2024-02-20 13:45:00'),
(11, 2, 'earned', 100, 'Referral bonus', 260, '2024-03-01 09:00:00'),
(12, 2, 'earned', 200, 'Special promotion', 460, '2024-04-15 10:00:00'),
(13, 2, 'redeemed', -750, 'Redeemed: Free Shipping', 1250, '2024-05-20 14:30:00'),
(14, 3, 'earned', 10, 'Points earned from purchase #7', 10, '2024-03-11 11:30:00'),
(15, 3, 'earned', 30, 'Points earned from purchase #8', 40, '2024-04-01 07:45:00'),
(16, 3, 'earned', 50, 'Welcome bonus points', 90, '2024-03-10 08:15:00'),
(17, 3, 'earned', 100, 'Survey completion bonus', 190, '2024-04-10 12:00:00'),
(18, 3, 'redeemed', -500, 'Redeemed: 5$ Discount Voucher', 450, '2024-05-15 09:20:00'),
(19, 4, 'earned', 80, 'Points earned from purchase #9', 80, '2024-01-08 13:20:00'),
(20, 4, 'earned', 60, 'Points earned from purchase #10', 140, '2024-02-14 10:30:00'),
(21, 4, 'earned', 50, 'Points earned from purchase #11', 190, '2024-03-20 15:00:00'),
(22, 4, 'earned', 50, 'Welcome bonus points', 240, '2024-01-05 15:20:00'),
(23, 4, 'earned', 150, 'VIP tier bonus', 390, '2024-01-31 23:00:00'),
(24, 4, 'earned', 200, 'Loyalty milestone - 5 purchases', 590, '2024-03-25 09:00:00'),
(25, 4, 'earned', 300, 'Special VIP promotion', 890, '2024-04-20 13:00:00'),
(26, 4, 'redeemed', -1000, 'Redeemed: 10$ Discount Voucher', 2100, '2024-05-10 11:45:00'),
(27, 5, 'earned', 50, 'Welcome bonus points', 50, '2026-01-13 11:11:25'),
(28, 5, 'earned', 100, 'Registration bonus', 150, '2026-01-13 11:11:25'),
(29, 5, 'earned', 200, 'First purchase bonus', 350, '2026-01-13 11:11:25'),
(30, 5, 'earned', 150, 'Points earned from demo purchase', 500, '2026-01-13 11:11:25');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `total_amount`, `status`, `created_at`) VALUES
(1, 1, 250.00, 'completed', '2024-01-16 10:20:00'),
(2, 1, 180.50, 'completed', '2024-02-05 13:30:00'),
(3, 1, 420.75, 'completed', '2024-03-12 15:45:00'),
(4, 2, 320.00, 'completed', '2024-02-21 09:15:00'),
(5, 2, 550.25, 'completed', '2024-03-15 12:40:00'),
(6, 2, 380.00, 'completed', '2024-04-02 13:20:00'),
(7, 3, 150.00, 'completed', '2024-03-11 11:30:00'),
(8, 3, 300.50, 'completed', '2024-04-01 07:45:00'),
(9, 4, 890.00, 'completed', '2024-01-08 13:20:00'),
(10, 4, 670.50, 'completed', '2024-02-14 10:30:00'),
(11, 4, 540.75, 'completed', '2024-03-20 15:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `points_required` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `stock` int DEFAULT '-1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`id`, `name`, `points_required`, `description`, `stock`) VALUES
(1, '5$ Discount Voucher', 500, 'Get $5 off your next purchase', -1),
(2, '10$ Discount Voucher', 1000, 'Get $10 off your next purchase', -1),
(3, 'Free Shipping', 750, 'Free shipping on your next order', -1),
(4, '20$ Discount Voucher', 2000, 'Get $20 off your next purchase', -1),
(5, 'Premium Gift Box', 1500, 'Exclusive premium gift box with surprise items', 50),
(6, 'VIP Membership (1 Month)', 3000, 'Access to VIP benefits for 1 month', 20),
(7, '50$ Discount Voucher', 5000, 'Get $50 off your next purchase', -1),
(8, 'Birthday Cake Voucher', 800, 'Free birthday cake from our partner bakery', 30),
(9, 'Express Delivery Pass', 600, 'Express delivery on your next 3 orders', 100),
(10, 'Mystery Gift', 1200, 'Surprise mystery gift worth at least $15', 25);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_points` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `name`, `total_points`, `created_at`) VALUES
(1, 'john.doe@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', 850, '2024-01-15 09:30:00'),
(2, 'jane.smith@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', 1250, '2024-02-20 13:45:00'),
(3, 'bob.wilson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Bob Wilson', 450, '2024-03-10 08:15:00'),
(4, 'alice.brown@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Alice Brown', 2100, '2024-01-05 15:20:00'),
(5, 'test@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test User', 500, '2026-01-13 11:11:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `points_transactions`
--
ALTER TABLE `points_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_created` (`user_id`,`created_at` DESC),
  ADD KEY `idx_type` (`type`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_status` (`user_id`,`status`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_points` (`points_required`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `points_transactions`
--
ALTER TABLE `points_transactions`
  ADD CONSTRAINT `points_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
