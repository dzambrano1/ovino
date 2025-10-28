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
-- Table structure for table `oc_parasitos`
--

CREATE TABLE `oc_parasitos` (
  `id` int(11) NOT NULL,
  `oc_parasitos_vacuna` varchar(30) NOT NULL,
  `oc_parasitos_dosis` decimal(10,2) NOT NULL,
  `oc_parasitos_costo` decimal(10,2) NOT NULL,
  `oc_parasitos_vigencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oc_parasitos`
--

INSERT INTO `oc_parasitos` (`id`, `oc_parasitos_vacuna`, `oc_parasitos_dosis`, `oc_parasitos_costo`, `oc_parasitos_vigencia`) VALUES
(1, 'CYDECTIN®', 2.00, 0.50, 180),
(2, 'TRIVANTEL', 2.00, 0.80, 180),
(3, 'Eprinex®', 2.00, 0.80, 180);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oc_parasitos`
--
ALTER TABLE `oc_parasitos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oc_parasitos`
--
ALTER TABLE `oc_parasitos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
