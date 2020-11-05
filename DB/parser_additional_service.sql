-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 03, 2020 at 06:46 AM
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
-- Table structure for table `parser_additional_service`
--

CREATE TABLE `parser_additional_service` (
  `id` int NOT NULL,
  `service` varchar(75) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parser_additional_service`
--

INSERT INTO `parser_additional_service` (`id`, `service`) VALUES
(3, 'Кондиционер'),
(4, 'Солнечная батарея'),
(5, 'Парковка'),
(6, 'Веранда'),
(7, 'Пол с подогревом'),
(8, 'Обустроенная кухня'),
(9, 'Кладовая'),
(10, 'Балкон'),
(11, 'Центральное отопление'),
(12, 'Камин'),
(13, 'Площадка для барбекю'),
(14, 'Сад'),
(15, 'Беседка'),
(16, 'Сауна'),
(17, 'Джакузи'),
(18, 'Система безопасности');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `parser_additional_service`
--
ALTER TABLE `parser_additional_service`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `parser_additional_service`
--
ALTER TABLE `parser_additional_service`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
