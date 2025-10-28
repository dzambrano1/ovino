-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2025 at 03:33 AM
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
-- Table structure for table `oc_aftosa`
--

CREATE TABLE `oc_aftosa` (
  `id` int(11) NOT NULL,
  `oc_aftosa_vacuna` varchar(30) NOT NULL,
  `oc_aftosa_dosis` decimal(10,2) NOT NULL,
  `oc_aftosa_costo` decimal(10,2) NOT NULL,
  `oc_aftosa_vigencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oc_aftosa`
--

INSERT INTO `oc_aftosa` (`id`, `oc_aftosa_vacuna`, `oc_aftosa_dosis`, `oc_aftosa_costo`, `oc_aftosa_vigencia`) VALUES
(1, 'AFTOFORTE', 2.00, 0.35, 180),
(3, 'AFTOVAC B	', 2.00, 0.58, 180),
(5, 'AFTOSAN 3', 2.00, 0.58, 180);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oc_aftosa`
--
ALTER TABLE `oc_aftosa`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oc_aftosa`
--
ALTER TABLE `oc_aftosa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
