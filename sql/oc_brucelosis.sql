-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2025 at 03:34 AM
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
-- Table structure for table `oc_brucelosis`
--

CREATE TABLE `oc_brucelosis` (
  `id` int(11) NOT NULL,
  `oc_brucelosis_vacuna` varchar(30) NOT NULL,
  `oc_brucelosis_dosis` decimal(10,2) NOT NULL,
  `oc_brucelosis_costo` decimal(10,2) NOT NULL,
  `oc_brucelosis_vigencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oc_brucelosis`
--

INSERT INTO `oc_brucelosis` (`id`, `oc_brucelosis_vacuna`, `oc_brucelosis_dosis`, `oc_brucelosis_costo`, `oc_brucelosis_vigencia`) VALUES
(1, 'VAC-SULES RB51', 2.00, 0.88, 180),
(2, 'CZV REV-1	', 2.00, 0.80, 180);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oc_brucelosis`
--
ALTER TABLE `oc_brucelosis`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oc_brucelosis`
--
ALTER TABLE `oc_brucelosis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
