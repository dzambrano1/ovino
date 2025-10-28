-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2025 at 03:35 AM
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
-- Database: `ganagram`
--

-- --------------------------------------------------------

--
-- Table structure for table `oc_razas`
--

CREATE TABLE `oc_razas` (
  `id` int(11) NOT NULL,
  `oc_razas_nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oc_razas`
--

INSERT INTO `oc_razas` (`id`, `oc_razas_nombre`) VALUES
(1, 'Dorper'),
(3, 'White Dorper'),
(4, 'Katahdin'),
(5, 'Santa Inés'),
(6, 'Black Belly'),
(7, 'Persa Cabeza Negra'),
(8, 'Criollo'),
(9, 'Pelibuey'),
(10, 'Charollais'),
(11, 'Bergamasca'),
(12, 'Lacaune'),
(14, 'Assaf'),
(15, 'Dorset'),
(16, 'Churra'),
(17, 'Suffolk'),
(20, 'Texel');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oc_razas`
--
ALTER TABLE `oc_razas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oc_razas`
--
ALTER TABLE `oc_razas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
