-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2025 at 03:14 PM
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
-- Database: `cinebook`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_reference` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `movie_title` varchar(200) NOT NULL,
  `seats` int(11) NOT NULL,
  `showtime` varchar(50) NOT NULL,
  `booking_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `booking_status` enum('Confirmed','Cancelled','Completed') DEFAULT 'Confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_reference`, `user_id`, `movie_id`, `movie_title`, `seats`, `showtime`, `booking_date`, `total_price`, `booking_status`, `created_at`) VALUES
(1, 'BK12345678', 1, 3, 'Multiverse of Madness', 1, '04:00 PM', '2025-10-25', 250.00, 'Confirmed', '2025-10-21 18:00:11'),
(2, 'BK23456789', 1, 2, 'Baaghi 4', 1, '04:00 PM', '2025-10-26', 250.00, 'Confirmed', '2025-10-21 18:00:34'),
(3, 'BK34567890', 2, 5, 'Jolly LLB 3', 1, '01:00 PM', '2025-10-21', 250.00, 'Confirmed', '2025-10-21 18:03:15');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `email`, `phone`, `message`, `created_at`) VALUES
(1, NULL, 'bharti@gmail.com', '1234567890', 'very niceee..', '2025-10-27 14:13:39');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `poster_image` varchar(255) DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL COMMENT 'Duration in minutes',
  `language` varchar(50) DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL COMMENT 'Movie rating out of 10',
  `status` enum('Now Showing','Coming Soon','Not Showing') DEFAULT 'Now Showing',
  `release_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `description`, `poster_image`, `genre`, `duration`, `language`, `rating`, `status`, `release_date`, `created_at`) VALUES
(1, 'The Conjuring: Last Rites', 'Dark and eerie supernatural horror with intense demonic elements', 'TheConjuring.webp', 'Horror', 130, 'English', 8.5, 'Now Showing', '2025-10-15', '2025-10-21 17:55:43'),
(2, 'Baaghi 4', 'Action-packed thriller with explosive choreography', 'Baaghi4.webp', 'Action', 145, 'Hindi', 7.8, 'Now Showing', '2025-10-10', '2025-10-21 17:55:43'),
(3, 'Multiverse of Madness', 'Marvel superhero adventure through multiple dimensions', 'Multiverse_of_Madness.webp', 'Action/Sci-Fi', 126, 'English', 8.2, 'Now Showing', '2025-10-05', '2025-10-21 17:55:43'),
(4, 'Coolie', 'Epic action drama starring Rajinikanth', 'coolie.webp', 'Action/Drama', 150, 'Tamil', 8.0, 'Now Showing', '2025-10-08', '2025-10-21 17:55:43'),
(5, 'Jolly LLB 3', 'Black comedy legal drama', 'Jolly-LLB-3.webp', 'Comedy/Drama', 135, 'Hindi', 7.5, 'Now Showing', '2025-10-12', '2025-10-21 17:55:43'),
(6, 'Mirai', 'Warrior protects nine sacred scriptures', 'mirai-poster.webp', 'Action/Adventure', 120, 'Japanese', 7.9, 'Now Showing', '2025-10-18', '2025-10-21 17:55:43'),
(7, 'Ne Zha 2', 'Animated fantasy adventure', 'Ne_Zha_2.webp', 'Animation/Fantasy', 110, 'Chinese', 8.3, 'Now Showing', '2025-10-20', '2025-10-21 17:55:43'),
(8, 'Thama', 'A bloody love story in this universe', 'Thama.webp', 'Horror/Romance', 125, 'Hindi', 7.2, 'Now Showing', '2025-10-22', '2025-10-21 17:55:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `city`, `address`, `gender`, `date_of_birth`, `last_login`, `created_at`) VALUES
(1, 'Abhinav Bharti', 'abhinav@gmail.com', '6738369244', '$2y$10$E64hlBjY1tdvp1njh7dz7OMROBLupPsg011Cg3us9vYqBGlzZf0vG', 'Varanasi', NULL, 'Male', NULL, '2025-10-21 17:59:33', '2025-10-21 17:59:33'),
(2, 'Ravi', 'ravi@123gmail.com', '9876543210', '$2y$10$MmwPSJIdL0jgIjE8EDxk3.61CsvaG9.TvAKQzcnHCiDdu82t47AgW', 'Patna', NULL, 'Male', NULL, '2025-10-21 18:02:39', '2025-10-21 18:02:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_reference` (`booking_reference`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `idx_booking_date` (`booking_date`),
  ADD KEY `idx_status` (`booking_status`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_rating` (`rating`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
