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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(100) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `image_url` varchar(1000) NOT NULL,
  `tokopedia_link` varchar(255) NOT NULL,
  `shopee_link` varchar(255) NOT NULL,
  `inaproc_link` varchar(255) NOT NULL,
  `siplah_link` varchar(255) NOT NULL,
  `blibli_link` varchar(255) NOT NULL,
  `image` varchar(1000) NOT NULL,
  `variant_name` varchar(255) NOT NULL,
  `variant_price` int(255) NOT NULL,
  `spec` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `unit`, `category`, `image_url`, `tokopedia_link`, `shopee_link`, `inaproc_link`, `siplah_link`, `blibli_link`, `image`, `variant_name`, `variant_price`, `spec`) VALUES
(106, 'Mesin Pasang Kancing FY 917-V4', 29947500, 'Unit', 'Mesin Jahit', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-pasang-kancing-fy-917-v4', '', '', '', '', 0, ''),
(107, 'Mesin Jahit High Speed FY9908D', 18690375, 'Unit', 'Mesin Jahit', '../uploads/5395c50d-9fdf-11ef-b009-d2db4602563b.jpg', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-jahit-high-speed-fy9908d', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-jahit-high-speed-fy9908d', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-jahit-high-speed-fy9908d', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-jahit-high-speed-fy9908d', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-jahit-high-speed-fy9908d', '', '', 0, ''),
(108, 'MESIN OBRAS HIGH SPEED FY600-V4', 18603750, 'Unit', 'Mesin Jahit', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-obras-high-speed-fy600-v4', '', '', '', '', 0, ''),
(109, 'MESIN KELIM FYC6-02D', 24956250, 'Unit', 'Mesin Jahit', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-kelim-fyc6-02d', '', '', '', '', 0, ''),
(110, 'MESIN LUBANG KANCING FY824-V4', 54450000, 'Unit', 'Mesin Jahit', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-lubang-kancing-fy824-v4', '', '', '', '', 0, ''),
(111, 'MESIN JAHIT LOW SPEED FY-100', 8167500, 'Unit', 'Mesin Jahit', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-jahit-low-speed-fy-100', '', '', '', '', 0, ''),
(112, 'MESIN BORDIR KOMPUTER FY-18PRO', 24502500, 'Unit', 'Mesin Jahit', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-bordir-komputer-fy-18pro', '', '', '', '', 0, ''),
(113, 'MESIN BORDIR COMPUTER PUTARAN TINGGI FY-1201-V4', 245025000, 'Unit', 'Mesin Jahit', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-bordir-computer-putaran-tinggi-fy-1201-v4', '', '', '', '', 0, ''),
(114, 'Puzzle PAUD (Kayu)', 385000, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/puzzle-paud-kayu', '', '', '', '', 0, ''),
(115, 'Sorting Box Geometri', 189001, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/sorting-box-geometri', '', '', '', '', 0, ''),
(116, 'Alat Mainan Rumah Tangga PAUD', 378001, 'Unit', 'Alat Peraga Edukatif', '../uploads/5395c50d-9fdf-11ef-b009-d2db4602563b.jpg', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/alat-mainan-rumah-tangga-paud', '', '', '', '', 0, ''),
(117, 'Alat Main Meronce PAUD', 210001, 'Unit', 'Alat Peraga Edukatif', '../uploads/5395c50d-9fdf-11ef-b009-d2db4602563b.jpg', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/alat-main-meronce-paud', '', '', '', '', 0, ''),
(118, 'Mainan Pukul Palu PAUD', 156001, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mainan-pukul-palu-paud', '', '', '', '', 0, ''),
(119, 'Set Mainan Menjahit', 150001, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/set-mainan-menjahit', '', '', '', '', 0, ''),
(120, 'Balok Rongga PAUD Kayu Seri 90-110', 2070000, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/balok-rongga-paud-kayu-seri-90-110', '', '', '', '', 0, ''),
(121, 'Timbangan PAUD', 146001, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/timbangan-paud', '', '', '', '', 0, ''),
(122, 'Balok Unit Paud (Seri 100)', 1150001, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/balok-unit-paud-seri-100', '', '', '', '', 0, ''),
(123, 'Alat Mainan Pertukangan PAUD', 231001, 'Unit', 'Alat Peraga Edukatif', '../uploads/5395c50d-9fdf-11ef-b009-d2db4602563b.jpg', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/alat-mainan-pertukangan-paud', '', '', '', '', 0, ''),
(124, 'Papan Geometri', 130001, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/papan-geometri', '', '', '', '', 0, ''),
(125, 'Kaca Pembesar', 252001, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/kaca-pembesar', '', '', '', '', 0, ''),
(126, 'Alat Main Kedokteran', 327000, 'Unit', 'Alat Peraga Edukatif', '../uploads/5395c50d-9fdf-11ef-b009-d2db4602563b.jpg', 'https://katalog.inaproc.id/semesta-sistem-solusindo/alat-main-kedokteran', 'https://katalog.inaproc.id/semesta-sistem-solusindo/alat-main-kedokteran', 'https://katalog.inaproc.id/semesta-sistem-solusindo/alat-main-kedokteran', 'https://katalog.inaproc.id/semesta-sistem-solusindo/alat-main-kedokteran', 'https://katalog.inaproc.id/semesta-sistem-solusindo/alat-main-kedokteran', '', '', 0, 'TP-LINK ARCHER TX20U PLUS \"AX1800 HIGH GAIN DUAL BAND WI-FI 6 USB ADPTER SPEED : 1201MBPS AT 5GHZ + 574 MBPS AT 2.4 GHZ SPEC : 2X HIGH GAIN EXTERNAL ANTENNAS, USB 3.0'),
(127, 'Kostum Profesi PAUD (isi 10)', 3193000, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/kostum-profesi-paud-isi-10', '', '', '', '', 0, ''),
(128, 'Boneka Gender PAUD', 1159001, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/boneka-gender-paud', '', '', '', '', 0, ''),
(129, 'Bola PAUD Berbagai Ukuran', 320001, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/bola-paud-berbagai-ukuran', '', '', '', '', 0, ''),
(130, 'Replika huruf dan angka paud', 180001, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/replika-huruf-dan-angka-paud-kayu', '', '', '', '', 0, ''),
(131, 'ANGKA DIGITAL SATUAN', 157000, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/angka-digital-satuan', '', '', '', '', 0, ''),
(132, 'ALFABET BERDIRI', 484000, 'Unit', 'Alat Peraga Edukatif', '../uploads/5395c50d-9fdf-11ef-b009-d2db4602563b.jpg', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/alfabet-berdiri', '', '', '', '', 0, ''),
(133, 'ANGKA DIGITAL PULUHAN', 206999, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/angka-digital-puluhan', '', '', '', '', 0, ''),
(134, 'Jaring Laba-Laba', 5592000, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/jaring-laba-laba', '', '', '', '', 0, ''),
(135, 'Peta Asean', 100000, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/peta-asean', '', '', '', '', 0, ''),
(136, 'Terowongan Terobos', 1100000, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/terowongan-terobos', '', '', '', '', 0, ''),
(137, 'Ayunan 4 Anak', 6300000, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/ayunan-4-anak', '', '', '', '', 0, ''),
(138, 'Hammer Segitiga', 221000, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/hammer-segitiga', '', '', '', '', 0, ''),
(139, 'Tangga Silinder', 234000, 'Unit', 'Alat Peraga Edukatif', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/tangga-silinder', '', '', '', '', 0, ''),
(140, 'Meja Siswa Adjustable ', 1280001, 'Unit', 'Meja dan Kursi Siswa', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/meja-siswa-adjustable-type-ak-09', '', '', '', '', 0, ''),
(141, 'Kursi Siswa Adjustable', 822500, 'Unit', 'Meja dan Kursi Siswa', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/kursi-siswa-adjustable-type-e-09', '', '', '', '', 0, ''),
(142, 'Meja Siswa Tipe AK 01', 935001, 'Unit', 'Meja dan Kursi Siswa', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/meja-siswa-tipe-ak-01', '', '', '', '', 0, ''),
(143, 'Kursi Siswa - E01', 765001, 'Unit', 'Meja dan Kursi Siswa', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/kursi-siswa-e01', '', '', '', '', 0, ''),
(144, 'MEJA SISWA - ADITECH STR 65', 1350001, 'Unit', 'Meja dan Kursi Siswa', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/meja-siswa-aditech-str-65', '', '', '', '', 0, ''),
(145, 'MEJA SISWA - ADITECH STR 64', 1320001, 'Unit', 'Meja dan Kursi Siswa', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/meja-siswa-aditech-str-64', '', '', '', '', 0, ''),
(146, 'KURSI SERBAGUNA - KS 01', 1340001, 'Unit', 'Meja dan Kursi Guru', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/kursi-serbaguna-ks-01', '', '', '', '', 0, ''),
(147, 'PAPAN TULIS WB 10', 1540001, 'Unit', 'Furnitur Kantor/Sekolah', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/papan-tulis-wb-10', '', '', '', '', 0, ''),
(148, 'MEJA KERJA GURU CH 1201', 2360001, 'Unit', 'Meja dan Kursi Guru', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/meja-kerja-guru-ch-1201', '', '', '', '', 0, ''),
(149, 'KURSI SISWA - ADITECH K STR 39', 1080000, 'Unit', 'Meja dan Kursi Siswa', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/kursi-siswa-aditech-k-str-39', '', '', '', '', 0, ''),
(150, 'LEMARI BESI GW 15', 3950000, 'Unit', 'Furnitur Kantor/Sekolah', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/lemari-besi-gw-15', '', '', '', '', 0, ''),
(151, 'KURSI SERBAGUNA SS 01', 670000, 'Unit', 'Meja dan Kursi Guru', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/kursi-serbaguna-ss-01', '', '', '', '', 0, ''),
(152, 'MEJA GURU SETENGAH BIRO CH 1201', 1670001, 'Unit', 'Meja dan Kursi Guru', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/meja-guru-setengah-biro-ch-1201', '', '', '', '', 0, ''),
(153, 'KURSI GURU DDC 33', 2886000, 'Unit', 'Meja dan Kursi Guru', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/kursi-guru-ddc-33', '', '', '', '', 0, ''),
(154, 'KURSI KANTOR DXK 3 N', 1670001, 'Unit', 'Meja dan Kursi Guru', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/kursi-kantor-dxk-3-n', '', '', '', '', 0, ''),
(155, 'PJUTS All in One 60Watt dan Tiang Oktagonal 7 Meter', 28500000, 'Paket', 'Lampu Jalan/PJUTS', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/pjuts-all-in-one-60watt-dan-tiang-oktagonal-7-meter', '', '', '', '', 0, ''),
(156, 'PJUTS TWO IN ONE 60WATT DAN TIANG 7 METER', 29985001, 'Paket', 'Lampu Jalan/PJUTS', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/pjuts-two-in-one-60watt-dan-tiang-7-meter', '', '', '', '', 0, ''),
(157, 'PJUTS TWO IN ONE 60WATT DAN SOLAR PANEL 200WP SERTA TIANG 7 METER', 30250001, 'Paket', 'Lampu Jalan/PJUTS', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/pjuts-two-in-one-60watt-dan-solar-panel-200wp-serta-tiang-7-meter', '', '', '', '', 0, ''),
(158, 'PJUTS TWO IN ONE 60WATT DAN SOLAR PANEL 200WP LENGKAP DENGAN TIANG 7 METER', 30085000, 'Paket', 'Lampu Jalan/PJUTS', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/pjuts-two-in-one-60watt-dan-solar-panel-200wp-lengkap-dengan-tiang-7-meter', '', '', '', '', 0, ''),
(159, 'PAKET PJUTS 2 IN 1 100W DENGAN TIANG 7M', 21500001, 'Paket', 'Lampu Jalan/PJUTS', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/paket-pjuts-2-in-1-100w-dengan-tiang-7m', '', '', '', '', 0, ''),
(160, 'Mesin Perajang Tembakau', 18004200, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/perajang-tembakau', '', '', '', '', 0, ''),
(161, 'Mixer Pengaduk Kosentrat', 28860000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mixer-pengaduk-kosentrat', '', '', '', '', 0, ''),
(162, 'Mesin Pencetak Pelet Kubota', 55000000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-pencetak-pelet-kubota', '', '', '', '', 0, ''),
(163, 'MESIN POMPA AIR SENTRIFUGAL / HAT-HWP-30', 8555000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-pompa-air-sentrifugal-hat-hwp-30', '', '', '', '', 0, ''),
(164, 'Mesin Conveyor Pemilah/HAT - 006 CPS', 75480000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-conveyor-pemilah-hat-006-cps', '', '', '', '', 0, ''),
(165, 'MESIN CONVEYOR FEEDER/HAT - 038CF', 46620000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-conveyor-feeder-hat-038cf', '', '', '', '', 0, ''),
(166, 'MESIN CONVEYOR PEMILAH/HAT - CP1500', 109612500, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-conveyor-pemilah-hat-cp1500', '', '', '', '', 0, ''),
(167, 'MESIN Sangrai Kopi (Roaster) Kap. 3 Kg', 48000000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/sangrai-kopi-roaster-kap-3-kg', '', '', '', '', 0, ''),
(168, 'Mesin Perontok Multikomoditi (Power Thresher Multiguna)', 25000000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-perontok-multikomoditi-power-thresher-multiguna', '', '', '', '', 0, ''),
(169, 'Mesin Pengupas Kulit Ari Biji Kopi (Huller Kopi)', 26640000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-pengupas-kulit-ari-biji-kopi-huller-kopi', '', '', '', '', 0, ''),
(170, 'Mesin Pompa Air Irigasi 2 In', 7000000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-pompa-air-irigasi-2-in', '', '', '', '', 0, ''),
(171, 'Mesin Press Sampah Hidrolik 40PH', 108780000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-press-sampah-hidrolik-40ph', '', '', '', '', 0, ''),
(172, 'Mesin Penepung (Diskmil) FFC 45', 47550000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-penepung-diskmil-ffc-45', '', '', '', '', 0, ''),
(173, 'Mesin Pengolahan Pupuk Organik (APPO)', 57720000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-pengolahan-pupuk-organik-appo', '', '', '', '', 0, ''),
(174, 'Mesin Press Hidrolik Untuk Pakan Ternak', 55500000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-press-hidrolik-untuk-pakan-ternak', '', '', '', '', 0, ''),
(175, 'Mesin Penepung (Diskmil)', 46620000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-penepung-diskmil', '', '', '', '', 0, ''),
(176, 'Mesin Pencacah Pakan Ternak (Chopper)', 38760000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-pencacah-pakan-ternak-chopper', '', '', '', '', 0, ''),
(177, 'Mesin Pencacah Kompos (Rumput, Daun dan Rating)', 27000000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-pencacah-kompos-rumput-daun-dan-rating', '', '', '', '', 0, ''),
(178, 'Mesin Pencacah dan Press Silase', 85999999, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-pencacah-dan-press-silase', '', '', '', '', 0, ''),
(179, 'Mesin Pengupas Kulit Kopi Kering (Huller) - HK 200LC', 15000001, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-pengupas-kulit-kopi-kering-huller-hk-200lc', '', '', '', '', 0, ''),
(180, 'Mesin Pengupas Kulit Kopi Kering (Huller) - HK 200', 16999000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-pengupas-kulit-kopi-kering-huller-hk-200', '', '', '', '', 0, ''),
(181, 'Mesin Pengupas Kulit Kopi Basah (Pulper) - PK150LC', 15513687, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-pengupas-kulit-kopi-basah-pulper-pk150lc', '', '', '', '', 0, ''),
(182, 'Mesin Pengupas Kulit Kopi Basah (Pulper) - PK150', 14200000, 'Unit', 'Mesin Industri', '', '', '', 'https://katalog.inaproc.id/semesta-sistem-solusindo/mesin-pengupas-kulit-kopi-basah-pulper-pk150', '', '', '', '', 0, ''),
(188, 'aa', 386, 'Batang', 'Mesin Welding Pipe', '../uploads/logo poltek tik.jpg', 'https://www.tokopedia.com/tokosemestasistem/socket-fusion-welding-machine-top-table-sfp-160-1730991716979934384', 'https://katalog.inaproc.id/semesta-sistem-solusindo/9-buku-anti-korupsi-penguatan-jati-diri-bangsa-dan-karakter', 'https://www.tokopedia.com/tokosemestasistem/socket-fusion-welding-machine-top-table-sfp-160-1730991716979934384', 'https://www.tokopedia.com/tokosemestasistem/socket-fusion-welding-machine-top-table-sfp-160-1730991716979934384', 'https://katalog.inaproc.id/semesta-sistem-solusindo/lemari-besi-gw-15', '', '', 0, 'TP-LINK ARCHER TX20U PLUS \"AX1800 HIGH GAIN DUAL BAND WI-FI 6 USB ADPTER SPEED : 1201MBPS AT 5GHZ + 574 MBPS AT 2.4 GHZ SPEC : 2X HIGH GAIN EXTERNAL ANTENNAS, USB 3.0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
