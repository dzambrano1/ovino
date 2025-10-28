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
-- Table structure for table `oc_sal`
--

CREATE TABLE `oc_sal` (
  `id` int(11) NOT NULL,
  `oc_sal_nombre` varchar(30) NOT NULL,
  `oc_sal_etapa` varchar(30) NOT NULL,
  `oc_sal_racion` decimal(10,2) NOT NULL,
  `oc_sal_costo` decimal(10,2) NOT NULL,
  `oc_sal_vigencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oc_sal`
--

INSERT INTO `oc_sal` (`id`, `oc_sal_nombre`, `oc_sal_etapa`, `oc_sal_racion`, `oc_sal_costo`, `oc_sal_vigencia`) VALUES
(1, 'VitaSal ', 'Inicio', 1.53, 2.35, 30),
(3, 'Sales Minerales La Vaquita', 'Crecimiento', 0.90, 0.90, 30),
(4, 'ADE CALBOV', 'Finalizacion', 0.50, 1.50, 30);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oc_sal`
--
ALTER TABLE `oc_sal`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oc_sal`
--
ALTER TABLE `oc_sal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
