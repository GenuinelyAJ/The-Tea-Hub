-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2023 at 12:20 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tth`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `comment`, `created_at`) VALUES
(2, 3, 10, 'this is a test', '2023-06-10 15:31:01'),
(3, 1, 10, 'hi', '2023-06-10 17:04:26'),
(10, 3, 9, 'wow!', '2023-06-10 23:24:55'),
(11, 5, 9, 'can i have some?', '2023-06-11 00:10:44'),
(12, 6, 12, 'Wow! Nice mirror!', '2023-06-11 00:39:08'),
(13, 6, 9, 'That\'s so cool!!!', '2023-06-11 00:39:50'),
(14, 7, 13, 'Its nice to swim in the beach!', '2023-06-11 01:13:47'),
(15, 8, 9, 'It is!', '2023-06-11 01:15:48'),
(16, 5, 9, 'wow!', '2023-06-11 01:55:45');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`) VALUES
(5, 3, 10),
(10, 5, 9),
(11, 3, 9),
(12, 2, 9),
(13, 6, 12),
(14, 6, 9),
(15, 7, 13),
(16, 8, 9),
(21, 9, 9);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_img` varchar(255) NOT NULL,
  `post_text` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `post_img`, `post_text`, `created_at`) VALUES
(2, 10, '1686409366bVRNRPK9WQY3GM.jpg', 'The sea can see!', '2023-06-10 15:02:46'),
(3, 10, '1686409526photo1686006915 (2).jpeg', 'This project is so difficult!!!', '2023-06-10 15:05:26'),
(5, 11, '1686437357photo1686006909 (3).jpeg', 'This milkshake tastes awesome!', '2023-06-10 22:49:17'),
(6, 12, '1686443926photo1686006899 (4).jpeg', 'Our Smart Mirror Project!', '2023-06-11 00:38:46'),
(8, 13, '1686446067All-About-micro-bit--An-Integrated-STEM---Coding-Device-for-Kids-.png', 'Wow! It\'s a very cute robot!', '2023-06-11 01:14:27'),
(9, 9, '1686448627WIN_20211004_13_46_41_Pro.jpg', 'I feel ok!', '2023-06-11 01:57:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `profile_pic` text NOT NULL DEFAULT '\'default_profile.jpg\'',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `ac_status` int(11) NOT NULL COMMENT '0 = Not Active, 1 = Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `gender`, `email`, `username`, `password`, `profile_pic`, `created_at`, `updated_at`, `ac_status`) VALUES
(9, 'Aaron', 'Balagtas', 0, 'genuinelyaj@gmail.com', 'GenuinelyAJ', '202cb962ac59075b964b07152d234b70', '1686439907photo1684772918.jpeg', '2023-06-10 14:11:32', '2023-06-10 23:31:47', 1),
(10, 'Aaroo', 'Balagtas', 0, 'ajplayz10@gmail.com', 'AJ', '81dc9bdb52d04dc20036dbd8313ed055', '16864093434zwacj0jagk81.jpg', '2023-06-10 14:59:00', '2023-06-10 15:02:23', 1),
(11, 'Nicolas', 'Parilla', 1, 'nicolas@gmail.com', 'ickoparilla', 'e10adc3949ba59abbe56e057f20f883e', '1686437301profile5.jpg', '2023-06-10 22:46:34', '2023-06-10 22:48:21', 1),
(13, 'Aaron', 'Balagtas', 1, 'ajgamergaming@gmail.com', 'HeyItsAJ', '827ccb0eea8a706c4c34a16891f84e7b', '1686445977photo1686006899 (5).jpeg', '2023-06-11 01:10:33', '2023-06-11 01:12:57', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
