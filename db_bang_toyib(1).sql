-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2024 at 03:46 PM
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
-- Database: `db_bang_toyib`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail`
--

CREATE TABLE `detail` (
  `ID_INV` varchar(100) NOT NULL,
  `ID_Item` varchar(100) NOT NULL,
  `Jumlah_Item` int(11) NOT NULL,
  `Harga_Satuan` decimal(10,2) NOT NULL,
  `Sub_Total` decimal(10,2) GENERATED ALWAYS AS (`Jumlah_Item` * `Harga_Satuan`) STORED,
  `Pajak_10_Persen` decimal(10,2) GENERATED ALWAYS AS (`Sub_Total` * 0.10) STORED,
  `Total_Bill` decimal(10,2) GENERATED ALWAYS AS (`Sub_Total` + `Pajak_10_Persen`) STORED,
  `Uang_Dibayar` decimal(10,2) NOT NULL,
  `Uang_Kembalian` decimal(10,2) GENERATED ALWAYS AS (`Uang_Dibayar` - `Total_Bill`) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail`
--

INSERT INTO `detail` (`ID_INV`, `ID_Item`, `Jumlah_Item`, `Harga_Satuan`, `Uang_Dibayar`) VALUES
('1', 'ITM1', 1, 25000.00, 27500.00),
('1', 'ITM2', 1, 2000.00, 2200.00),
('1', 'ITM3', 1, 16000.00, 17600.00),
('1', 'ITM4', 1, 5000.00, 5500.00);

-- --------------------------------------------------------

--
-- Table structure for table `header`
--

CREATE TABLE `header` (
  `ID_INV` varchar(100) NOT NULL,
  `Tanggal` date NOT NULL,
  `ID_Pelanggan` varchar(100) DEFAULT NULL,
  `ID_Kasir` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `header`
--

INSERT INTO `header` (`ID_INV`, `Tanggal`, `ID_Pelanggan`, `ID_Kasir`) VALUES
('1', '2024-05-20', 'PLGN1', 'KSR1');

-- --------------------------------------------------------

--
-- Table structure for table `kasir`
--

CREATE TABLE `kasir` (
  `ID_Kasir` varchar(100) NOT NULL,
  `Nama_Kasir` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kasir`
--

INSERT INTO `kasir` (`ID_Kasir`, `Nama_Kasir`) VALUES
('KSR1', 'Whister');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `ID_Pelanggan` varchar(100) NOT NULL,
  `Nama_Pelanggan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`ID_Pelanggan`, `Nama_Pelanggan`) VALUES
('PLGN1', 'BAYU');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `ID_Item` varchar(100) NOT NULL,
  `Nama_Item` varchar(100) NOT NULL,
  `Harga_Satuan` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`ID_Item`, `Nama_Item`, `Harga_Satuan`) VALUES
('ITM1', 'PAKET 2', 25000.00),
('ITM2', 'TAMBAH NASI', 2000.00),
('ITM3', 'MIE NYEMEK', 16000.00),
('ITM4', 'TEH ES', 5000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail`
--
ALTER TABLE `detail`
  ADD PRIMARY KEY (`ID_INV`,`ID_Item`),
  ADD KEY `ID_Item` (`ID_Item`);

--
-- Indexes for table `header`
--
ALTER TABLE `header`
  ADD PRIMARY KEY (`ID_INV`),
  ADD KEY `ID_Pelanggan` (`ID_Pelanggan`),
  ADD KEY `ID_Kasir` (`ID_Kasir`);

--
-- Indexes for table `kasir`
--
ALTER TABLE `kasir`
  ADD PRIMARY KEY (`ID_Kasir`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`ID_Pelanggan`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`ID_Item`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail`
--
ALTER TABLE `detail`
  ADD CONSTRAINT `detail_ibfk_1` FOREIGN KEY (`ID_INV`) REFERENCES `header` (`ID_INV`),
  ADD CONSTRAINT `detail_ibfk_2` FOREIGN KEY (`ID_Item`) REFERENCES `service` (`ID_Item`);

--
-- Constraints for table `header`
--
ALTER TABLE `header`
  ADD CONSTRAINT `header_ibfk_1` FOREIGN KEY (`ID_Pelanggan`) REFERENCES `pelanggan` (`ID_Pelanggan`),
  ADD CONSTRAINT `header_ibfk_2` FOREIGN KEY (`ID_Kasir`) REFERENCES `kasir` (`ID_Kasir`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
