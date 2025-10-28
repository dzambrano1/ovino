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
-- Table structure for table `oc_concentrado`
--

CREATE TABLE `oc_concentrado` (
  `id` int(11) NOT NULL,
  `oc_concentrado_nombre` varchar(30) NOT NULL,
  `oc_concentrado_etapa` varchar(30) NOT NULL,
  `oc_concentrado_racion` decimal(10,2) NOT NULL,
  `oc_concentrado_costo` decimal(10,2) NOT NULL,
  `oc_concentrado_vigencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oc_concentrado`
--

INSERT INTO `oc_concentrado` (`id`, `oc_concentrado_nombre`, `oc_concentrado_etapa`, `oc_concentrado_racion`, `oc_concentrado_costo`, `oc_concentrado_vigencia`) VALUES
(1, 'Ovina 32', 'Inicio', 1.53, 2.35, 30),
(3, 'ABA Ovinos', 'Crecimiento', 0.90, 0.99, 30),
(5, 'NutriBLOCK Ovino', 'Finalizacion', 0.85, 0.65, 33);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oc_concentrado`
--
ALTER TABLE `oc_concentrado`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oc_concentrado`
--
ALTER TABLE `oc_concentrado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
