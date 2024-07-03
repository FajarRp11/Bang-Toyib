-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jul 2024 pada 10.04
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bang_toyib`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail`
--

CREATE TABLE `detail` (
  `ID_INV` varchar(100) NOT NULL,
  `ID_Item` varchar(100) NOT NULL,
  `Jumlah_Item` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail`
--

INSERT INTO `detail` (`ID_INV`, `ID_Item`, `Jumlah_Item`) VALUES
('1', 'ITM1', 1),
('1', 'ITM2', 1),
('1', 'ITM3', 1),
('1', 'ITM4', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `header`
--

CREATE TABLE `header` (
  `ID_INV` varchar(100) NOT NULL,
  `Tanggal` date NOT NULL,
  `ID_Pelanggan` varchar(100) DEFAULT NULL,
  `ID_Kasir` varchar(100) DEFAULT NULL,
  `Jumlah_Bayar` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `header`
--

INSERT INTO `header` (`ID_INV`, `Tanggal`, `ID_Pelanggan`, `ID_Kasir`, `Jumlah_Bayar`) VALUES
('1', '2024-05-20', 'PLGN1', 'KSR1', '48000');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kasir`
--

CREATE TABLE `kasir` (
  `ID_Kasir` varchar(100) NOT NULL,
  `Nama_Kasir` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kasir`
--

INSERT INTO `kasir` (`ID_Kasir`, `Nama_Kasir`) VALUES
('KSR1', 'Whister');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `ID_Item` varchar(100) NOT NULL,
  `Nama_Item` varchar(100) NOT NULL,
  `Harga_Satuan` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`ID_Item`, `Nama_Item`, `Harga_Satuan`) VALUES
('ITM1', 'PAKET 2', '25000'),
('ITM2', 'TAMBAH NASI', '2000'),
('ITM3', 'MIE NYEMEK', '16000'),
('ITM4', 'TEH ES', '5000');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `ID_Pelanggan` varchar(100) NOT NULL,
  `Nama_Pelanggan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`ID_Pelanggan`, `Nama_Pelanggan`) VALUES
('PLGN1', 'BAYU');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail`
--
ALTER TABLE `detail`
  ADD PRIMARY KEY (`ID_INV`,`ID_Item`),
  ADD KEY `ID_Item` (`ID_Item`);

--
-- Indeks untuk tabel `header`
--
ALTER TABLE `header`
  ADD PRIMARY KEY (`ID_INV`),
  ADD KEY `ID_Pelanggan` (`ID_Pelanggan`),
  ADD KEY `ID_Kasir` (`ID_Kasir`);

--
-- Indeks untuk tabel `kasir`
--
ALTER TABLE `kasir`
  ADD PRIMARY KEY (`ID_Kasir`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`ID_Item`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`ID_Pelanggan`);

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail`
--
ALTER TABLE `detail`
  ADD CONSTRAINT `detail_ibfk_1` FOREIGN KEY (`ID_INV`) REFERENCES `header` (`ID_INV`),
  ADD CONSTRAINT `detail_ibfk_2` FOREIGN KEY (`ID_Item`) REFERENCES `menu` (`ID_Item`);

--
-- Ketidakleluasaan untuk tabel `header`
--
ALTER TABLE `header`
  ADD CONSTRAINT `header_ibfk_1` FOREIGN KEY (`ID_Pelanggan`) REFERENCES `pelanggan` (`ID_Pelanggan`),
  ADD CONSTRAINT `header_ibfk_2` FOREIGN KEY (`ID_Kasir`) REFERENCES `kasir` (`ID_Kasir`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
