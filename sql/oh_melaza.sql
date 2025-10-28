-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2025 at 03:38 AM
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
-- Table structure for table `oh_melaza`
--

CREATE TABLE `oh_melaza` (
  `id` int(11) NOT NULL,
  `oh_melaza_tagid` varchar(10) NOT NULL,
  `oh_melaza_etapa` varchar(25) NOT NULL,
  `oh_melaza_producto` varchar(50) NOT NULL,
  `oh_melaza_racion` decimal(10,2) NOT NULL,
  `oh_melaza_costo` decimal(10,2) NOT NULL,
  `oh_melaza_fecha_inicio` date NOT NULL,
  `oh_melaza_fecha_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oh_melaza`
--

INSERT INTO `oh_melaza` (`id`, `oh_melaza_tagid`, `oh_melaza_etapa`, `oh_melaza_producto`, `oh_melaza_racion`, `oh_melaza_costo`, `oh_melaza_fecha_inicio`, `oh_melaza_fecha_fin`) VALUES
(713, '24200', 'PRE-INICIADOR', 'Melaza Asobarinas', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(714, '24200', 'INICIADOR', 'Melaza Asobarinas', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(715, '24200', 'INICIADOR', 'Melaza Asobarinas', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(716, '24200', 'DESTETE', 'Melaza Asobarinas', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(717, '24200', 'CRECIMIENTO', 'Melaza Asobarinas', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(718, '24200', 'ENGORDE', 'Melaza Asobarinas', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(719, '24200', 'FINALIZADOR', 'Melaza Asobarinas', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(720, '23500', 'PRE-INICIADOR', 'Melaza Asobarinas', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(721, '23500', 'INICIADOR', 'Melaza Asobarinas', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(722, '23500', 'INICIADOR', 'Melaza Asobarinas', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(723, '23500', 'DESTETE', 'Melaza Asobarinas', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(724, '23500', 'CRECIMIENTO', 'Melaza Asobarinas', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(725, '23500', 'ENGORDE', 'Melaza Asobarinas', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(726, '23500', 'FINALIZADOR', 'Melaza Asobarinas', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(727, '24560', 'PRE-INICIADOR', 'Melaza Asobarinas', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(728, '24560', 'INICIADOR', 'Melaza Asobarinas', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(729, '24560', 'INICIADOR', 'Melaza Asobarinas', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(730, '24560', 'DESTETE', 'Melaza Asobarinas', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(731, '24560', 'CRECIMIENTO', 'Melaza Asobarinas', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(732, '24560', 'ENGORDE', 'Melaza Asobarinas', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(733, '24560', 'FINALIZADOR', 'Melaza Asobarinas', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(734, '3000', 'PRE-INICIADOR', 'Melaza Asobarinas', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(735, '3000', 'INICIADOR', 'Melaza Asobarinas', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(736, '3000', 'INICIADOR', 'Melaza Asobarinas', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(737, '3000', 'DESTETE', 'Melaza Asobarinas', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(738, '3000', 'CRECIMIENTO', 'Melaza Lasso', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(739, '3000', 'ENGORDE', 'Melaza Lasso', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(740, '3000', 'FINALIZADOR', 'Melaza Lasso', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(741, '4001', 'PRE-INICIADOR', 'Melaza Lasso', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(742, '4001', 'INICIADOR', 'Melaza Lasso', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(743, '4001', 'INICIADOR', 'Melaza Lasso', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(744, '4001', 'DESTETE', 'Melaza Lasso', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(745, '4001', 'CRECIMIENTO', 'Melaza Lasso', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(746, '4001', 'ENGORDE', 'Melaza Lasso', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(747, '4001', 'FINALIZADOR', 'Melaza Lasso', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(748, '5266', 'PRE-INICIADOR', 'Melaza Lasso', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(749, '5266', 'INICIADOR', 'Melaza Lasso', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(750, '5266', 'INICIADOR', 'Melaza Lasso', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(751, '5266', 'DESTETE', 'Melaza Lasso', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(752, '5266', 'CRECIMIENTO', 'Melaza Lasso', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(753, '5266', 'ENGORDE', 'Melaza Lasso', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(754, '5266', 'FINALIZADOR', 'Melaza Lasso', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(755, '777', 'PRE-INICIADOR', 'Melaza Lasso', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(756, '777', 'INICIADOR', 'Melaza Lasso', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(757, '777', 'INICIADOR', 'Melaza Lasso', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(758, '777', 'DESTETE', 'Melaza Lasso', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(759, '777', 'CRECIMIENTO', 'Melaza Lasso', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(760, '777', 'ENGORDE', 'Melaza Lasso', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(761, '777', 'FINALIZADOR', 'Melaza Lasso', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(762, '8300', 'PRE-INICIADOR', 'Melaza Lasso', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(763, '8300', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(764, '8300', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(765, '8300', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(766, '8300', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(767, '8300', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(768, '8300', 'FINALIZADOR', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(769, '8985', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(770, '8985', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(771, '8985', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(772, '8985', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(773, '8985', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(774, '8985', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(775, '8985', 'FINALIZADOR', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(776, '9500', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(777, '9500', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(778, '9500', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(779, '9500', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(780, '9500', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(781, '9500', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(782, '9500', 'FINALIZADOR', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(783, '12345', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(784, '12345', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(785, '12345', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(786, '12345', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(787, '12345', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(788, '12345', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(789, '12345', 'Crecimiento', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(790, '15500', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(791, '15500', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(792, '15500', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(793, '15500', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(794, '15500', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(795, '15500', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(796, '15500', 'Crecimiento', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(797, '20000', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(798, '20000', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(799, '20000', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(800, '20000', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(801, '20000', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(802, '20000', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(803, '20000', 'Gestación (inicio)	', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(804, '21214', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(805, '21214', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(806, '21214', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(807, '21214', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(808, '21214', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(809, '21214', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(810, '21214', 'Gestación (final)	', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(811, '2222', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(812, '2222', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(813, '2222', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(814, '2222', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(815, '2222', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(816, '2222', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(817, '2222', 'Flushing', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(818, '23000', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(819, '23000', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(820, '23000', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(821, '23000', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(822, '23000', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(823, '23000', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(824, '23000', 'FINALIZADOR', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(825, '27500', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(826, '27500', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(827, '27500', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(828, '27500', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(829, '27500', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(830, '27500', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(831, '27500', 'FINALIZADOR', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(832, '33000', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(833, '33000', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(834, '33000', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(835, '33000', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(836, '33000', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(837, '33000', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(838, '33000', 'FINALIZADOR', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(839, '45000', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(840, '45000', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(841, '45000', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(842, '45000', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(843, '45000', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(844, '45000', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(845, '45000', 'FINALIZADOR', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(846, '5000', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(847, '5000', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(848, '5000', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(849, '5000', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(850, '5000', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(851, '5000', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(852, '5000', 'FINALIZADOR', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(853, '54321', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(854, '54321', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(855, '54321', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(856, '54321', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(857, '54321', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(858, '54321', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(859, '54321', 'FINALIZADOR', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(860, '599', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(861, '599', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(862, '599', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(863, '599', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(864, '599', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(865, '599', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(866, '599', 'FINALIZADOR', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22'),
(867, '8210', 'PRE-INICIADOR', 'Melaza La Espiga', 0.15, 0.70, '2024-01-01', '2024-01-22'),
(868, '8210', 'INICIADOR', 'Melaza La Espiga', 0.50, 0.70, '2024-01-23', '2024-02-07'),
(869, '8210', 'INICIADOR', 'Melaza La Espiga', 0.80, 0.70, '2024-02-08', '2024-02-15'),
(870, '8210', 'DESTETE', 'Melaza La Espiga', 1.00, 0.70, '2024-02-16', '2024-02-23'),
(871, '8210', 'CRECIMIENTO', 'Melaza La Espiga', 2.00, 0.70, '2024-02-24', '2024-05-24'),
(872, '8210', 'ENGORDE', 'Melaza La Espiga', 3.00, 0.70, '2024-05-25', '2024-11-21'),
(873, '8210', 'FINALIZADOR', 'Melaza La Espiga', 5.00, 0.70, '2024-11-22', '2025-11-22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oh_melaza`
--
ALTER TABLE `oh_melaza`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oh_melaza`
--
ALTER TABLE `oh_melaza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=874;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
