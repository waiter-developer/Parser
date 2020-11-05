-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 03, 2020 at 06:47 AM
-- Server version: 8.0.22-0ubuntu0.20.04.2
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `parser_products`
--

CREATE TABLE `parser_products` (
  `id` int NOT NULL,
  `title` varchar(75) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `local_place` varchar(30) NOT NULL,
  `term` varchar(25) NOT NULL,
  `status` varchar(20) NOT NULL,
  `price` varchar(15) NOT NULL,
  `property_type` varchar(10) NOT NULL,
  `bedrooms` varchar(15) NOT NULL,
  `distance_sea` varchar(12) NOT NULL,
  `bathrooms` int NOT NULL,
  `pool` varchar(15) NOT NULL,
  `since_year` int NOT NULL,
  `furniture` varchar(12) NOT NULL,
  `land_area` varchar(15) NOT NULL,
  `covered_area` varchar(15) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `parser_products`
--
ALTER TABLE `parser_products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `parser_products`
--
ALTER TABLE `parser_products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
