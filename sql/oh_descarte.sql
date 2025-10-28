-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2025 at 03:37 AM
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
-- Table structure for table `oh_descarte`
--

CREATE TABLE `oh_descarte` (
  `id` int(11) NOT NULL,
  `oh_descarte_tagid` varchar(10) NOT NULL,
  `oh_descarte_peso` decimal(10,2) NOT NULL,
  `oh_descarte_fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oh_descarte`
--

INSERT INTO `oh_descarte` (`id`, `oh_descarte_tagid`, `oh_descarte_peso`, `oh_descarte_fecha`) VALUES
(3, '3000', 300.00, '2025-02-04'),
(6, '3000', 250.00, '2025-03-16'),
(7, '4000', 333.00, '2025-03-18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oh_descarte`
--
ALTER TABLE `oh_descarte`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oh_descarte`
--
ALTER TABLE `oh_descarte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
