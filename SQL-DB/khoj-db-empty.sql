-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 08, 2024 at 06:21 PM
-- Server version: 10.6.16-MariaDB-0ubuntu0.22.04.1
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `khoj`
--

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `siteUrl` varchar(512) NOT NULL,
  `imageUrl` varchar(512) NOT NULL,
  `alt` varchar(512) NOT NULL,
  `title` varchar(512) NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT 0,
  `broken` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_english`
--

CREATE TABLE `news_english` (
  `id` int(11) NOT NULL,
  `guid` varchar(255) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `pubDate` datetime DEFAULT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `content_encoded` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_hindi`
--

CREATE TABLE `news_hindi` (
  `id` int(11) NOT NULL,
  `guid` varchar(255) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `pubDate` datetime DEFAULT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `content_encoded` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_marathi`
--

CREATE TABLE `news_marathi` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `pubDate` datetime DEFAULT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `id` int(11) NOT NULL,
  `url` varchar(512) NOT NULL,
  `title` varchar(512) NOT NULL,
  `description` text NOT NULL,
  `keywords` varchar(512) NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_english`
--
ALTER TABLE `news_english`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_hindi`
--
ALTER TABLE `news_hindi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_marathi`
--
ALTER TABLE `news_marathi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
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
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_english`
--
ALTER TABLE `news_english`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_hindi`
--
ALTER TABLE `news_hindi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_marathi`
--
ALTER TABLE `news_marathi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
