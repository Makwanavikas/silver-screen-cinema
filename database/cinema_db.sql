-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
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
-- Database: `cinema_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `show_id` int(11) NOT NULL,
  `seat_numbers` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- (no sample data for `bookings`)
--


-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `release_year` int(11) NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `poster` varchar(255) NOT NULL,
  `trailer_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `genre`, `release_year`, `rating`, `poster`, `trailer_url`) VALUES
(3, 'Border 2', 'Action', 2026, 7.4, 'Border2.png', 'https://www.youtube.com/watch?v=AZGfCK1yTTI'),
(4, 'Avatar: Fire and Ash', 'Animation', 2026, 7.4, 'Avatar.png', 'https://www.youtube.com/watch?v=nb_fFj_0rq8&t=6s'),
(5, 'Kalki 2898 AD', 'Sci-Fi / Action', 2024, 7.0, 'Screenshot 2026-04-08 064129.png', 'https://www.youtube.com/watch?v=kQDd1AhGIHk'),
(6, 'Dhurandhar 2: The Revenge', 'Action / Spy Thriller', 2026, 9.2, 'Screenshot 2026-04-08 063748.png', 'https://www.youtube.com/watch?v=NHk7scrb_9I'),
(7, 'Stree 2', 'Horror / Comedy', 2024, 6.9, 'Screenshot 2026-04-08 064222.png', 'https://www.youtube.com/watch?v=KVnheXywIbY'),
(8, 'Animal', 'Action / Drama', 2023, 6.2, 'Screenshot 2026-04-08 064307.png', 'https://www.youtube.com/watch?v=8FkLRUJj-o0');

-- --------------------------------------------------------

--
-- Table structure for table `otp_verification`
--

CREATE TABLE `otp_verification` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- (no sample data for `otp_verification`)
--


-- --------------------------------------------------------

--
-- Table structure for table `shows`
--

CREATE TABLE `shows` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `theater_id` int(11) NOT NULL,
  `show_time` datetime NOT NULL,
  `screen_number` int(11) NOT NULL,
  `classic_price` decimal(10,2) NOT NULL DEFAULT 120.00,
  `prime_price` decimal(10,2) NOT NULL DEFAULT 150.00,
  `recliner_price` decimal(10,2) NOT NULL DEFAULT 250.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shows`
--

INSERT INTO `shows` (`id`, `movie_id`, `theater_id`, `show_time`, `screen_number`, `classic_price`, `prime_price`, `recliner_price`) VALUES
(5, 4, 9, '2026-03-22 17:00:00', 1, 120.00, 150.00, 250.00),
(10, 6, 2, '2026-04-08 13:00:00', 1, 120.00, 150.00, 250.00),
(11, 6, 9, '2026-04-09 15:00:00', 2, 120.00, 150.00, 250.00),
(12, 6, 9, '2026-04-08 15:00:00', 1, 120.00, 150.00, 250.00),
(13, 6, 6, '2026-04-15 17:00:00', 2, 150.00, 200.00, 250.00),
(14, 4, 2, '2026-05-10 14:00:00', 2, 120.00, 150.00, 250.00);

-- --------------------------------------------------------

--
-- Table structure for table `theaters`
--

CREATE TABLE `theaters` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `total_screens` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `theaters`
--

INSERT INTO `theaters` (`id`, `name`, `city`, `location`, `total_screens`) VALUES
(2, 'SilverScreen Inorbit', 'Vadodara', 'Gorwa Road, Subhanpura', 6),
(3, 'SilverScreen Palladium', 'Mumbai', 'Lower Parel', 8),
(4, 'SilverScreen VR Mall', 'Surat', 'Dumas Road, Magdalla', 5),
(5, 'SilverScreen AlphaOne', 'Ahmedabad', 'Vastrapur Lake, Vastrapur', 6),
(6, 'SilverScreen Acropolis', 'Ahmedabad', 'SG Highway, Thaltej', 4),
(7, 'SilverScreen Select Citywalk', 'Delhi', 'Saket District Centre', 8),
(8, 'SilverScreen Phoenix', 'Pune', 'Viman Nagar', 7),
(9, 'SilverScreen City Center', 'Nadiad', 'College Road, near DDIT', 3);

-- --------------------------------------------------------

--
-- Table structure for table `trailers`
--

CREATE TABLE `trailers` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `youtube_url` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trailers`
--

INSERT INTO `trailers` (`id`, `title`, `youtube_url`, `thumbnail`, `created_at`) VALUES
(1, 'Pushpa 2', 'https://www.youtube.com/embed/1kVK0MZlbI4', 'Screenshot 2026-03-06 143153.png', '2026-03-06 09:02:10'),
(2, 'Kgf 2', 'https://www.youtube.com/embed/JKa05nyUmuQ', 'kgf.jpg', '2026-03-06 09:33:47'),
(3, 'Animal', 'https://www.youtube.com/embed/8FkLRUJj-o0', 'Screenshot 2026-04-08 065217.png', '2026-04-08 01:23:23'),
(4, 'Stree 2', 'https://www.youtube.com/embed/KVnheXywIbY', 'Screenshot 2026-04-08 065402.png', '2026-04-08 01:25:02'),
(5, 'Kalki 2898 AD ', 'https://www.youtube.com/embed/kQDd1AhGIHk', 'Screenshot 2026-04-08 065612.png', '2026-04-08 01:27:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `role` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `is_verified`, `role`) VALUES
(1, 'Demo Admin', 'admin@example.com', '$2y$12$WFRfLqJbi4GoeOTxoaeFbe37xMnpKZ5GZRQq1EBRlgKPSHgeuCsy6', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_verification`
--
ALTER TABLE `otp_verification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shows`
--
ALTER TABLE `shows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_show_movie` (`movie_id`),
  ADD KEY `fk_show_theater` (`theater_id`);

--
-- Indexes for table `theaters`
--
ALTER TABLE `theaters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trailers`
--
ALTER TABLE `trailers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `otp_verification`
--
ALTER TABLE `otp_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `shows`
--
ALTER TABLE `shows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `theaters`
--
ALTER TABLE `theaters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `trailers`
--
ALTER TABLE `trailers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `shows`
--
ALTER TABLE `shows`
  ADD CONSTRAINT `fk_show_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_show_theater` FOREIGN KEY (`theater_id`) REFERENCES `theaters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;