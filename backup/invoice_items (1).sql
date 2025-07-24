-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 07:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `internal_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `invoice_id` varchar(255) NOT NULL,
  `id_product` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(255) NOT NULL,
  `discount` int(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `net` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`invoice_id`, `id_product`, `name`, `qty`, `price`, `discount`, `unit`, `net`) VALUES
('5', 0, 'Alat Mainan Pertukangan PAUD', 12, 231001, 0, 'piece', 2772012),
('5', 0, 'ALFABET BERDIRI', 12, 484000, 0, 'piece', 5808000),
('6', 0, 'Ayunan 4 Anak', 12, 6300000, 0, 'piece', 75600000),
('7', 0, 'Mesin Perajang Tembakau', 1, 18004200, 0, 'piece', 18004200),
('7', 0, 'Mesin Pengupas Kulit Kopi Basah (Pulper) - PK150', 10, 14200000, 0, 'piece', 142000000),
('8', 0, 'Papan Geometri', 12, 130001, 0, 'piece', 1560012),
('9', 0, 'Alat Main Meronce PAUD', 2, 210001, 0, 'piece', 420002),
('10', 0, 'Alat Main Meronce PAUD', 1, 2100000, 0, 'piece', 2100000),
('11', 0, 'Alat Main Kedokteran', 1, 327000, 0, 'piece', 327000),
('12', 0, 'Alat Main Meronce PAUD', 1, 210001, 0, 'piece', 210001),
('15', 0, 'Alat Main Meronce PAUD', 1, 210001, 0, 'piece', 210001),
('16', 0, 'ALFABET BERDIRI', 2, 484000, 0, 'piece', 968000),
('17', 0, 'Alat Mainan Pertukangan PAUD', 1, 231001, 0, 'piece', 231001),
('18', 0, 'Alat Main Kedokteran', 1, 327000, 0, 'piece', 327000),
('19', 0, 'ANGKA DIGITAL PULUHAN', 1, 206999, 0, 'piece', 206999),
('20', 0, 'Alat Main Meronce PAUD', 0, 210001, 0, 'piece', 0),
('21', 0, 'Alat Main Meronce PAUD', 2, 210001, 0, 'piece', 420002),
('22', 0, 'Alat Mainan Pertukangan PAUD', 1, 231001, 0, 'piece', 231001),
('23', 0, 'ALFABET BERDIRI', 2, 484000, 0, 'piece', 968000),
('25', 0, 'Alat Mainan Pertukangan PAUD', 12, 231001, 0, 'piece', 2772012),
('26', 0, 'ALFABET BERDIRI', 1, 484000, 0, 'piece', 484000),
('28', 0, 'Hammer Segitiga', 1, 221000, 0, 'piece', 221000),
('1', 0, 'ALFABET BERDIRI', 1, 484000, 0, 'piece', 484000),
('2', 0, 'Alat Mainan Rumah Tangga PAUD', 2, 378001, 0, 'piece', 756002),
('3', 0, 'Alat Mainan Pertukangan PAUD', 1, 231001, 0, 'piece', 231001),
('4', 0, 'Alat Main Kedokteran', 11, 327000, 0, 'piece', 3597000),
('5', 0, 'Alat Main Meronce PAUD', 1, 210001, 0, 'piece', 210001),
('6', 0, '', 1, 1, 0, 'piece', 1),
('6', 0, 'Alat Main Kedokteran', 1, 327000, 0, 'piece', 327000),
('7', 0, 'Alat Main Kedokteran', 1, 327000, 0, 'piece', 327000),
('8', 0, 'Alat Main Kedokteran', 1, 327000, 0, 'piece', 327000),
('9', 0, 'Alat Main Meronce PAUD', 1, 210001, 0, 'piece', 210001),
('10', 0, 'Kaca Pembesar', 1, 252001, 0, 'piece', 252001),
('11', 125, 'Kaca Pembesar', 1, 252001, 0, 'piece', 252001),
('12', 126, 'Alat Main Kedokteran', 1, 327000, 0, 'piece', 327000),
('15', 0, 'Alat Main Kedokteran', 11, 327000, 0, 'piece', 3597000),
('15', 0, 'Alat Main Kedokteran', 11, 327000, 0, 'piece', 3597000),
('16', 0, 'Alat Main Kedokteran', 1, 327000, 0, 'piece', 327000),
('14', 0, 'Alat Main Kedokteran', 1, 327000, 0, 'piece', 327000),
('14', 0, 'Alat Main Kedokteran', 1, 327000, 0, 'piece', 327000),
('13', 137, 'Ayunan 4 Anak', 1, 6300000, 0, 'piece', 6300000);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
