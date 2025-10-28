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
-- Table structure for table `oc_melaza`
--

CREATE TABLE `oc_melaza` (
  `id` int(11) NOT NULL,
  `oc_melaza_nombre` varchar(30) NOT NULL,
  `oc_melaza_etapa` varchar(30) NOT NULL,
  `oc_melaza_racion` decimal(10,2) NOT NULL,
  `oc_melaza_costo` decimal(10,2) NOT NULL,
  `oc_melaza_vigencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oc_melaza`
--

INSERT INTO `oc_melaza` (`id`, `oc_melaza_nombre`, `oc_melaza_etapa`, `oc_melaza_racion`, `oc_melaza_costo`, `oc_melaza_vigencia`) VALUES
(1, 'Melaza Asobarinas', 'Sementales', 1.53, 2.35, 180),
(3, 'Melaza Lasso', 'Crecimiento', 0.90, 0.90, 32),
(5, 'Melaza La Espiga', 'Finalizacion', 0.85, 0.65, 33);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oc_melaza`
--
ALTER TABLE `oc_melaza`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oc_melaza`
--
ALTER TABLE `oc_melaza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
