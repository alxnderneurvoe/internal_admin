-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 07:20 PM
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
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `address` varchar(1000) NOT NULL,
  `subtotal` int(50) NOT NULL,
  `tax` int(50) NOT NULL,
  `shipping` int(50) NOT NULL,
  `grand_total` int(50) NOT NULL,
  `amount` int(50) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `rek` tinyint(1) DEFAULT 1,
  `delivery_time` varchar(255) NOT NULL,
  `delivery_unit` varchar(255) NOT NULL,
  `client_nik` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `user_id`, `invoice_number`, `client_name`, `address`, `subtotal`, `tax`, `shipping`, `grand_total`, `amount`, `created_at`, `status`, `rek`, `delivery_time`, `delivery_unit`, `client_nik`) VALUES
(13, 1, '1/VII/SSS/SPH/2025', 'Muhammad Habibillah', 'Hagu Selatan', 6300000, 693000, 0, 6993000, 0, '', 'SPH', 1, '2', 'minggu', ''),
(14, 1, '14/VII/SSS/SPH/2025', 'Muhammad Habibillah', '1', 654000, 71940, 0, 725940, 0, '', 'SPH', 1, '2', 'bulan', ''),
(15, 1, '15/VII/SSS/INV/2025', 'Muhammad', 'a', 7194000, 791340, 0, 7985340, 0, '', 'INV', 0, '', '', ''),
(16, 1, '16/VII/SSS/INV/2025', 'Mulia', 'a', 327000, 35970, 0, 362970, 0, '', 'INV', 1, '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
