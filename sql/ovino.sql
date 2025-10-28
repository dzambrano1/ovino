-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2025 at 12:55 PM
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
-- Table structure for table `vacuno`
--

CREATE TABLE `vacuno` (
  `id` int(10) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image2` varchar(255) NOT NULL,
  `image3` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `peso_nacimiento` double(5,2) NOT NULL,
  `especie` varchar(50) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `tagid` varchar(50) DEFAULT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `raza` varchar(50) DEFAULT NULL,
  `grupo` varchar(50) DEFAULT NULL,
  `estatus` varchar(50) DEFAULT NULL,
  `etapa` varchar(100) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `fecha_compra` date DEFAULT NULL,
  `peso_compra` double(5,2) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `fecha_venta` date DEFAULT NULL,
  `peso_venta` double(5,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `deceso_causa` varchar(30) NOT NULL,
  `deceso_fecha` date DEFAULT NULL,
  `descarte_fecha` date DEFAULT NULL,
  `descarte_peso` decimal(10,2) DEFAULT NULL,
  `descarte_precio` decimal(10,2) DEFAULT NULL,
  `destete_fecha` date DEFAULT NULL,
  `destete_peso` decimal(10,2) NOT NULL,
  `fecha_publicacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vacuno`
--

INSERT INTO `vacuno` (`id`, `image`, `image2`, `image3`, `video`, `fecha_nacimiento`, `peso_nacimiento`, `especie`, `nombre`, `tagid`, `genero`, `raza`, `grupo`, `estatus`, `etapa`, `edad`, `fecha_compra`, `peso_compra`, `precio_compra`, `fecha_venta`, `peso_venta`, `precio_venta`, `deceso_causa`, `deceso_fecha`, `descarte_fecha`, `descarte_peso`, `descarte_precio`, `destete_fecha`, `destete_peso`, `fecha_publicacion`) VALUES
(600, 'uploads/67b396c39ade1_1739822787.jpeg', 'uploads/67faa869d525f_1744480361.png', 'uploads/67faa869d5c2d_1744480361.png', 'uploads/videos/67faaa8d3a3d2_1744480909.mp4', '2023-08-12', 50.00, 'Vacuno', 'Lola', '3000', 'Hembra', 'Gyr', 'Preï¿½adas', 'Activo', 'Escotera', 653, '0000-00-00', 0.00, 0.00, NULL, 0.00, 0.00, 'Rayo', '2025-04-30', '2025-05-02', 300.00, 900.00, NULL, 0.00, '2025-01-08'),
(602, 'uploads/67fd6c0eeb929_1744661518.png', 'uploads/67fd6e95d0735_1744662165.png', 'uploads/67fd6c0eedc95_1744661518.png', 'uploads/videos/67fab7635f48b_1744484195.mp4', '2025-01-01', 50.00, 'Vacuno', 'Tomas', '5000', 'Macho', 'Holstein', 'Sanos', 'Activo', 'Inicio', 150, '0000-00-00', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-02-04'),
(605, 'uploads/67fac81def95b_1744488477.png', 'uploads/67fac81df0b64_1744488477.png', 'uploads/67fac81e0147f_1744488478.png', 'uploads/videos/67fac81e01cc7_1744488478.mp4', '2023-02-25', 50.00, 'Vacuno', 'Alegria', '9500', 'Hembra', 'Carora', 'Sanos', 'Activo', 'Parida', 821, '2025-04-12', 300.00, 500.00, '2025-06-01', 520.00, 500.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-02-04'),
(609, 'uploads/67fad35c8e19c_1744491356.png', 'uploads/67fd5746a12c8_1744656198.png', 'uploads/67fd561b68b23_1744655899.png', NULL, '2023-01-01', 55.00, 'Vacuno', 'Jeny', '8300', 'Hembra', 'Brahman', 'Sanos', 'Activo', 'Parida', 877, '0000-00-00', 0.00, 0.00, '2025-06-01', 500.00, 1500.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-02-04'),
(621, 'uploads/67fd5a0e43b8c_1744656910.png', 'uploads/67fd57b16cb53_1744656305.png', 'uploads/67fd5b0c42661_1744657164.png', NULL, '2023-01-16', 56.00, 'Vacuno', 'Roky', '15500', 'Macho', 'Brahman', 'Sanos', 'Activo', 'Engorde', 862, '0000-00-00', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-02-04'),
(622, 'uploads/67fd58513926d_1744656465.png', 'uploads/67fad17e21982_1744490878.png', 'uploads/67fad17e221fd_1744490878.png', NULL, '2023-01-01', 58.00, 'Vacuno', 'Domingo', '20000', 'Macho', 'Nelore', 'Sanos', 'Activo', 'Engorde', 877, '0000-00-00', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-04-07'),
(624, 'uploads/67fd4d598ed97_1744653657.png', 'uploads/67fd4d21edfd5_1744653601.png', 'uploads/67fd4df43b670_1744653812.png', NULL, '2023-02-18', 50.00, 'Vacuno', 'Oscar', '23000', 'Macho', 'Nelore', 'Sanos', 'Activo', 'Engorde', 829, '0000-00-00', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-03-31'),
(625, 'uploads/67fd4f9eabb89_1744654238.png', 'uploads/67fd50a53a226_1744654501.png', 'uploads/67fd514be5d02_1744654667.png', NULL, '2023-01-04', 51.00, 'Vacuno', 'Lento', '33000', 'Macho', 'Brahman', 'Sanos', 'Activo', 'Engorde', 874, '0000-00-00', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-02-04'),
(626, 'uploads/67fd52db575af_1744655067.png', 'uploads/67fd5279a6bd6_1744654969.png', 'uploads/67fadb9a035d4_1744493466.png', NULL, '2023-01-04', 53.00, 'Vacuno', 'Dinya', '23500', 'Hembra', '', 'Sanos', 'Activo', 'Parida', 874, '2024-03-06', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-01-20'),
(627, 'uploads/67fadce29ac03_1744493794.png', 'uploads/67fadce29b11e_1744493794.png', 'uploads/67fadce29b582_1744493794.png', NULL, '2022-12-15', 52.00, 'Vacuno', 'Rosa', '24560', 'Hembra', 'Guzerat', 'Sanos', 'Activo', 'Engorde', 894, '2024-04-18', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-03-21'),
(628, 'uploads/67fd5c2f41fd6_1744657455.png', 'uploads/67fd5c2f4285d_1744657455.png', 'uploads/67fd5c2f431dd_1744657455.png', NULL, '2022-06-01', 53.00, 'Vacuno', 'Humo', '27500', 'Macho', 'Carora', 'Sanos', 'Activo', 'Engorde', 1091, '2024-04-01', 0.00, 0.00, '2025-06-01', 500.00, 1000.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-02-10'),
(630, 'uploads/68114c9bd4b33_1745964187.png', 'uploads/68114c9bd548f_1745964187.jpg', 'uploads/68114c9bd92c4_1745964187.jpg', 'uploads/videos/68114c9bda356_1745964187.mp4', '2023-01-01', 60.00, 'Vacuno', 'Lester', '8210', 'Macho', 'Holstein', 'Sanos', 'Activo', 'Engorde', 873, '0000-00-00', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-03-06'),
(633, 'uploads/67fd5cdcc2ff2_1744657628.png', 'uploads/67fd5cdcc3929_1744657628.png', 'uploads/67fd5cdcc42a9_1744657628.png', NULL, '2023-01-02', 58.00, 'Vacuno', 'Blanca', '24200', 'Hembra', 'Pardo Suizo', 'Vacias', 'Activo', 'Produccion', 876, '2024-04-19', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-05-08'),
(634, 'uploads/67fd5e228055e_1744657954.png', 'uploads/67fd5e2281e36_1744657954.png', 'uploads/67fd5e2282e6c_1744657954.png', NULL, '2024-06-01', 50.00, 'Vacuno', 'Cantor', '45000', 'Macho', 'Romagnola', 'Sanos', 'Activo', 'Inicio', 360, '2023-06-01', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-02-01'),
(635, 'uploads/67fd5efd0cfd1_1744658173.png', 'uploads/67fd5efd0dc83_1744658173.png', 'uploads/67fd5efd0e797_1744658173.png', NULL, '2024-06-01', 45.00, 'Vacuno', 'Pedro', '599', 'Macho', 'Maine anjou', 'Sanos', 'Activo', 'Inicio', 360, '2023-06-30', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-04-01'),
(641, 'uploads/67fae7ff328bf_1744496639.png', 'uploads/67fd6002a6ede_1744658434.png', 'uploads/67fd604d63f84_1744658509.png', NULL, '2024-01-02', 46.00, 'Vacuno', 'Carla', '4001', 'Hembra', 'Holstein', 'Sanos', 'Activo', 'Crecimiento', 511, '2024-11-30', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-01-01'),
(644, 'uploads/67fd630e97987_1744659214.png', 'uploads/67fd630e97e06_1744659214.png', 'uploads/67fd630e9825c_1744659214.png', NULL, '2025-01-01', 49.00, 'Vacuno', 'Blanquin', '21214', 'Macho', 'Mosaico Perijanero', 'Sanos', 'Activo', 'Inicio', 146, '2025-01-01', 180.00, 360.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-01-05'),
(645, 'uploads/67fd63be6c17a_1744659390.png', 'uploads/67fd63be6c6cd_1744659390.png', 'uploads/67fd63be6cd81_1744659390.png', NULL, '2024-01-01', 50.00, 'Vacuno', 'Asterisc', '5266', 'Hembra', 'Wayu', 'Sanos', 'Activo', 'Parida', 512, '2024-01-01', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-04-05'),
(646, 'uploads/67faeb62ce2eb_1744497506.png', 'uploads/67fd645501bd4_1744659541.png', 'uploads/67faebe0a9980_1744497632.png', NULL, '2024-02-01', 52.00, 'Vacuno', 'Cachitos', '8985', 'Hembra', 'Nelore', 'Sanos', 'Activo', 'Crecimiento', 481, '2024-02-01', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, '2025-02-28'),
(647, 'uploads/681093846f453_carora-maute2.jpg', 'uploads/6810938472080_carora-maute3.jpg', 'uploads/6810938472561_carora1-imagen.jpeg', 'uploads/6810938472a6c_vaca2-mobile-registroca.mp4', '2024-10-01', 49.00, 'Vacuno', 'Bob', '2222', 'Macho', 'Carora', 'Sanos', 'Activo', 'Destete', 238, '2025-01-07', 300.00, 600.00, NULL, 0.00, 0.00, '', NULL, NULL, 0.00, NULL, NULL, 0.00, NULL),
(650, 'uploads/6813d2c0902f6_Nelore-pintado-image.png', 'uploads/6813d2c0914c8_Nelore-pintado-image2.png', 'uploads/6813d2c092861_Nelore-pintado-image3.jpg', 'uploads/6813d2c0931d0_nelore-pintado-video-mute-final.mp4', '2024-01-01', 48.00, 'Vacuno', 'Pintado', '12345', 'Macho', 'Nelore', 'Sanos', 'Activo', 'Crecimiento', 512, '2025-01-01', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(651, 'uploads/6813ec99569ef_jersey-becerro-image.jpg', 'uploads/6813ec99580a6_jersey-becerro-image2.jpg', 'uploads/6813ec99588fe_jersey-becerro-image3.jpg', 'uploads/6813ec99591e1_jersey-becerro-video.mp4', '2025-02-01', 54.00, 'Vacuno', 'Bambi', '54321', 'Macho', 'Jersey', 'Sanos', 'Activo', 'Inicio', 115, '0000-00-00', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(652, 'uploads/6823521bf39aa_1747145243.jpg', 'uploads/6823521c006f5_1747145244.png', 'uploads/6823521c0161e_1747145244.jpeg', 'uploads/videos/6823521c01fb7_1747145244.mp4', '2024-01-17', 46.00, 'Vacuno', 'Reina', '777', 'Hembra', 'Gyr', 'Sanos', 'Activo', 'Crecimiento', 496, '2025-01-01', 0.00, 0.00, NULL, 0.00, 0.00, '', NULL, NULL, NULL, NULL, NULL, 0.00, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vacuno`
--
ALTER TABLE `vacuno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `tagid` (`tagid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vacuno`
--
ALTER TABLE `vacuno`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=653;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
