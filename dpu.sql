-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Jun 2025 pada 22.48
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dpu`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `properti`
--

CREATE TABLE `properti` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `harga` varchar(11) DEFAULT NULL,
  `harga_setelah` double DEFAULT NULL,
  `harga_sebelum` double DEFAULT NULL,
  `pajak` double DEFAULT NULL,
  `iuran` double DEFAULT NULL,
  `jenis` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `label` varchar(100) DEFAULT NULL,
  `foto_properti` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `negara` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(20) DEFAULT NULL,
  `ukuran_dalam` double DEFAULT NULL,
  `ukuran_tanah` double DEFAULT NULL,
  `jumlah_ruangan` int(11) DEFAULT NULL,
  `jumlah_kamar` int(11) DEFAULT NULL,
  `jumlah_km` int(11) DEFAULT NULL,
  `id_khusus` varchar(100) DEFAULT NULL,
  `garasi` varchar(11) DEFAULT NULL,
  `tahun_bangun` varchar(50) DEFAULT NULL,
  `tersedia_mulai` date DEFAULT NULL,
  `basement` varchar(100) DEFAULT NULL,
  `tambahan` text DEFAULT NULL,
  `atap` varchar(100) DEFAULT NULL,
  `eksterior_tambahan` text DEFAULT NULL,
  `jenis_struktur` varchar(100) DEFAULT NULL,
  `jumlah_lantai` int(11) DEFAULT NULL,
  `interior` text DEFAULT NULL,
  `eksterior` text DEFAULT NULL,
  `utilitas` text DEFAULT NULL,
  `fitur_lainnya` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `properti`
--

INSERT INTO `properti` (`id`, `nama`, `deskripsi`, `harga`, `harga_setelah`, `harga_sebelum`, `pajak`, `iuran`, `jenis`, `status`, `label`, `foto_properti`, `alamat`, `negara`, `provinsi`, `kota`, `kecamatan`, `kode_pos`, `ukuran_dalam`, `ukuran_tanah`, `jumlah_ruangan`, `jumlah_kamar`, `jumlah_km`, `id_khusus`, `garasi`, `tahun_bangun`, `tersedia_mulai`, `basement`, `tambahan`, `atap`, `eksterior_tambahan`, `jenis_struktur`, `jumlah_lantai`, `interior`, `eksterior`, `utilitas`, `fitur_lainnya`) VALUES
(6, 'Pandan Wangi  Apart', '', '300000', 0, 0, 0, 0, 'Apartemen', 'Sewa', 'Aktif', 'uploads/6850637c148de_AVA GYJ.png', 'Jl. AWaaa', 'asdas', 'Kalimantan Timur', 'sdas', 'dasd', 'asdas', 0, 0, 0, 0, 0, NULL, 'dasda', '3123', '1111-11-12', 'sdas', 'asdas', 'sdad', 'Array', 'Kayu', 4, 'Gym, Ruang Media', 'Halaman Belakang, Lapangan Basket, Garasi Terhubung, Bak Mandi Air Hangat', 'Pendingin Sentral, Ventilasi', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `Username`, `Password`) VALUES
(1, 'admin', 'admin1212');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `properti`
--
ALTER TABLE `properti`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `properti`
--
ALTER TABLE `properti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
