-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 03 Bulan Mei 2025 pada 21.46
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
-- Database: `ims`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `ims_product`
--

CREATE TABLE `ims_product` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ims_product`
--

INSERT INTO `ims_product` (`product_id`, `name`, `description`, `supplier_id`, `price`, `image`, `created_at`) VALUES
(1, 'Diesel Engine TS190', 'Mesin diesel 1-silinder cocok untuk pompa air dan traktor kecil.', 1, 5500000.00, '68163fef41132_ts190.jpeg', '2025-01-15 09:00:00'),
(2, 'Mini Tractor YM200', 'Traktor mini untuk lahan sempit dan sawah bertingkat.', 1, 32500000.00, '681641feae252_mini tractor ym200.jpeg', '2025-02-01 10:30:00'),
(3, 'Water Pump WP150', 'Pompa air berdaya tinggi cocok untuk irigasi sawah.', 1, 2750000.00, '681642b0cb449_waterpump.jpeg', '2025-02-10 08:15:00'),
(4, 'Power Tiller PT400', 'Alat bajak tanah berdaya tinggi untuk lahan luas.', 3, 18000000.00, '68163e29a5261_Power Tiller PT400.jpg', '2025-02-20 11:00:00'),
(5, 'Combine Harvester CH120', 'Mesin panen padi otomatis dengan efisiensi tinggi.', 2, 10000.00, '68163db7103fb_Combine Harvester CH120.jpeg', '2025-03-01 14:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ims_stock`
--

CREATE TABLE `ims_stock` (
  `stock_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ims_stock`
--

INSERT INTO `ims_stock` (`stock_id`, `product_id`, `quantity`, `warehouse_id`) VALUES
(1, 1, 100, 1),
(2, 2, 50, 5),
(3, 3, 80, 2),
(4, 4, 30, 3),
(5, 5, 10, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ims_supplier`
--

CREATE TABLE `ims_supplier` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ims_supplier`
--

INSERT INTO `ims_supplier` (`supplier_id`, `name`, `contact`, `address`) VALUES
(1, 'PT Yanmar Diesel Indonesia', '021-5551234', 'Jl. Raya Jakarta-Bogor Km. 29, Cibinong, Bogor'),
(2, 'PT Traktor Nusantara', '021-7894561', 'Jl. Raya Bekasi Km. 21, Cakung, Jakarta Timur'),
(3, 'PT Kubota Machinery Indonesia', '021-1234567', 'Jl. Raya Serang Km. 12, Tangerang'),
(4, 'PT Agrindo Mitra Sejati', '022-9876543', 'Jl. Soekarno Hatta No.88, Bandung'),
(5, 'PT Surya Traktor Mandiri', '031-6547891', 'Jl. Mayjen Sungkono No.9, Surabaya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ims_transaction`
--

CREATE TABLE `ims_transaction` (
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `type` enum('in','out') DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ims_transaction`
--

INSERT INTO `ims_transaction` (`transaction_id`, `product_id`, `type`, `quantity`, `transaction_date`, `warehouse_id`) VALUES
(1, 1, 'in', 100, '2025-03-01 08:00:00', 1),
(2, 2, 'in', 20, '2025-03-02 09:30:00', 1),
(3, 1, 'out', 30, '2025-03-03 14:00:00', 1),
(4, 3, 'in', 80, '2025-03-04 13:00:00', 2),
(5, 4, 'in', 30, '2025-03-05 15:00:00', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ims_user`
--

CREATE TABLE `ims_user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ims_user`
--

INSERT INTO `ims_user` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin1', '0192023a7bbd73250516f069df18b500', 'admin'),
(2, 'staff1', 'de9bf5643eabf80f4a56fda3bbb84483', 'staff');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ims_warehouse`
--

CREATE TABLE `ims_warehouse` (
  `warehouse_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `location` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ims_warehouse`
--

INSERT INTO `ims_warehouse` (`warehouse_id`, `name`, `location`) VALUES
(1, 'Gudang Pusat', 'Jl. Industri No. 10, Karawang'),
(2, 'Gudang Surabaya', 'Jl. Rungkut Industri No. 5, Surabaya'),
(3, 'Gudang Medan', 'Jl. Gatot Subroto No. 123, Medan'),
(4, 'Gudang Semarang', 'Jl. Kaligawe No. 21, Semarang'),
(5, 'Gudang Makassar', 'Jl. Urip Sumoharjo No. 45, Makassarkl');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `ims_product`
--
ALTER TABLE `ims_product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indeks untuk tabel `ims_stock`
--
ALTER TABLE `ims_stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indeks untuk tabel `ims_supplier`
--
ALTER TABLE `ims_supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indeks untuk tabel `ims_transaction`
--
ALTER TABLE `ims_transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indeks untuk tabel `ims_user`
--
ALTER TABLE `ims_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `ims_warehouse`
--
ALTER TABLE `ims_warehouse`
  ADD PRIMARY KEY (`warehouse_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `ims_user`
--
ALTER TABLE `ims_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
