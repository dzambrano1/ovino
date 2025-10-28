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
-- Table structure for table `oc_clostridiosis`
--

CREATE TABLE `oc_clostridiosis` (
  `id` int(11) NOT NULL,
  `oc_clostridiosis_vacuna` varchar(30) NOT NULL,
  `oc_clostridiosis_dosis` decimal(10,2) NOT NULL,
  `oc_clostridiosis_costo` decimal(10,2) NOT NULL,
  `oc_clostridiosis_vigencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oc_clostridiosis`
--

INSERT INTO `oc_clostridiosis` (`id`, `oc_clostridiosis_vacuna`, `oc_clostridiosis_dosis`, `oc_clostridiosis_costo`, `oc_clostridiosis_vigencia`) VALUES
(1, 'Clostrisan 9 + T', 2.00, 0.81, 180),
(2, 'MULTICLOS', 2.00, 0.80, 180),
(3, 'Clostri FORTE 10', 2.00, 0.80, 180);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oc_clostridiosis`
--
ALTER TABLE `oc_clostridiosis`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oc_clostridiosis`
--
ALTER TABLE `oc_clostridiosis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
