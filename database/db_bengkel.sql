-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Jul 2023 pada 21.57
-- Versi server: 10.4.25-MariaDB
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_bengkel`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_adm` int(11) NOT NULL,
  `nama_adm` varchar(50) NOT NULL,
  `telp_adm` varchar(15) NOT NULL,
  `user_adm` varchar(50) NOT NULL,
  `pass_adm` varchar(100) NOT NULL,
  `foto_adm` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_adm`, `nama_adm`, `telp_adm`, `user_adm`, `pass_adm`, `foto_adm`) VALUES
(1, 'Administrator', '08962878534', 'admin', 'admin', 'user.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barangjasa`
--

CREATE TABLE `barangjasa` (
  `id_brg` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis` varchar(20) NOT NULL,
  `stok` varchar(10) NOT NULL,
  `harga` varchar(20) NOT NULL,
  `keterangan` text NOT NULL,
  `id_adm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `barangjasa`
--

INSERT INTO `barangjasa` (`id_brg`, `nama`, `jenis`, `stok`, `harga`, `keterangan`, `id_adm`) VALUES
(1, 'Oli Yamalube 800cc', 'barang', '35', '35000', 'Oli Yamalube 800cc', 1),
(4, 'Paket Service Ekonomis', 'jasa', '1', '30000', 'Ekonomis', 1),
(5, 'Suspensi Matic', 'barang', '18', '175000', 'Suspensi matic', 1),
(8, 'Paket Ganti Oli', 'jasa', '1', '60000', 'Ganti oli rutin', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kasir`
--

CREATE TABLE `kasir` (
  `id_kasir` int(11) NOT NULL,
  `nama_kasir` varchar(50) NOT NULL,
  `telp_kasir` varchar(20) NOT NULL,
  `user_kasir` varchar(50) NOT NULL,
  `pass_kasir` varchar(100) NOT NULL,
  `foto_kasir` text NOT NULL,
  `id_adm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kasir`
--

INSERT INTO `kasir` (`id_kasir`, `nama_kasir`, `telp_kasir`, `user_kasir`, `pass_kasir`, `foto_kasir`, `id_adm`) VALUES
(1, 'Diko Hafa', '0210181928', 'kasir', 'pass', '06062019071454r.jpg', 1),
(5, 'Sinta', '082277296298', 'kasir2', 'password', '06102023235215a.jpg', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `konsumen`
--

CREATE TABLE `konsumen` (
  `id_kon` int(11) NOT NULL,
  `nama_kon` varchar(50) NOT NULL,
  `telp_kon` varchar(20) NOT NULL,
  `alamat_kon` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `konsumen`
--

INSERT INTO `konsumen` (`id_kon`, `nama_kon`, `telp_kon`, `alamat_kon`) VALUES
(0, 'Umum', '0', '-'),
(3, 'Suhadi', '012391839', 'Bantul');

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_spl` int(11) NOT NULL,
  `nama_spl` varchar(50) NOT NULL,
  `telp_spl` varchar(20) NOT NULL,
  `alamat_spl` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id_spl`, `nama_spl`, `telp_spl`, `alamat_spl`) VALUES
(2, 'Citra Mulia', '08129828919', 'Sleman'),
(3, 'Supplier Oli', '012123910', 'Solo\r\n'),
(4, 'Evan ', '083545345', 'Magelang');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tmp_trx`
--

CREATE TABLE `tmp_trx` (
  `id_tmp` int(11) NOT NULL,
  `id_trx` varchar(20) NOT NULL,
  `id_brg` int(11) NOT NULL,
  `jml` int(11) NOT NULL,
  `id_kasir` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tmp_trx`
--

INSERT INTO `tmp_trx` (`id_tmp`, `id_trx`, `id_brg`, `jml`, `id_kasir`, `status`) VALUES
(2, '02062019094643', 4, 1, 1, 'Done'),
(4, '02062019094643', 1, 2, 1, 'Done'),
(7, '02062019120923', 4, 1, 1, 'Done'),
(8, '02062019121127', 4, 1, 1, 'Done'),
(10, '06062019094346', 1, 1, 1, 'Done'),
(11, '06062019100803', 4, 1, 1, 'Done'),
(14, '11062023104735', 1, 2, 1, 'Done'),
(15, '08072023012536', 1, 3, 1, 'Done'),
(16, '08072023012536', 4, 1, 1, 'Done'),
(17, '08072023012536', 5, 1, 1, 'Done'),
(18, '08072023023639', 4, 1, 1, 'Done'),
(19, '08072023033433', 8, 1, 1, 'Done'),
(20, '08072023033634', 8, 1, 1, 'Done'),
(21, '08072023033823', 8, 1, 1, 'Done'),
(24, '08072023040342', 5, 1, 1, 'Done'),
(25, '08072023042249', 1, 3, 1, 'Done'),
(26, '09072023154356', 1, 2, 1, 'Done'),
(27, '09072023154356', 4, 1, 1, 'Done'),
(28, '11072023003809', 1, 2, 1, 'Done'),
(29, '11072023003809', 8, 1, 1, 'Done');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trx`
--

CREATE TABLE `trx` (
  `id_trx` varchar(20) NOT NULL,
  `id_kon` int(11) NOT NULL,
  `tgl_trx` date NOT NULL,
  `total` varchar(20) NOT NULL,
  `id_kasir` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `trx`
--

INSERT INTO `trx` (`id_trx`, `id_kon`, `tgl_trx`, `total`, `id_kasir`) VALUES
('02062019094643', 0, '2023-06-02', '100000', 1),
('02062019120923', 3, '2019-06-02', '30000', 1),
('02062019121127', 0, '2019-06-02', '30000', 1),
('06062019094346', 0, '2019-06-06', '35000', 1),
('06062019100803', 3, '2019-06-06', '30000', 1),
('08072023012536', 0, '2023-07-08', '310000', 1),
('08072023023639', 0, '2023-07-08', '30000', 1),
('08072023033433', 0, '2023-07-08', '60000', 1),
('08072023033634', 0, '2023-07-08', '60000', 1),
('08072023033823', 0, '2023-07-08', '60000', 1),
('08072023040342', 3, '2023-07-08', '175000', 1),
('08072023042249', 3, '2023-07-08', '105000', 1),
('09072023154356', 0, '2023-07-09', '100000', 1),
('11062023104735', 0, '2023-06-11', '70000', 1),
('11072023003809', 0, '2023-07-11', '130000', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `trxbarang`
--

CREATE TABLE `trxbarang` (
  `id_trxbrg` varchar(20) NOT NULL,
  `tgl_trxbrg` date NOT NULL,
  `id_brg` int(11) NOT NULL,
  `id_spl` int(11) NOT NULL,
  `jml_brg` int(11) NOT NULL,
  `ket_trxbrg` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `trxbarang`
--

INSERT INTO `trxbarang` (`id_trxbrg`, `tgl_trxbrg`, `id_brg`, `id_spl`, `jml_brg`, `ket_trxbrg`) VALUES
('00365602062019', '2023-06-02', 1, 3, 10, 'Oli'),
('02062511072023', '2023-07-11', 1, 4, 35, 'Tambah 35 stok'),
('02064711072023', '2023-07-11', 5, 2, 15, 'Tambah stok 15'),
('04184902062019', '2019-06-02', 5, 2, 5, 'suspensi'),
('04190502062019', '2019-06-02', 1, 3, 5, 'masuk lagi');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_adm`);

--
-- Indeks untuk tabel `barangjasa`
--
ALTER TABLE `barangjasa`
  ADD PRIMARY KEY (`id_brg`);

--
-- Indeks untuk tabel `kasir`
--
ALTER TABLE `kasir`
  ADD PRIMARY KEY (`id_kasir`);

--
-- Indeks untuk tabel `konsumen`
--
ALTER TABLE `konsumen`
  ADD PRIMARY KEY (`id_kon`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_spl`);

--
-- Indeks untuk tabel `tmp_trx`
--
ALTER TABLE `tmp_trx`
  ADD PRIMARY KEY (`id_tmp`);

--
-- Indeks untuk tabel `trx`
--
ALTER TABLE `trx`
  ADD PRIMARY KEY (`id_trx`);

--
-- Indeks untuk tabel `trxbarang`
--
ALTER TABLE `trxbarang`
  ADD PRIMARY KEY (`id_trxbrg`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_adm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `barangjasa`
--
ALTER TABLE `barangjasa`
  MODIFY `id_brg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `kasir`
--
ALTER TABLE `kasir`
  MODIFY `id_kasir` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `konsumen`
--
ALTER TABLE `konsumen`
  MODIFY `id_kon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_spl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tmp_trx`
--
ALTER TABLE `tmp_trx`
  MODIFY `id_tmp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
