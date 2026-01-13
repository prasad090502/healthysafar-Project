-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2026 at 08:49 AM
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
-- Database: `hsafar_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `reset_token` varchar(191) DEFAULT NULL,
  `reset_expires_at` datetime DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'customer',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `username`, `email`, `contact`, `password_hash`, `reset_token`, `reset_expires_at`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Ishan Bhokarikar', 'ishanbhokarikar', 'ishanbhokarikar@gmail.com', '9421223903', '$2y$12$S.wjip27/LZKF/di93c9e.KLo6vIjMUJmIcrrqXStwSXJzHeKHCVC', NULL, NULL, 'customer', '2025-11-18 02:51:58', NULL),
(2, 'Wirelesscty', 'Wirelesscty', 'jannbroyles@msn.com', 'jannbroyles@msn.com', '$2y$10$iG0ZxPVv6J9V5F34xQHseO7.1cJc26b0dfMB7jz0PUnWfFwP1emXy', NULL, NULL, 'customer', '2025-11-20 04:42:26', NULL),
(3, 'rxdotjpzko', 'egmjousmui', 'nyhlkgzx@testform.xyz', 'dzzfktwwgu', '$2y$10$tYYlcyHep1HjXO0bX.za8eKRpb0wv.yRbc3ICd9UNCCnpMyiRucsG', NULL, NULL, 'customer', '2025-11-21 14:22:56', NULL),
(4, 'KennethFlert', 'KennethFlert', 'patricklowe9oizp@gmail.com', 'patricklowe9oizp@gma', '$2y$10$VcDPL6HZ6N5uAsOGPe8d7.WmbU7wr4NyAWG5ecX.iiYR8.2/C2OdK', NULL, NULL, 'customer', '2025-11-22 01:32:06', NULL),
(5, 'cyncapenly', 'cyncapenly', 'Dex@prohabits.es', 'Dex@prohabits.es', '$2y$10$axdN9vnBO//3NSNPjPGv2eCQ1a/RPGG.ac5Y9jYFLY56vXJvu2zXW', NULL, NULL, 'customer', '2025-11-22 21:57:22', NULL),
(6, 'RainMachineqru', 'RainMachineqru', 'aiequipmentne@gmail.com', 'aiequipmentne@gmail.', '$2y$10$aJe2wVcM3VaGku5aHpIBDu7ravqa8m62ZGp2TxLaUn3HG8I6Mxnwm', NULL, NULL, 'customer', '2025-11-23 20:19:22', NULL),
(7, 'pereezdstavy', 'pereezdstavy', 'whomorib@mail.ru', 'whomorib@mail.ru', '$2y$10$/Bee/a/ayqos39yU2AKdZefNhaUS/8ds0rEJ6rn/JVoMXxSo3f9ZO', NULL, NULL, 'customer', '2025-11-25 11:20:33', NULL),
(8, 'LavillCag', 'LavillCag', 'revers711@1ti.ru', 'revers711@1ti.ru', '$2y$10$6ttiYj3VTq1vdalhrvzz4eW/3tJp59Moz49xFWindSgKrGIaP8OCu', NULL, NULL, 'customer', '2025-11-26 05:23:12', NULL),
(9, 'Artisanahn', 'Artisanahn', 'jessedparkerjr@gmail.com', 'jessedparkerjr@gmail', '$2y$10$yPTE.IKUbtJc1oBlRtgUoOb49Z90J2K0HHpMCSVP/J6VJKhWE52wK', NULL, NULL, 'customer', '2025-11-26 12:04:05', NULL),
(10, 'Drywallczc', 'Drywallczc', 'ryanarcher@shaw.ca', 'ryanarcher@shaw.ca', '$2y$10$EK78wOGgBW8h/Tv2CS.4zOMAh/f39Ty7JvWRAJjmsr8WuXe.tN1q2', NULL, NULL, 'customer', '2025-12-01 01:43:39', NULL),
(11, 'Yamahadwr', 'Yamahadwr', 'emilylynn71@gmail.com', 'emilylynn71@gmail.co', '$2y$10$/gBt0eikKz1Sn7Tm7qbzou1cVOmAd8qmpX9.17A145ZITGYCgYDfe', NULL, NULL, 'customer', '2025-12-01 03:52:43', NULL),
(12, 'Prabhu yuvraj', 'prabhu', 'prabhu.ingle05@gmail.com', '9575595655', '$2y$10$CJLIS7Xn2Y7vgHe2WEN6/u1B6zG.TkanfVNPUm8w6pNcRfNQEoVPS', NULL, NULL, 'customer', '2025-12-02 15:09:30', NULL),
(13, 'Sagar sitaram gaikwad', 'Sagar gaikwad', 'sg1743804@gmail.com', '9021593304', '$2y$10$qlX1Yg8Hb6rv6mNvBXnu7uLs2AB4g9GEV/A2Q5reSXtsAaGEEwHRC', NULL, NULL, 'customer', '2025-12-04 14:05:24', NULL),
(14, 'JamesAgept', 'JamesAgept', 'decemelacox06@gmail.com', 'decemelacox06@gmail.', '$2y$10$6qKUMi3bDD5Ax343Ih6HI.pZbd9DFol2afBo4fDu1fvfMQzucuJEa', NULL, NULL, 'customer', '2025-12-09 07:13:01', NULL),
(15, 'Generationiuj', 'Generationiuj', 'gordysgt@gmail.com', 'gordysgt@gmail.com', '$2y$10$AYkAdWXKDzCbA7SMJoPVzeM6pFpcCb2i3JCRvQaPQVRBrrRwVXGnm', NULL, NULL, 'customer', '2025-12-11 12:41:45', NULL),
(16, 'Flexibletrc', 'Flexibletrc', 'rickjj4141@gmail.com', 'rickjj4141@gmail.com', '$2y$10$6bro5erI8o7R5AJL2fmIVuORvZxyncn52tfDl4h78LyTqEQbwEB9S', NULL, NULL, 'customer', '2025-12-12 02:12:53', NULL),
(17, 'mizfoeve', 'mizfoeve', 'dealbcounthel@gismail.online', 'dealbcounthel@gismai', '$2y$10$FENvzp1jAW5RoieSoyjLEev9IbbJH9cRQ/77B9RvTXb5onjjQZkg2', NULL, NULL, 'customer', '2025-12-12 18:28:35', NULL),
(18, 'Infraredflj', 'Infraredflj', 'ali940055@gmail.com', 'ali940055@gmail.com', '$2y$10$QLAqR2iIZDFTscY3QytUSuacnQ2OhPcQifeoMXsMgcZ49XzttGq3i', NULL, NULL, 'customer', '2025-12-14 02:13:11', NULL),
(19, 'Zodiacpvd', 'Zodiacpvd', 'drbutterfly2005@gmail.com', 'drbutterfly2005@gmai', '$2y$10$jXBpdBFmtctNXdRmjBP9OuTN0Zc80ed9M8raYo/.zJXTwKcU/SzuO', NULL, NULL, 'customer', '2025-12-14 22:37:22', NULL),
(20, 'Broncozqr', 'Broncozqr', 'mccabe11@yahoo.com', 'mccabe11@yahoo.com', '$2y$10$oOrK9WZhrbkWvNftfOWxw.McqiSTzVXCllIPybFU0hwBGK0snm3vi', NULL, NULL, 'customer', '2025-12-19 19:32:48', NULL),
(21, 'Beaconvkc', 'Beaconvkc', 'pvasi209@gmail.com', 'pvasi209@gmail.com', '$2y$10$lfUNFRq2anxYc9EtLgu7V.llYlDs646w3ff5ehm6ga/3Ow7mkJzJa', NULL, NULL, 'customer', '2025-12-23 16:52:48', NULL),
(22, 'Candyzmn', 'Candyzmn', 'frank_vannier@yahoo.fr', 'frank_vannier@yahoo.', '$2y$10$L9siPGYS1FQ1./Fnv5/2Bu9x.ySrOI5oHiE528dmGy9UPKEs7b7q2', NULL, NULL, 'customer', '2025-12-23 22:04:04', NULL),
(23, 'Avalanchencs', 'Avalanchencs', 'akinamontanez@gmail.com', 'akinamontanez@gmail.', '$2y$10$yiaDxieYo2dydhgfIa2eP.XUEQzqI3iVapEt9rYCqV/CmrKsFL54y', NULL, NULL, 'customer', '2025-12-24 00:34:38', NULL),
(24, 'Sunburstyph', 'Sunburstyph', 'kyle@fullsend.com', 'kyle@fullsend.com', '$2y$10$fiXOcy0YBWF/kf6Ng8EHMux4TOAf4jUv.N5CFNii5W1IBcVmykldy', NULL, NULL, 'customer', '2025-12-25 14:03:18', NULL),
(25, 'Waynecoolo', 'Waynecoolo', 'lindasweett74@gmail.com', 'lindasweett74@gmail.', '$2y$10$oeiyib.8P049LgaXGHeEyurB7xibBPntMR0rySC15yJYcKvzqCZEW', NULL, NULL, 'customer', '2025-12-28 15:56:08', NULL),
(26, 'Beateruof', 'Beateruof', 'adreiling@7brewmke.com', 'adreiling@7brewmke.c', '$2y$10$F2kVGfQoadRJ10CU33s1sO1v.9kc1sjQ66PhDMp1Cm/ZGeDKRV0ly', NULL, NULL, 'customer', '2025-12-29 22:44:31', NULL),
(27, 'Keypadanhq', 'Keypadanhq', 'dahlquisthvacandplumbing@gmail.com', 'dahlquisthvacandplum', '$2y$10$/oE7bSix5Z9r2Rs774kQruu082A/vvjMOafQGaEGiVvSfONbro3GS', NULL, NULL, 'customer', '2026-01-01 21:24:44', NULL),
(28, 'Bluetoothtzg', 'Bluetoothtzg', 'vishnuchakradhar7@gmail.com', 'vishnuchakradhar7@gm', '$2y$10$euZvPDZZNFMVjRnM/Qz/U.YQaThgs7xj6Z58QAIlkEyWkPnROqaQK', NULL, NULL, 'customer', '2026-01-02 15:22:05', NULL),
(29, 'Fortressxqy', 'Fortressxqy', 'shumakerm314@icloud.com', 'shumakerm314@icloud.', '$2y$10$D/itAP27JYd6MxV/iUeOB.vGUUqRg4Lu/xMw5YA7Qf/CRKNgSZwCq', NULL, NULL, 'customer', '2026-01-03 07:07:41', NULL),
(30, 'Serieskqg', 'Serieskqg', 'decks@thedeckstore.com', 'decks@thedeckstore.c', '$2y$10$MInp8zNwLUWca0ZVSwTSuOAPj7C2uVHJp3rQfnPkyWTmW5pEV3PW6', NULL, NULL, 'customer', '2026-01-04 13:45:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_addresses`
--

CREATE TABLE `customer_addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `alternate_phone` varchar(20) DEFAULT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `country` varchar(100) NOT NULL DEFAULT 'India',
  `address_type` enum('home','office','other') NOT NULL DEFAULT 'home',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_addresses`
--

INSERT INTO `customer_addresses` (`id`, `customer_id`, `name`, `phone`, `alternate_phone`, `address_line1`, `address_line2`, `landmark`, `city`, `state`, `pincode`, `country`, `address_type`, `is_default`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ishan Bhokarikar', '9421223903', '', 'C-1/6 Kasliwal Park', 'N-2 CIDCO', '', 'Chhatrapati Sambhajinagar', 'Maharashtra', '431006', 'India', 'home', 0, NULL, NULL, '2025-11-18 03:50:14', '2025-12-04 11:37:57'),
(2, 1, 'Ishan', '9421223903', '', 'dasd', '', '', 'asda', 'adad', '12312312', 'India', 'home', 0, NULL, NULL, '2025-11-18 04:05:45', NULL),
(3, 12, 'Prabhu yuvraj', '09853298534', '09853298534', '18, pratapgadh, tirupati executive society, ulkanagari', '', '', 'Aurangabad', 'Maharashtra', '431005', 'India', 'home', 1, NULL, NULL, '2025-12-02 15:10:44', NULL),
(7, 1, 'Ishan Bhokarikar', '9421223903', '', 'N-2 Ci Test', '', '', 'Cs', 'Mah', '431001', 'India', 'home', 0, NULL, NULL, '2025-12-04 11:44:31', '2025-12-04 11:46:04'),
(8, 1, 'Ishan', '123', '', 'sad', '', '', 'wdq₹', 'dsaa', '₹', 'India', 'home', 0, NULL, NULL, '2025-12-04 11:46:04', '2025-12-04 11:52:37'),
(9, 1, 'Ishan', '9421223903', '', 'asdad', '', '', 'Csnge', 'mah', '431001', 'India', 'home', 1, NULL, NULL, '2025-12-04 11:52:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `long_description` text DEFAULT NULL,
  `weekday` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `menu_name`, `short_description`, `long_description`, `weekday`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Noodles', 'It non sticky and spicy', 'Our delicious Noodles are freshly prepared using high-quality ingredients and perfectly cooked to deliver a satisfying and wholesome meal. Tossed with seasonal vegetables and flavorful spices, this dish offers a perfect balance of taste and nutrition. Light yet filling, these noodles are ideal for lunch or dinner and are crafted to suit everyday healthy eating.', 'Monday', 1, '2026-01-09 07:01:43', '2026-01-09 10:35:14'),
(2, 'Onion Pizza', 'A classic pizza topped with fresh onions, melted cheese, and a perfectly baked crispy base.', 'Our Onion Pizza is made with a crispy baked base, rich tomato sauce, and generous layers of melted cheese, topped with freshly sliced onions. Simple yet flavorful, this pizza delivers a perfect balance of crunch, softness, and savory taste, making it a timeless favorite for all pizza lovers.', 'Tuesday', 1, '2026-01-09 07:06:57', '2026-01-09 10:43:34'),
(3, 'pohe', NULL, NULL, 'Monday', 1, '2026-01-09 09:49:27', '2026-01-09 09:49:27'),
(4, 'Pizza', NULL, NULL, 'Thursday', 1, '2026-01-09 09:50:07', '2026-01-09 09:50:07'),
(5, 'Momos', NULL, NULL, 'Saturday', 1, '2026-01-09 10:13:23', '2026-01-09 10:13:23');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `razorpay_order_id` varchar(191) DEFAULT NULL,
  `razorpay_payment_id` varchar(191) DEFAULT NULL,
  `razorpay_signature` varchar(255) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) NOT NULL DEFAULT 'INR',
  `shipping_address_id` int(10) UNSIGNED DEFAULT NULL,
  `billing_address_id` int(10) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `order_number`, `status`, `payment_status`, `payment_method`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`, `subtotal`, `tax_amount`, `shipping_amount`, `discount_amount`, `grand_total`, `currency`, `shipping_address_id`, `billing_address_id`, `notes`, `created_at`, `updated_at`) VALUES
(7, 1, 'HS202511262104471', 'pending', 'pending', 'cod', NULL, NULL, NULL, 320.00, 0.00, 0.00, 0.00, 320.00, 'INR', 1, 1, '', '2025-11-26 21:04:47', NULL),
(8, 1, 'HS202512021507451', 'pending', 'paid', 'cod', NULL, NULL, NULL, 180.00, 0.00, 0.00, 0.00, 180.00, 'INR', 1, 1, '', '2025-12-02 15:07:45', '2025-12-02 15:08:13'),
(9, 1, 'HS202512021520531', 'pending', 'pending', 'online', NULL, NULL, NULL, 120.00, 0.00, 0.00, 0.00, 120.00, 'INR', 1, 1, '', '2025-12-02 15:20:53', NULL),
(10, 1, 'HS202512021525511', 'pending', 'pending', 'online', NULL, NULL, NULL, 120.00, 0.00, 0.00, 0.00, 120.00, 'INR', 1, 1, '', '2025-12-02 15:25:51', NULL),
(11, 1, 'HS202512021533341', 'pending', 'pending', 'cod', NULL, NULL, NULL, 120.00, 0.00, 0.00, 0.00, 120.00, 'INR', 1, 1, '', '2025-12-02 15:33:34', NULL),
(12, 1, 'HS202512022150311', 'pending', 'pending', 'online', NULL, NULL, NULL, 110.00, 0.00, 0.00, 0.00, 110.00, 'INR', 1, 1, '', '2025-12-02 21:50:31', NULL),
(13, 1, 'HS202512022355291', 'pending', 'pending', 'online', 'order_Rmvph0cUht448d', NULL, NULL, 1.00, 0.00, 0.00, 0.00, 1.00, 'INR', 1, 1, '', '2025-12-02 23:55:29', '2025-12-02 23:55:30'),
(14, 1, 'HS202512041046531', 'pending', 'pending', 'online', NULL, NULL, NULL, 110.00, 0.00, 0.00, 0.00, 110.00, 'INR', 1, 1, '', '2025-12-04 10:46:53', NULL),
(15, 1, 'HS202512041046571', 'pending', 'pending', 'online', NULL, NULL, NULL, 110.00, 0.00, 0.00, 0.00, 110.00, 'INR', 1, 1, '', '2025-12-04 10:46:57', NULL),
(16, 1, 'HS202512041047081', 'pending', 'pending', 'online', NULL, NULL, NULL, 110.00, 0.00, 0.00, 0.00, 110.00, 'INR', 1, 1, '', '2025-12-04 10:47:08', NULL),
(17, 1, 'HS202512041047561', 'pending', 'pending', 'online', NULL, NULL, NULL, 110.00, 0.00, 0.00, 0.00, 110.00, 'INR', 1, 1, '', '2025-12-04 10:47:56', NULL),
(18, 1, 'HS202512041051241', 'pending', 'pending', 'online', 'order_RnVXgacq88F4Nq', NULL, NULL, 110.00, 0.00, 0.00, 0.00, 110.00, 'INR', 1, 1, '', '2025-12-04 10:51:24', '2025-12-04 10:51:25'),
(19, 1, 'HS202512041056061', 'pending', 'pending', 'online', 'order_RnVcdGfLi6yKHx', NULL, NULL, 110.00, 0.00, 0.00, 0.00, 110.00, 'INR', 1, 1, '', '2025-12-04 10:56:06', '2025-12-04 10:56:06'),
(20, 1, 'HS202512041106381', 'pending', 'pending', 'online', 'order_RnVnlrPP7RkDPl', NULL, NULL, 110.00, 0.00, 0.00, 0.00, 110.00, 'INR', 1, 1, '', '2025-12-04 11:06:38', '2025-12-04 11:06:39'),
(21, 1, 'HS202512041109571', 'pending', 'pending', 'online', 'order_RnVrGj4fpAhE31', NULL, NULL, 110.00, 0.00, 0.00, 0.00, 110.00, 'INR', 1, 1, '', '2025-12-04 11:09:57', '2025-12-04 11:09:57'),
(22, 1, 'HS202512041110391', 'pending', 'pending', 'online', 'order_RnVs0NLPppeE5R', NULL, NULL, 110.00, 0.00, 0.00, 0.00, 110.00, 'INR', 1, 1, '', '2025-12-04 11:10:39', '2025-12-04 11:10:39'),
(23, 12, 'HS2025120411231812', '', 'paid', 'online', 'order_RnW5OC1CnEf3AF', 'pay_RnW6ETVzazcsSU', NULL, 120.00, 0.00, 0.00, 0.00, 120.00, 'INR', 3, 3, '', '2025-12-04 11:23:18', '2025-12-04 11:24:24'),
(24, 1, 'HS202512041157481', '', 'failed', 'online', 'order_RnWfovfCyMVcMe', NULL, NULL, 110.00, 0.00, 0.00, 0.00, 110.00, 'INR', 9, 9, '', '2025-12-04 11:57:48', '2025-12-04 11:57:52'),
(25, 12, 'HS2025120413425712', '', 'failed', 'online', 'order_RnYStpCIxYDgPh', NULL, NULL, 1.00, 0.00, 0.00, 0.00, 1.00, 'INR', 3, 3, '', '2025-12-04 13:42:57', '2025-12-04 13:43:09'),
(26, 1, 'HS202512091641341', '', 'paid', 'online', 'order_RpaBBtJANQQ8ab', 'pay_RpaBZPipsDHOxo', NULL, 1.00, 0.00, 0.00, 0.00, 1.00, 'INR', 1, 1, '', '2025-12-09 16:41:34', '2025-12-09 16:42:16'),
(27, 12, 'HS2025122514022212', '', 'paid', 'online', 'order_Rvs0wJtgbSXhB5', 'pay_Rvs1VchkRy3Z2L', NULL, 57.65, 0.00, 0.00, 0.00, 57.65, 'INR', 3, 3, '', '2025-12-25 14:02:22', '2025-12-25 14:03:11');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(100) DEFAULT NULL,
  `qty` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `qty`, `unit_price`, `total_price`, `created_at`) VALUES
(7, 7, 17, 'Nagpur Oranges', NULL, 1, 110.00, 110.00, '2025-11-26 21:04:47'),
(8, 8, 22, 'ABC Detox Juice ', NULL, 1, 120.00, 120.00, '2025-12-02 15:07:45'),
(9, 8, 24, 'Sugarcane Juice', NULL, 1, 60.00, 60.00, '2025-12-02 15:07:45'),
(10, 9, 22, 'ABC Detox Juice ', NULL, 1, 120.00, 120.00, '2025-12-02 15:20:53'),
(11, 10, 22, 'ABC Detox Juice ', NULL, 1, 120.00, 120.00, '2025-12-02 15:25:51'),
(12, 11, 22, 'ABC Detox Juice ', NULL, 1, 120.00, 120.00, '2025-12-02 15:33:34'),
(13, 12, 17, 'Nagpur Oranges', NULL, 1, 110.00, 110.00, '2025-12-02 21:50:31'),
(14, 13, 21, 'Desi Gajar (Red Carrot)', NULL, 1, 1.00, 1.00, '2025-12-02 23:55:29'),
(15, 14, 17, 'Nagpur Oranges', NULL, 1, 110.00, 110.00, '2025-12-04 10:46:53'),
(16, 15, 17, 'Nagpur Oranges', NULL, 1, 110.00, 110.00, '2025-12-04 10:46:57'),
(17, 16, 17, 'Nagpur Oranges', NULL, 1, 110.00, 110.00, '2025-12-04 10:47:08'),
(18, 17, 17, 'Nagpur Oranges', NULL, 1, 110.00, 110.00, '2025-12-04 10:47:56'),
(19, 18, 17, 'Nagpur Oranges', NULL, 1, 110.00, 110.00, '2025-12-04 10:51:24'),
(20, 19, 17, 'Nagpur Oranges', NULL, 1, 110.00, 110.00, '2025-12-04 10:56:06'),
(21, 20, 17, 'Nagpur Oranges', NULL, 1, 110.00, 110.00, '2025-12-04 11:06:38'),
(22, 21, 17, 'Nagpur Oranges', NULL, 1, 110.00, 110.00, '2025-12-04 11:09:57'),
(23, 22, 17, 'Nagpur Oranges', NULL, 1, 110.00, 110.00, '2025-12-04 11:10:39'),
(24, 23, 22, 'ABC Detox Juice ', NULL, 1, 120.00, 120.00, '2025-12-04 11:23:18'),
(25, 24, 17, 'Nagpur Oranges', NULL, 1, 110.00, 110.00, '2025-12-04 11:57:48'),
(26, 25, 21, 'Desi Gajar (Red Carrot)', NULL, 1, 1.00, 1.00, '2025-12-04 13:42:57'),
(27, 26, 0, 'Office Salad Lunch Plan', NULL, 1, 1.00, 1.00, '2025-12-09 16:41:34'),
(28, 27, 0, 'Protein Salad (300ml) - Monthly Pack', NULL, 1, 57.65, 57.65, '2025-12-25 14:02:22');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(190) NOT NULL,
  `slug` varchar(190) NOT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `long_description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `currency_code` char(3) NOT NULL DEFAULT 'INR',
  `sku` varchar(100) NOT NULL,
  `category` varchar(150) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `stock_status` enum('in_stock','out_of_stock','preorder') NOT NULL DEFAULT 'in_stock',
  `stock_quantity` int(10) UNSIGNED DEFAULT 0,
  `main_image` varchar(255) DEFAULT NULL,
  `thumbnail_image` varchar(255) DEFAULT NULL,
  `gallery_images` text DEFAULT NULL,
  `average_rating` decimal(3,2) DEFAULT 0.00,
  `rating_count` int(10) UNSIGNED DEFAULT 0,
  `review_count` int(10) UNSIGNED DEFAULT 0,
  `serving_size` varchar(100) DEFAULT 'Per 100 g',
  `calories_kcal` int(10) UNSIGNED DEFAULT 0,
  `protein_g` decimal(5,2) DEFAULT 0.00,
  `carbs_g` decimal(5,2) DEFAULT 0.00,
  `fat_g` decimal(5,2) DEFAULT 0.00,
  `fibre_g` decimal(5,2) DEFAULT 0.00,
  `sugar_g` decimal(5,2) DEFAULT 0.00,
  `sodium_mg` int(10) UNSIGNED DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `subscription_available` tinyint(1) NOT NULL DEFAULT 0,
  `prep_time_minutes` int(10) UNSIGNED DEFAULT NULL,
  `is_veg` tinyint(1) NOT NULL DEFAULT 1,
  `max_per_order` int(10) UNSIGNED DEFAULT NULL,
  `seo_title` varchar(190) DEFAULT NULL,
  `seo_description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `short_description`, `long_description`, `price`, `sale_price`, `currency_code`, `sku`, `category`, `tags`, `stock_status`, `stock_quantity`, `main_image`, `thumbnail_image`, `gallery_images`, `average_rating`, `rating_count`, `review_count`, `serving_size`, `calories_kcal`, `protein_g`, `carbs_g`, `fat_g`, `fibre_g`, `sugar_g`, `sodium_mg`, `is_active`, `is_featured`, `subscription_available`, `prep_time_minutes`, `is_veg`, `max_per_order`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
(1, 'Bosco Apple Fruit', 'bosco-apple-fruit', 'Premium, juicy apples with balanced sweetness and tanginess.', 'Prepare to embark on a sensory journey with the Bosco Apple, a fruit that transcends the ordinary and promises an unparalleled taste experience...', 120.85, 150.99, 'INR', 'Bosco-Apple-Fruit', 'Fresh Fruits', 'Fruits,Organic,Apple', 'in_stock', 100, 'assets/img/product/product_details_1_1.jpg', 'assets/img/product/product_1_1.jpg', NULL, 5.00, 0, 0, 'Per 100 g', 52, 0.30, 14.00, 0.20, 2.40, 10.00, 1, 1, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-16 23:05:49', '2025-11-17 23:14:54'),
(18, 'Elaichi Banana ', 'elaichi-banana-1-dozen', 'Naturally sweet elaichi bananas, great for energy and digestion.', 'Perfect breakfast or pre-workout fruit, easy to digest and naturally sweet without added sugar.', 65.00, NULL, 'INR', 'PROD-BANANA-003', 'Fresh Fruits', 'Fruits,Banana,Energy', 'in_stock', 120, 'assets/img/product/product_1_7.jpg', 'assets/img/product/product_1_7.jpg', NULL, 4.85, 0, 0, 'Per 100 g', 89, 1.10, 23.00, 0.30, 2.60, 12.00, 1, 1, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-16 23:09:05', '2025-11-17 23:15:05'),
(19, 'Palak (Fresh Spinach)', 'palak-fresh-spinach-500g', 'Fresh, tender spinach leaves for sabji, paratha and smoothies.', 'Locally sourced, pesticide-free spinach ideal for palak paneer, dal palak and green smoothies.', 40.00, 50.00, 'INR', 'PROD-PALAK-004', 'Vegetables', 'Leafy Greens,Palak,Iron', 'in_stock', 60, 'assets/img/product/product_1_2.jpg', 'assets/img/product/product_1_2.jpg', NULL, 4.80, 0, 0, 'Per 100 g', 23, 2.90, 3.60, 0.40, 2.20, 0.40, 79, 1, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-16 23:09:05', '2025-11-23 22:46:04'),
(20, 'Lal Pyaz (Red Onion)', 'lal-pyaz-red-onion-1kg', 'Crisp red onions, perfect for salads, curries and tadka.', 'Enhances flavour of every Indian dish – from bhaji and curries to raw kanda with bhakri.', 30.00, 40.00, 'INR', 'PROD-ONION-005', 'Vegetables', 'Onion,Daily Veg,Indian Kitchen', 'in_stock', 150, 'assets/img/product/product_1_4.jpg', 'assets/img/product/product_1_4.jpg', NULL, 4.70, 0, 0, 'Per 100 g', 40, 1.10, 9.30, 0.10, 1.70, 4.20, 4, 1, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-16 23:09:05', '2025-11-17 23:15:13'),
(21, 'Desi Gajar (Red Carrot)', 'desi-gajar-red-carrot', 'Seasonal red carrots ideal for gajar ka halwa and salads.', 'Traditional Indian red carrots rich in beta-carotene, perfect for winter recipes and raw munching.', 1.00, NULL, 'INR', 'PROD-CARROT-006', 'Vegetables', 'Carrot,Seasonal,Vitamin A', 'in_stock', 90, 'assets/img/product/product_1_8.jpg', 'assets/img/product/product_1_8.jpg', NULL, 4.95, 0, 0, 'Per 100 g', 41, 0.90, 10.00, 0.20, 2.80, 4.70, 69, 1, 0, 0, NULL, 1, NULL, 'Desi Gajar (Red Carrot) | HealthySafar', 'Seasonal red carrots ideal for gajar ka halwa and salads.', '2025-11-16 23:09:05', '2025-12-02 23:31:18'),
(22, 'ABC Detox Juice ', 'abc-detox-juice', 'Cold-pressed ABC juice for detox and glowing skin.', 'Cold-pressed blend of apple, beetroot and carrot with no added sugar or preservatives.', 120.00, 150.00, 'INR', 'PROD-JUICE-007', 'Fruit Juice', 'Juice,Detox,Cold Pressed', 'in_stock', 50, 'uploads/products/1763945129_45430cc8ab3e59a74c8b.png', 'uploads/products/1763945129_32442443f603bae90161.png', NULL, 4.90, 0, 0, 'Per 250 ml', 110, 1.50, 25.00, 0.30, 3.00, 20.00, 25, 1, 0, 0, NULL, 1, NULL, 'ABC Detox Juice  | HealthySafar', 'Cold-pressed ABC juice for detox and glowing skin.', '2025-11-16 23:09:05', '2025-11-24 00:45:29'),
(23, 'Jamun Shots ', 'jamun-shots-6-pack', 'Jamun-based health shots to help manage blood sugar naturally.', 'Prepared with real jamun pulp and seeds powder, traditionally believed to support glucose metabolism.', 180.00, 210.00, 'INR', 'PROD-JAMUN-008', 'Fruit Juice', 'Jamun,Diabetic Friendly,Ayurvedic', 'in_stock', 40, 'assets/img/healthysafar/jamunjuice.jpg', 'assets/img/healthysafar/jamunjuice.jpg', NULL, 4.85, 0, 0, 'Per 100 ml', 35, 0.40, 8.00, 0.10, 1.00, 6.00, 5, 1, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-16 23:09:05', '2025-11-17 23:15:24'),
(24, 'Sugarcane Juice', 'sugarcane-juice-300ml', 'Freshly pressed ganne ka ras with lemon and ginger.', 'Hydrating and refreshing drink, served chilled with a hint of lemon and ginger, no refined sugar added.', 60.00, NULL, 'INR', 'PROD-GANNA-009', 'Fruit Juice', 'Juice,Traditional,Hydrating', 'in_stock', 70, 'assets/img/healthysafar/bottle-of-fresh-beet-juice-on-gray-background-2023-11-27-05-01-09-utc.jpg', 'assets/img/healthysafar/bottle-of-fresh-beet-juice-on-gray-background-2023-11-27-05-01-09-utc.jpg', NULL, 4.60, 0, 0, 'Per 250 ml', 150, 0.80, 37.00, 0.20, 0.00, 32.00, 15, 1, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-16 23:09:05', '2025-11-17 23:15:28'),
(25, 'Classic Fruit Salad Bowl', 'classic-fruit-salad-bowl', 'Ready-to-eat mix of seasonal cut fruits.', 'Colourful bowl of seasonal fruits like apple, banana, papaya, pomegranate and grapes – perfect as a mid-meal snack.', 130.00, 150.00, 'INR', 'PROD-SALAD-010', 'Salads', 'Salad,Fruit Bowl,Healthy Snack', 'in_stock', 45, 'assets/img/healthysafar/saladplate.jpg', 'assets/img/healthysafar/saladplate.jpg', NULL, 4.95, 0, 0, 'Per 150 g', 90, 1.30, 22.00, 0.40, 3.00, 16.00, 5, 1, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-16 23:09:05', '2025-11-17 23:15:32'),
(26, 'Green Detox Salad', 'green-detox-salad', 'Cucumber, lettuce, sprouts and seeds with light dressing.', 'Low-calorie salad with fresh greens, crunchy veggies and roasted seeds, ideal for weight management.', 150.00, 180.00, 'INR', 'PROD-SALAD-011', 'Salads', 'Salad,Low Calorie,Detox', 'in_stock', 40, 'assets/img/healthysafar/salad.png', 'assets/img/healthysafar/salad.png', NULL, 4.85, 0, 0, 'Per 150 g', 75, 4.00, 10.00, 3.50, 4.00, 4.00, 120, 1, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-16 23:09:05', '2025-11-17 23:15:37'),
(27, 'Sprouts & Veggie  Salad', 'sprouts-veggie-power-salad', 'High-protein sprout mix with veggies and lemon dressing.', 'Protein-rich salad made with mixed sprouts, onions, tomatoes, cucumber and coriander with masala tadka.', 160.00, 190.00, 'INR', 'PROD-SALAD-012', 'Salads', 'Salad,Sprouts,High Protein', 'in_stock', 35, 'assets/img/healthysafar/fresh-mix-vegetable-salad-in-metal-plate-on-rustic-2023-11-27-05-12-25-utc.png', 'assets/img/healthysafar/fresh-mix-vegetable-salad-in-metal-plate-on-rustic-2023-11-27-05-12-25-utc.png', NULL, 4.90, 0, 0, 'Per 150 g', 120, 8.00, 18.00, 3.00, 6.00, 5.00, 180, 1, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-16 23:09:05', '2025-11-17 23:15:41');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `subscription_plan_id` int(10) UNSIGNED NOT NULL,
  `duration_days` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_deliveries_planned` int(11) NOT NULL DEFAULT 0,
  `postponement_limit` int(11) NOT NULL DEFAULT 0,
  `postponement_used` int(11) NOT NULL DEFAULT 0,
  `base_address_id` int(10) UNSIGNED DEFAULT NULL,
  `default_slot_key` varchar(50) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','active','completed','cancelled') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `customer_id`, `subscription_plan_id`, `duration_days`, `start_date`, `end_date`, `total_deliveries_planned`, `postponement_limit`, `postponement_used`, `base_address_id`, `default_slot_key`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(2, NULL, 1, 4, 7, '2025-11-29', '2025-12-06', 7, 0, 1, 1, 'lunch', 210.00, 'active', '2025-11-26 21:04:47', '2025-11-26 22:48:35'),
(3, NULL, 1, 2, 30, '2025-12-11', '2026-01-14', 30, 2, 0, 1, 'lunch', 1.00, 'active', '2025-12-09 16:41:34', '2025-12-09 16:42:16'),
(4, NULL, 12, 5, 1, '2025-12-27', '2025-12-27', 1, 0, 0, 3, 'lunch', 57.65, 'active', '2025-12-25 14:02:22', '2025-12-25 14:03:11');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_deliveries`
--

CREATE TABLE `subscription_deliveries` (
  `id` int(10) UNSIGNED NOT NULL,
  `subscription_id` int(10) UNSIGNED NOT NULL,
  `subscription_plan_id` int(10) UNSIGNED NOT NULL,
  `is_choice_based` tinyint(1) NOT NULL DEFAULT 0,
  `selected_type` enum('plan','menu_item') DEFAULT NULL,
  `selected_plan_id` int(10) UNSIGNED DEFAULT NULL,
  `selected_menu_item_id` int(10) UNSIGNED DEFAULT NULL,
  `selected_by` enum('customer','admin') DEFAULT 'customer',
  `selected_at` datetime DEFAULT NULL,
  `selection_notes` varchar(255) DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `delivery_date` date NOT NULL,
  `base_address_id` int(10) UNSIGNED DEFAULT NULL,
  `override_address_id` int(10) UNSIGNED DEFAULT NULL,
  `base_slot_key` varchar(50) NOT NULL,
  `override_slot_key` varchar(50) DEFAULT NULL,
  `status` enum('pending','out_for_delivery','delivered','skipped','cancelled') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `is_generated_extension` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `menu_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_deliveries`
--

INSERT INTO `subscription_deliveries` (`id`, `subscription_id`, `subscription_plan_id`, `is_choice_based`, `selected_type`, `selected_plan_id`, `selected_menu_item_id`, `selected_by`, `selected_at`, `selection_notes`, `user_id`, `customer_id`, `delivery_date`, `base_address_id`, `override_address_id`, `base_slot_key`, `override_slot_key`, `status`, `notes`, `is_generated_extension`, `created_at`, `updated_at`, `menu_id`) VALUES
(8, 2, 4, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-11-29', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-11-26 21:04:47', '2025-11-26 21:04:47', NULL),
(9, 2, 4, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-01', 1, NULL, 'lunch', NULL, 'skipped', NULL, 0, '2025-11-26 21:04:47', '2025-11-26 22:48:35', NULL),
(10, 2, 4, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-02', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-11-26 21:04:47', '2025-11-26 21:04:47', NULL),
(11, 2, 4, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-03', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-11-26 21:04:47', '2025-11-26 21:04:47', NULL),
(12, 2, 4, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-04', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-11-26 21:04:47', '2025-11-26 21:04:47', NULL),
(13, 2, 4, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-05', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-11-26 21:04:47', '2025-11-26 21:04:47', NULL),
(14, 2, 4, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-06', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-11-26 21:04:47', '2025-11-26 22:47:11', NULL),
(15, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-11', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(16, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-12', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(17, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-13', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(18, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-15', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(19, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-16', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(20, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-17', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(21, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-18', 1, NULL, 'lunch', NULL, 'delivered', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:44:01', NULL),
(22, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-19', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(23, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-20', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(24, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-22', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(25, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-23', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(26, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-24', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(27, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-25', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(28, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-26', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(29, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-27', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(30, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-29', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(31, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-30', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(32, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2025-12-31', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(33, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2026-01-01', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(34, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2026-01-02', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(35, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2026-01-03', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(36, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2026-01-05', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(37, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2026-01-06', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(38, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2026-01-07', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(39, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2026-01-08', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2026-01-08 10:01:15', NULL),
(40, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2026-01-09', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(41, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2026-01-10', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(42, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2026-01-12', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(43, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2026-01-13', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(44, 3, 2, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 1, '2026-01-14', 1, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-09 16:41:34', '2025-12-09 16:41:34', NULL),
(45, 4, 5, 0, NULL, NULL, NULL, 'customer', NULL, NULL, NULL, 12, '2025-12-27', 3, NULL, 'lunch', NULL, 'pending', NULL, 0, '2025-12-25 14:02:22', '2025-12-25 14:02:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_menu_items`
--

CREATE TABLE `subscription_menu_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `calories_kcal` int(11) DEFAULT NULL,
  `protein_g` decimal(6,2) DEFAULT NULL,
  `carbs_g` decimal(6,2) DEFAULT NULL,
  `fats_g` decimal(6,2) DEFAULT NULL,
  `fibre_g` decimal(6,2) DEFAULT NULL,
  `sugar_g` decimal(6,2) DEFAULT NULL,
  `sodium_mg` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `slug` varchar(160) NOT NULL,
  `short_description` text DEFAULT NULL,
  `long_description` mediumtext DEFAULT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pricing_type` enum('per_day','per_package') NOT NULL DEFAULT 'per_package',
  `menu_mode` enum('fixed','choice') NOT NULL DEFAULT 'fixed',
  `choice_per_day_limit` tinyint(4) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `title`, `slug`, `short_description`, `long_description`, `thumbnail_url`, `banner_url`, `base_price`, `pricing_type`, `menu_mode`, `choice_per_day_limit`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(2, 'Office Salad Lunch Plan', 'office-salad-lunch-plan', 'Fresh salads for your office lunch, daily.', 'Crisp, fibre-rich salads with balanced macros, suitable for regular office-goers. Easy to digest and keeps you light yet energetic through the day.', 'uploads/subscriptions/salad-thumb.jpg', 'uploads/subscriptions/salad-banner.jpg', 1799.00, 'per_package', 'fixed', 1, 0, 2, '2025-11-24 00:33:01', '2025-12-25 07:05:49'),
(3, 'Detox Juice Cleanse Plan', 'detox-juice-cleanse-plan', 'Cold-pressed juices to detox and refresh your system.', 'A curated set of detoxifying, cold-pressed juices rich in antioxidants. Best suited for short-term cleanses and weekend detox routines.', 'uploads/subscriptions/detox-thumb.jpg', 'uploads/subscriptions/detox-banner.jpg', 249.00, 'per_day', 'fixed', 1, 1, 3, '2025-11-24 00:33:01', '2025-11-24 00:33:01'),
(4, 'Test Subscription', 'Test-Subscription', 'Short Description Short Description Short Description', 'Long Description / Health Benefits Long Description / Health Benefits Long Description / Health Benefits Long Description / Health Benefits vLong Description / Health Benefits', 'uploads/subscriptions/1764115342_215df28bd3d4192348f3.png', 'uploads/subscriptions/1764115342_aed25b4e7387c53ab8f8.png', 210.00, 'per_package', 'fixed', 1, 0, 0, '2025-11-26 00:02:22', '2025-12-25 06:35:17'),
(5, 'Protein Salad (300ml) - Monthly Pack', 'protein-salad-300ml', 'One month package (Mon–Fri, 20 boxes). Sunday off.', 'Protein Salad (300ml) - Monthly Pack\r\n\r\n\r\n\r\n\r\nDetails: One month package (Mon–Fri, 20 boxes). Sunday off.\r\n\r\n\r\n\r\n\r\nIncludes:\r\n- Freshly prepared, hygienic packaging\r\n- Sunday off\r\n- Lunch/Dinner delivery slots (configurable)\r\n\r\n\r\n\r\n\r\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', 'uploads/subscriptions/1766646195_210a62aab1fed0b02e95.jpeg', 'uploads/subscriptions/1766646195_87ca5ba1b0e6db1ce01a.jpeg', 1499.00, 'per_package', 'fixed', 1, 1, 1, '2025-12-24 18:02:42', '2025-12-25 07:03:15'),
(6, 'Boiled Chicken + Green Salad (200g, bone-in) - Monthly Pack', 'boiled-chicken-200gms-with-bones', 'Boiled chicken 200g (curry cut with bones) + green salad. Monthly pack. Sunday off.', 'Boiled Chicken + Green Salad (200g, bone-in) - Monthly Pack\r\n\r\n\r\n\r\n\r\nDetails: Boiled chicken 200g (curry cut with bones) + green salad. Monthly pack. Sunday off.\r\n\r\n\r\n\r\n\r\nIncludes:\r\n- Freshly prepared, hygienic packaging\r\n- Sunday off\r\n- Lunch/Dinner delivery slots (configurable)\r\n\r\n\r\n\r\n\r\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', 'uploads/subscriptions/1766646323_2308219c767ddcba9a21.jpeg', 'uploads/subscriptions/1766646323_e8f1342433e24d5338c4.jpeg', 3499.00, 'per_package', 'fixed', 1, 1, 2, '2025-12-24 18:02:42', '2025-12-25 07:05:23'),
(7, 'Oats Smoothie (250ml) - Monthly Pack', 'oats-smoothie', '250ml can. Monthly pack. Sunday off.', 'Oats Smoothie (250ml) - Monthly Pack\r\n\r\n\r\n\r\n\r\nDetails: 250ml can. Monthly pack. Sunday off.\r\n\r\n\r\n\r\n\r\nIncludes:\r\n- Freshly prepared, hygienic packaging\r\n- Sunday off\r\n- Lunch/Dinner delivery slots (configurable)\r\n\r\n\r\n\r\n\r\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', 'uploads/subscriptions/1766646456_6245534d4ebb67b7d1f4.jpeg', 'uploads/subscriptions/1766646456_966a90a30dffe2cb4e15.jpeg', 4999.00, 'per_package', 'fixed', 1, 1, 3, '2025-12-24 18:02:42', '2025-12-25 07:07:36'),
(8, 'Mini Meal Box - Monthly Pack (26 boxes)', 'mini-meal-box', 'One month package (26 boxes). Sunday off. Includes 3 chapati + 1 veg + green salad.', 'Mini Meal Box - Monthly Pack (26 boxes)\n\n\n\n\nDetails: One month package (26 boxes). Sunday off. Includes 3 chapati + 1 veg + green salad.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 3499.00, 'per_package', 'fixed', 1, 1, 4, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(9, 'Fruit Box (300ml) - Monthly Pack', 'fruitbox-300ml', 'One month package (Mon–Fri, 20 boxes). Sunday off.', 'Fruit Box (300ml) - Monthly Pack\n\n\n\n\nDetails: One month package (Mon–Fri, 20 boxes). Sunday off.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 1499.00, 'per_package', 'fixed', 1, 1, 5, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(10, 'Healthy Smoothie (250ml) - Members Monthly Pack', 'healthy-smoothie-250ml-only-for-members', 'One month package (20 glasses). Members only. Sunday off.', 'Healthy Smoothie (250ml) - Members Monthly Pack\n\n\n\n\nDetails: One month package (20 glasses). Members only. Sunday off.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 1979.00, 'per_package', 'fixed', 1, 1, 6, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(11, 'Boiled Egg Salad (500ml) - Monthly Pack', 'boiled-egg-salad-500ml-box', 'One month package (Mon–Fri, 20 boxes). Sunday off.', 'Boiled Egg Salad (500ml) - Monthly Pack\n\n\n\n\nDetails: One month package (Mon–Fri, 20 boxes). Sunday off.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 2499.00, 'per_package', 'fixed', 1, 1, 7, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(12, 'Vegetable Salad (300ml) - Monthly Pack', 'vegetable-salad-300ml-box', 'One month package (Mon–Fri, 20 boxes). Sunday off.', 'Vegetable Salad (300ml) - Monthly Pack\n\n\n\n\nDetails: One month package (Mon–Fri, 20 boxes). Sunday off.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 1499.00, 'per_package', 'fixed', 1, 1, 8, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(13, 'ABC Juice (250ml) - Members Monthly Pack', 'abc-juice-only-for-members', 'ABC cold-press juice 250ml. One month package (20 glasses). Members only. Sunday off.', 'ABC Juice (250ml) - Members Monthly Pack\n\n\n\n\nDetails: ABC cold-press juice 250ml. One month package (20 glasses). Members only. Sunday off.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 1979.00, 'per_package', 'fixed', 1, 1, 9, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(14, 'Fruit Box (300ml) - 3 Months Pack (Members)', 'fruitbox-300ml-3-months-package', '3 months package (members offer). Sunday off.', 'Fruit Box (300ml) - 3 Months Pack (Members)\n\n\n\n\nDetails: 3 months package (members offer). Sunday off.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 3822.00, 'per_package', 'fixed', 1, 1, 10, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(15, 'Mini Meal Box - Single Box', 'mini-meal-box-11', 'Single box: 3 chapati + 1 veg + salad.', 'Mini Meal Box - Single Box\n\n\n\n\nDetails: Single box: 3 chapati + 1 veg + salad.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 3874.00, 'per_package', 'fixed', 1, 1, 11, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(16, 'Paneer Salad (200g paneer) - Single Box', 'paneer-salad', '200g paneer + dressing + vegetable salad.', 'Paneer Salad (200g paneer) - Single Box\n\n\n\n\nDetails: 200g paneer + dressing + vegetable salad.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 12974.00, 'per_package', 'fixed', 1, 1, 12, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(17, 'Chicken Rice Meal Box - Monthly Pack', 'chicken-rice', 'Boneless chicken 200g + rice + green salad + chutani. Monthly pack. Sunday off.', 'Chicken Rice Meal Box - Monthly Pack\n\n\n\n\nDetails: Boneless chicken 200g + rice + green salad + chutani. Monthly pack. Sunday off.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 7999.00, 'per_package', 'fixed', 1, 1, 13, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(18, 'Mix Salads Combo (4 bowls) - Pack', 'mix-salads', '4-salad combo (Mediterranean Delight, Protein Powerhouse, Asian Fusion, etc.).', 'Mix Salads Combo (4 bowls) - Pack\n\n\n\n\nDetails: 4-salad combo (Mediterranean Delight, Protein Powerhouse, Asian Fusion, etc.).\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 31174.00, 'per_package', 'fixed', 1, 1, 14, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(19, 'Protein Choice Salad (Paneer/Chicken/Salmon) - Monthly Pack', 'paneer-chicken-breast-salmon-fish-salad', '200g (paneer/chicken breast/salmon) + salad/rice + chutani/yogurt, etc. Monthly pack. Sunday off.', 'Protein Choice Salad (Paneer/Chicken/Salmon) - Monthly Pack\n\n\n\n\nDetails: 200g (paneer/chicken breast/salmon) + salad/rice + chutani/yogurt, etc. Monthly pack. Sunday off.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 10399.00, 'per_package', 'fixed', 1, 1, 15, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(20, 'Regular Meal Box - Trial / Single Box', 'regular-meal-box-trial-single-box', '4 chapati + daal rice + 2 veg + green salad.', 'Regular Meal Box - Trial / Single Box\n\n\n\n\nDetails: 4 chapati + daal rice + 2 veg + green salad.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 6500.00, 'per_package', 'fixed', 1, 1, 16, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(21, 'Protein Salad (500ml) - Monthly Pack (26 boxes)', 'protein-salad-big-box', '500ml box. One month package (26 boxes). Sunday off. Valid for 45 days.', 'Protein Salad (500ml) - Monthly Pack (26 boxes)\n\n\n\n\nDetails: 500ml box. One month package (26 boxes). Sunday off. Valid for 45 days.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 3999.00, 'per_package', 'fixed', 1, 1, 17, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(22, 'ABC Juice - Monthly Pack (Valid 45 days)', 'abc-juice', 'One month package. Sunday off. Valid for 45 days.', 'ABC Juice - Monthly Pack (Valid 45 days)\n\n\n\n\nDetails: One month package. Sunday off. Valid for 45 days.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 4999.00, 'per_package', 'fixed', 1, 1, 18, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(23, 'Fruit Box (300ml) - Monthly Pack (Healthy Diet Box)', 'fruit-box-small-300ml', 'Healthy diet box 300ml. One month package. Sunday off. Valid for 45 days.', 'Fruit Box (300ml) - Monthly Pack (Healthy Diet Box)\n\n\n\n\nDetails: Healthy diet box 300ml. One month package. Sunday off. Valid for 45 days.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 2999.00, 'per_package', 'fixed', 1, 1, 19, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(24, 'Protein Meal Box - Monthly Pack (26 boxes)', 'protein-meal-box', 'One month package (26 boxes). Sunday off. Valid for 45 days.', 'Protein Meal Box - Monthly Pack (26 boxes)\n\n\n\n\nDetails: One month package (26 boxes). Sunday off. Valid for 45 days.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 4999.00, 'per_package', 'fixed', 1, 1, 20, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(25, 'Protein Salad (300ml) - Small Pack (Healthy Diet Box)', 'protein-salad-small-pack', 'Healthy diet box 300ml. One month package. Sunday off. Valid for 45 days.', 'Protein Salad (300ml) - Small Pack (Healthy Diet Box)\n\n\n\n\nDetails: Healthy diet box 300ml. One month package. Sunday off. Valid for 45 days.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 2999.00, 'per_package', 'fixed', 1, 1, 21, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(26, 'Premium Vegetable Salad (750ml) - Monthly Pack', 'premium-vegetable-salad', 'Healthy diet box 750ml. One month package. Sunday off. Valid for 45 days.', 'Premium Vegetable Salad (750ml) - Monthly Pack\n\n\n\n\nDetails: Healthy diet box 750ml. One month package. Sunday off. Valid for 45 days.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 4999.00, 'per_package', 'fixed', 1, 1, 22, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(27, 'Fruits Box (500ml) - Monthly Pack', 'fruits-box', 'Big size 500ml. One month package. Sunday off. Valid for 45 days.', 'Fruits Box (500ml) - Monthly Pack\n\n\n\n\nDetails: Big size 500ml. One month package. Sunday off. Valid for 45 days.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 3999.00, 'per_package', 'fixed', 1, 1, 23, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(28, 'Healthy Vegetable & Fruit Juice - Monthly Pack', 'healthy-vegetable-and-fruit-juice', 'One month package. Sunday off.', 'Healthy Vegetable & Fruit Juice - Monthly Pack\n\n\n\n\nDetails: One month package. Sunday off.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 4999.00, 'per_package', 'fixed', 1, 1, 24, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(29, 'Protein Paratha Combo', 'protein-paratha', '2 paratha + curd + basil pesto/green chutani/tomato sauce + pickle.', 'Protein Paratha Combo\n\n\n\n\nDetails: 2 paratha + curd + basil pesto/green chutani/tomato sauce + pickle.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 51974.00, 'per_package', 'fixed', 1, 1, 25, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(30, 'Fruit Box – One Week Free (2 Months Pack)', 'fruit-box-one-week-free', 'Two months pack. Includes one week free (as per offer).', 'Fruit Box – One Week Free (2 Months Pack)\n\n\n\n\nDetails: Two months pack. Includes one week free (as per offer).\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 8000.00, 'per_package', 'fixed', 1, 1, 26, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(31, 'Protein Shake (100% Natural) - Pack', 'protein-shake-100-natural', 'Ingredients as per recipe. Pack.', 'Protein Shake (100% Natural) - Pack\n\n\n\n\nDetails: Ingredients as per recipe. Pack.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 5499.00, 'per_package', 'fixed', 1, 1, 27, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(32, 'Protein Rich Meal Bowl - 1 Week Pack (6 days)', 'protein-rich-meal-bowl-week', 'One week package (6 days). Sunday off.', 'Protein Rich Meal Bowl - 1 Week Pack (6 days)\n\n\n\n\nDetails: One week package (6 days). Sunday off.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 8662.00, 'per_package', 'fixed', 1, 1, 28, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(33, 'Protein Rich Meal Bowl - Monthly Pack (26 boxes)', 'protein-rich-meal-bowl-month', 'One month package (26 boxes). Sunday off.', 'Protein Rich Meal Bowl - Monthly Pack (26 boxes)\n\n\n\n\nDetails: One month package (26 boxes). Sunday off.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 4999.00, 'per_package', 'fixed', 1, 1, 29, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(34, 'Healthy & Delicious Juice Mix - Monthly Pack', 'healthy-and-delicious-juice', 'Includes Sunburst, Vitamin-C Booster, Miracle, Green Heaven etc.', 'Healthy & Delicious Juice Mix - Monthly Pack\n\n\n\n\nDetails: Includes Sunburst, Vitamin-C Booster, Miracle, Green Heaven etc.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 4999.00, 'per_package', 'fixed', 1, 1, 30, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(35, 'FSB Combo (Fruit + Protein Salad + Brown Breads) - Monthly Pack', 'fsb-fruit-protein-salad-brown-breads', 'Fruit + protein/vegetable salad + brown bread with peanut butter. Monthly pack. Sunday off.', 'FSB Combo (Fruit + Protein Salad + Brown Breads) - Monthly Pack\n\n\n\n\nDetails: Fruit + protein/vegetable salad + brown bread with peanut butter. Monthly pack. Sunday off.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 4999.00, 'per_package', 'fixed', 1, 1, 31, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(36, 'Oatmeal Combo (Oatmeal + Salad + Fruit) - Monthly Pack', 'oatmeal-combo', 'Oatmeal + salad + fruit. Monthly pack. Sunday off. 22 boxes count in one month.', 'Oatmeal Combo (Oatmeal + Salad + Fruit) - Monthly Pack\n\n\n\n\nDetails: Oatmeal + salad + fruit. Monthly pack. Sunday off. 22 boxes count in one month.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 4999.00, 'per_package', 'fixed', 1, 1, 32, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(37, 'Protein Salad (500ml) - Single Box', 'protein-salad', '500ml box single.', 'Protein Salad (500ml) - Single Box\n\n\n\n\nDetails: 500ml box single.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 6500.00, 'per_package', 'fixed', 1, 1, 33, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(38, 'Fruit Box (500ml) - Single Box', 'fruit-box', '500ml box single.', 'Fruit Box (500ml) - Single Box\n\n\n\n\nDetails: 500ml box single.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 6500.00, 'per_package', 'fixed', 1, 1, 34, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(39, 'Oatmeal - Monthly Pack', 'oatmeal', 'One month package. Sunday off.', 'Oatmeal - Monthly Pack\n\n\n\n\nDetails: One month package. Sunday off.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 4999.00, 'per_package', 'fixed', 1, 1, 35, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(40, 'Salad Trial Pack (Fruits, Protein & Vegetable) - 3 Days', 'salad-trial-pack', 'Daily one box delivered (3 days).', 'Salad Trial Pack (Fruits, Protein & Vegetable) - 3 Days\n\n\n\n\nDetails: Daily one box delivered (3 days).\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 6500.00, 'per_package', 'fixed', 1, 1, 36, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(41, 'Healthy Breakfast - Monthly Pack (22 boxes)', 'healthy-breakfast', 'One month package (22 boxes). Includes paneer/eggs/soya bhurji + brown bread, etc.', 'Healthy Breakfast - Monthly Pack (22 boxes)\n\n\n\n\nDetails: One month package (22 boxes). Includes paneer/eggs/soya bhurji + brown bread, etc.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 4999.00, 'per_package', 'fixed', 1, 1, 37, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(42, 'Vegetable Salad (500ml) - Single Box', 'vegetable-salad', '500ml box single.', 'Vegetable Salad (500ml) - Single Box\n\n\n\n\nDetails: 500ml box single.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 6500.00, 'per_package', 'fixed', 1, 1, 38, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(43, 'Fruit Box (500ml) - Single Box (variant)', 'fruit-box-39', '500ml box single.', 'Fruit Box (500ml) - Single Box (variant)\n\n\n\n\nDetails: 500ml box single.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 6500.00, 'per_package', 'fixed', 1, 1, 39, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(44, 'Mix Pack (Fruit Salad + Sprouts Salad + Vegetable Salad) - Monthly Pack', 'mix-pack', 'One month package (500ml box). Sunday off. Valid for 45 days.', 'Mix Pack (Fruit Salad + Sprouts Salad + Vegetable Salad) - Monthly Pack\n\n\n\n\nDetails: One month package (500ml box). Sunday off. Valid for 45 days.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 3999.00, 'per_package', 'fixed', 1, 1, 40, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(45, 'Vegetable Salad (300ml) - Healthy Diet Monthly Pack', 'vegetable-salad-diet-box', 'Healthy diet box 300ml. One month package. Sunday off. Valid for 45 days.', 'Vegetable Salad (300ml) - Healthy Diet Monthly Pack\n\n\n\n\nDetails: Healthy diet box 300ml. One month package. Sunday off. Valid for 45 days.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 3499.00, 'per_package', 'fixed', 1, 1, 41, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(46, 'Smoothie - Monthly Pack', 'smoothie', 'One month (Saturday & Sunday closed). Valid for 45 days.', 'Smoothie - Monthly Pack\n\n\n\n\nDetails: One month (Saturday & Sunday closed). Valid for 45 days.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 4999.00, 'per_package', 'fixed', 1, 1, 42, '2025-12-24 18:02:42', '2025-12-24 18:02:42'),
(47, 'Vegetable Salad (500ml) - Big Size Monthly Pack', 'vegetable-salad-big-size', '500ml big size box. One month package (Sunday off). Valid for 45 days.', 'Vegetable Salad (500ml) - Big Size Monthly Pack\n\n\n\n\nDetails: 500ml big size box. One month package (Sunday off). Valid for 45 days.\n\n\n\n\nIncludes:\n- Freshly prepared, hygienic packaging\n- Sunday off\n- Lunch/Dinner delivery slots (configurable)\n\n\n\n\nNote: Nutrition values are estimates and may vary by seasonal ingredients and portion size.', NULL, NULL, 3999.00, 'per_package', 'fixed', 1, 1, 43, '2025-12-24 18:02:42', '2025-12-24 18:02:42');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plan_choices`
--

CREATE TABLE `subscription_plan_choices` (
  `id` int(10) UNSIGNED NOT NULL,
  `subscription_plan_id` int(10) UNSIGNED NOT NULL,
  `option_type` enum('plan','menu_item') NOT NULL,
  `option_plan_id` int(10) UNSIGNED DEFAULT NULL,
  `option_menu_item_id` int(10) UNSIGNED DEFAULT NULL,
  `label` varchar(150) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plan_choice_pool`
--

CREATE TABLE `subscription_plan_choice_pool` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_plan_id` int(10) UNSIGNED NOT NULL,
  `option_type` enum('plan','menu_item') NOT NULL,
  `option_plan_id` int(10) UNSIGNED DEFAULT NULL,
  `option_menu_item_id` int(10) UNSIGNED DEFAULT NULL,
  `label` varchar(150) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plan_config`
--

CREATE TABLE `subscription_plan_config` (
  `id` int(10) UNSIGNED NOT NULL,
  `subscription_plan_id` int(10) UNSIGNED NOT NULL,
  `duration_options_json` text DEFAULT NULL,
  `delivery_slots_json` text DEFAULT NULL,
  `off_days_json` text DEFAULT NULL,
  `duration_pricing_json` text DEFAULT NULL,
  `postponement_limit` int(11) NOT NULL DEFAULT 0,
  `cut_off_hour` tinyint(4) DEFAULT 22,
  `min_start_offset_days` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_plan_config`
--

INSERT INTO `subscription_plan_config` (`id`, `subscription_plan_id`, `duration_options_json`, `delivery_slots_json`, `off_days_json`, `duration_pricing_json`, `postponement_limit`, `cut_off_hour`, `min_start_offset_days`, `created_at`, `updated_at`) VALUES
(2, 2, '[7,30]', '[{\"key\":\"lunch\",\"label\":\"Office Lunch\",\"window\":\"1 PM - 3 PM\"}]', '[\"SUN\"]', '{\"7\":1799,\"30\":1}', 2, 21, 1, '2025-11-24 00:33:01', '2025-12-09 16:40:52'),
(3, 3, '[3,5,7]', '[{\"key\":\"morning\",\"label\":\"Morning Delivery\",\"window\":\"7 AM - 9 AM\"}]', '[\"SUN\"]', '{\"3\":1299,\"5\":1999,\"7\":2599}', 1, 20, 1, '2025-11-24 00:33:01', '2025-11-24 00:33:01'),
(4, 4, '[7]', '[{\"key\":\"lunch\",\"label\":\"Lunch\",\"window\":\"12 PM - 2 PM\"}]', '[\"SUN\"]', '{\"7\":210}', 2, 22, 1, '2025-11-26 00:02:22', '2025-11-26 21:58:31'),
(5, 22, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 192.27, \"3\": 576.81, \"6\": 1153.62, \"26\": 4999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(6, 13, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 76.12, \"3\": 228.35, \"6\": 456.69, \"26\": 1979.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(7, 6, '[1,3,6,26]', '[{\"key\":\"lunch\",\"label\":\"Lunch\",\"window\":\"\"},{\"key\":\"dinner\",\"label\":\"Dinner\",\"window\":\"\"}]', NULL, '{\"1\":134.58,\"3\":403.73,\"6\":807.46,\"26\":3499}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-25 07:05:23'),
(8, 11, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 96.12, \"3\": 288.35, \"6\": 576.69, \"26\": 2499.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(9, 17, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 307.65, \"3\": 922.96, \"6\": 1845.92, \"26\": 7999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(10, 38, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 250.0, \"3\": 750.0, \"6\": 1500.0, \"26\": 6500.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(11, 43, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 250.0, \"3\": 750.0, \"6\": 1500.0, \"26\": 6500.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(12, 30, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 307.69, \"3\": 923.08, \"6\": 1846.15, \"26\": 8000.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(13, 23, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 115.35, \"3\": 346.04, \"6\": 692.08, \"26\": 2999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(14, 9, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 57.65, \"3\": 172.96, \"6\": 345.92, \"26\": 1499.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(15, 14, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 147.0, \"3\": 441.0, \"6\": 882.0, \"26\": 3822.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(16, 27, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 153.81, \"3\": 461.42, \"6\": 922.85, \"26\": 3999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(17, 35, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 192.27, \"3\": 576.81, \"6\": 1153.62, \"26\": 4999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(18, 34, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 192.27, \"3\": 576.81, \"6\": 1153.62, \"26\": 4999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(19, 41, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 192.27, \"3\": 576.81, \"6\": 1153.62, \"26\": 4999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(20, 10, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 76.12, \"3\": 228.35, \"6\": 456.69, \"26\": 1979.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(21, 28, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 192.27, \"3\": 576.81, \"6\": 1153.62, \"26\": 4999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(22, 8, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 134.58, \"3\": 403.73, \"6\": 807.46, \"26\": 3499.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(23, 15, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 149.0, \"3\": 447.0, \"6\": 894.0, \"26\": 3874.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(24, 44, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 153.81, \"3\": 461.42, \"6\": 922.85, \"26\": 3999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(25, 18, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 1199.0, \"3\": 3597.0, \"6\": 7194.0, \"26\": 31174.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(26, 39, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 192.27, \"3\": 576.81, \"6\": 1153.62, \"26\": 4999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(27, 36, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 192.27, \"3\": 576.81, \"6\": 1153.62, \"26\": 4999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(28, 7, '[1,3,6,26]', '[{\"key\":\"lunch\",\"label\":\"Lunch\",\"window\":\"\"},{\"key\":\"dinner\",\"label\":\"Dinner\",\"window\":\"\"}]', NULL, '{\"1\":192.27,\"3\":576.81,\"6\":1153.62,\"26\":4999}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-25 07:07:36'),
(29, 19, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 399.96, \"3\": 1199.88, \"6\": 2399.77, \"26\": 10399.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(30, 16, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 499.0, \"3\": 1497.0, \"6\": 2994.0, \"26\": 12974.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(31, 26, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 192.27, \"3\": 576.81, \"6\": 1153.62, \"26\": 4999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(32, 24, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 192.27, \"3\": 576.81, \"6\": 1153.62, \"26\": 4999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(33, 29, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 1999.0, \"3\": 5997.0, \"6\": 11994.0, \"26\": 51974.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(34, 33, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 192.27, \"3\": 576.81, \"6\": 1153.62, \"26\": 4999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(35, 32, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 333.17, \"3\": 999.5, \"6\": 1999.0, \"26\": 8662.33}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(36, 37, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 250.0, \"3\": 750.0, \"6\": 1500.0, \"26\": 6500.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(37, 5, '[1,3,6,26]', '[{\"key\":\"lunch\",\"label\":\"Lunch\",\"window\":\"\"},{\"key\":\"dinner\",\"label\":\"Dinner\",\"window\":\"\"}]', '[\"SAT\",\"SUN\"]', '{\"1\":57.65,\"3\":172.96,\"6\":345.92,\"26\":1499}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-25 07:03:15'),
(38, 21, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 153.81, \"3\": 461.42, \"6\": 922.85, \"26\": 3999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(39, 25, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 115.35, \"3\": 346.04, \"6\": 692.08, \"26\": 2999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(40, 31, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 211.5, \"3\": 634.5, \"6\": 1269.0, \"26\": 5499.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(41, 20, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 250.0, \"3\": 750.0, \"6\": 1500.0, \"26\": 6500.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(42, 40, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 250.0, \"3\": 750.0, \"6\": 1500.0, \"26\": 6500.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(43, 46, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 192.27, \"3\": 576.81, \"6\": 1153.62, \"26\": 4999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(44, 42, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 250.0, \"3\": 750.0, \"6\": 1500.0, \"26\": 6500.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(45, 12, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 57.65, \"3\": 172.96, \"6\": 345.92, \"26\": 1499.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(46, 47, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 153.81, \"3\": 461.42, \"6\": 922.85, \"26\": 3999.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11'),
(47, 45, '[1, 3, 6, 26]', '[{\"key\": \"lunch\", \"label\": \"Lunch\"}, {\"key\": \"dinner\", \"label\": \"Dinner\"}]', '[\"sunday\"]', '{\"1\": 134.58, \"3\": 403.73, \"6\": 807.46, \"26\": 3499.0}', 0, 22, 1, '2025-12-24 18:04:11', '2025-12-24 18:04:11');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plan_nutrition`
--

CREATE TABLE `subscription_plan_nutrition` (
  `id` int(10) UNSIGNED NOT NULL,
  `subscription_plan_id` int(10) UNSIGNED NOT NULL,
  `calories_kcal` int(11) DEFAULT NULL,
  `protein_g` decimal(6,2) DEFAULT NULL,
  `carbs_g` decimal(6,2) DEFAULT NULL,
  `fats_g` decimal(6,2) DEFAULT NULL,
  `fibre_g` decimal(6,2) DEFAULT NULL,
  `sugar_g` decimal(6,2) DEFAULT NULL,
  `sodium_mg` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_plan_nutrition`
--

INSERT INTO `subscription_plan_nutrition` (`id`, `subscription_plan_id`, `calories_kcal`, `protein_g`, `carbs_g`, `fats_g`, `fibre_g`, `sugar_g`, `sodium_mg`, `notes`) VALUES
(2, 2, 450, 18.00, 45.00, 14.00, 10.00, 8.00, 550, 'Typical salad bowl with mixed veggies and lean protein.'),
(3, 3, 220, 4.00, 48.00, 2.00, 3.00, 32.00, 80, 'Cold-pressed vegetable + fruit juice.'),
(4, 4, 500, 30.00, 20.00, 25.00, 6.00, 8.00, 500, ''),
(5, 22, 140, 2.00, 34.00, NULL, 2.00, 26.00, 35, 'Estimated per serving/day. Update using your recipe and portion size.'),
(6, 13, 140, 2.00, 34.00, NULL, 2.00, 26.00, 35, 'Estimated per serving/day. Update using your recipe and portion size.'),
(7, 6, 450, 45.00, 12.00, 20.00, 4.00, 4.00, 900, 'Estimated per serving/day. Update using your recipe and portion size.'),
(8, 11, 480, 26.00, 22.00, 30.00, 6.00, 6.00, 750, 'Estimated per serving/day. Update using your recipe and portion size.'),
(9, 17, 780, 45.00, 90.00, 22.00, 6.00, 5.00, 1200, 'Estimated per serving/day. Update using your recipe and portion size.'),
(10, 38, 320, 3.00, 80.00, 1.00, 7.00, 55.00, 15, 'Estimated per serving/day. Update using your recipe and portion size.'),
(11, 43, 320, 3.00, 80.00, 1.00, 7.00, 55.00, 15, 'Estimated per serving/day. Update using your recipe and portion size.'),
(12, 30, 280, 3.00, 70.00, 1.00, 6.00, 45.00, 15, 'Estimated per serving/day. Update using your recipe and portion size.'),
(13, 23, 220, 3.00, 55.00, 1.00, 5.00, 38.00, 15, 'Estimated per serving/day. Update using your recipe and portion size.'),
(14, 9, 220, 3.00, 55.00, 1.00, 5.00, 38.00, 15, 'Estimated per serving/day. Update using your recipe and portion size.'),
(15, 14, 220, 3.00, 55.00, 1.00, 5.00, 38.00, 15, 'Estimated per serving/day. Update using your recipe and portion size.'),
(16, 27, 280, 3.00, 70.00, 1.00, 6.00, 45.00, 15, 'Estimated per serving/day. Update using your recipe and portion size.'),
(17, 35, 720, 28.00, 85.00, 28.00, 10.00, 18.00, 980, 'Estimated per serving/day. Update using your recipe and portion size.'),
(18, 34, 130, 2.00, 30.00, NULL, 2.00, 24.00, 35, 'Estimated per serving/day. Update using your recipe and portion size.'),
(19, 41, 620, 32.00, 55.00, 26.00, 7.00, 10.00, 950, 'Estimated per serving/day. Update using your recipe and portion size.'),
(20, 10, 300, 12.00, 45.00, 8.00, 6.00, 18.00, 180, 'Estimated per serving/day. Update using your recipe and portion size.'),
(21, 28, 130, 2.00, 30.00, NULL, 2.00, 24.00, 35, 'Estimated per serving/day. Update using your recipe and portion size.'),
(22, 8, 650, 28.00, 75.00, 20.00, 8.00, 7.00, 1100, 'Estimated per serving/day. Update using your recipe and portion size.'),
(23, 15, 650, 28.00, 75.00, 20.00, 8.00, 7.00, 1100, 'Estimated per serving/day. Update using your recipe and portion size.'),
(24, 44, 720, 28.00, 85.00, 28.00, 10.00, 18.00, 980, 'Estimated per serving/day. Update using your recipe and portion size.'),
(25, 18, 360, 16.00, 35.00, 14.00, 8.00, 10.00, 540, 'Estimated per serving/day. Update using your recipe and portion size.'),
(26, 39, 380, 12.00, 60.00, 10.00, 8.00, 12.00, 220, 'Estimated per serving/day. Update using your recipe and portion size.'),
(27, 36, 720, 28.00, 85.00, 28.00, 10.00, 18.00, 980, 'Estimated per serving/day. Update using your recipe and portion size.'),
(28, 7, 360, 14.00, 52.00, 10.00, 7.00, 16.00, 200, 'Estimated per serving/day. Update using your recipe and portion size.'),
(29, 19, 520, 28.00, 24.00, 32.00, 7.00, 6.00, 800, 'Estimated per serving/day. Update using your recipe and portion size.'),
(30, 16, 520, 28.00, 24.00, 32.00, 7.00, 6.00, 800, 'Estimated per serving/day. Update using your recipe and portion size.'),
(31, 26, 340, 18.00, 28.00, 16.00, 9.00, 8.00, 520, 'Estimated per serving/day. Update using your recipe and portion size.'),
(32, 24, 650, 28.00, 75.00, 20.00, 8.00, 7.00, 1100, 'Estimated per serving/day. Update using your recipe and portion size.'),
(33, 29, 560, 18.00, 65.00, 24.00, 7.00, 8.00, 900, 'Estimated per serving/day. Update using your recipe and portion size.'),
(34, 33, 700, 35.00, 70.00, 25.00, 7.00, 6.00, 1200, 'Estimated per serving/day. Update using your recipe and portion size.'),
(35, 32, 700, 35.00, 70.00, 25.00, 7.00, 6.00, 1200, 'Estimated per serving/day. Update using your recipe and portion size.'),
(36, 37, 340, 18.00, 28.00, 16.00, 9.00, 8.00, 520, 'Estimated per serving/day. Update using your recipe and portion size.'),
(37, 5, 340, 18.00, 28.00, 16.00, 9.00, 8.00, 520, 'Estimated per serving/day. Update using your recipe and portion size.'),
(38, 21, 340, 18.00, 28.00, 16.00, 9.00, 8.00, 520, 'Estimated per serving/day. Update using your recipe and portion size.'),
(39, 25, 340, 18.00, 28.00, 16.00, 9.00, 8.00, 520, 'Estimated per serving/day. Update using your recipe and portion size.'),
(40, 31, 300, 26.00, 20.00, 12.00, 2.00, 12.00, 220, 'Estimated per serving/day. Update using your recipe and portion size.'),
(41, 20, 650, 28.00, 75.00, 20.00, 8.00, 7.00, 1100, 'Estimated per serving/day. Update using your recipe and portion size.'),
(42, 40, 420, 20.00, 45.00, 16.00, 8.00, 10.00, 650, 'Estimated per serving/day. Update using your recipe and portion size.'),
(43, 46, 300, 12.00, 45.00, 8.00, 6.00, 18.00, 180, 'Estimated per serving/day. Update using your recipe and portion size.'),
(44, 42, 340, 18.00, 28.00, 16.00, 9.00, 8.00, 520, 'Estimated per serving/day. Update using your recipe and portion size.'),
(45, 12, 340, 18.00, 28.00, 16.00, 9.00, 8.00, 520, 'Estimated per serving/day. Update using your recipe and portion size.'),
(46, 47, 340, 18.00, 28.00, 16.00, 9.00, 8.00, 520, 'Estimated per serving/day. Update using your recipe and portion size.'),
(47, 45, 340, 18.00, 28.00, 16.00, 9.00, 8.00, 520, 'Estimated per serving/day. Update using your recipe and portion size.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_customers_username` (`username`),
  ADD UNIQUE KEY `uniq_customers_email` (`email`);

--
-- Indexes for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_customer_addresses_customer_id` (`customer_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_orders_order_number` (`order_number`),
  ADD KEY `idx_orders_customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_items_order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_products_slug` (`slug`),
  ADD UNIQUE KEY `uq_products_sku` (`sku`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sub_plan_id` (`subscription_plan_id`),
  ADD KEY `idx_sub_user` (`user_id`),
  ADD KEY `idx_sub_customer` (`customer_id`);

--
-- Indexes for table `subscription_deliveries`
--
ALTER TABLE `subscription_deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_deliv_date` (`delivery_date`),
  ADD KEY `idx_deliv_status` (`status`),
  ADD KEY `idx_deliv_sub` (`subscription_id`),
  ADD KEY `fk_deliv_plan` (`subscription_plan_id`),
  ADD KEY `idx_delivery_selected_plan` (`selected_plan_id`),
  ADD KEY `idx_delivery_selected_menu` (`selected_menu_item_id`),
  ADD KEY `idx_delivery_choice` (`is_choice_based`,`delivery_date`);

--
-- Indexes for table `subscription_menu_items`
--
ALTER TABLE `subscription_menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_subscription_slug` (`slug`);

--
-- Indexes for table `subscription_plan_choices`
--
ALTER TABLE `subscription_plan_choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_subscription_plan_id` (`subscription_plan_id`),
  ADD KEY `idx_option_plan_id` (`option_plan_id`),
  ADD KEY `idx_option_menu_item_id` (`option_menu_item_id`);

--
-- Indexes for table `subscription_plan_choice_pool`
--
ALTER TABLE `subscription_plan_choice_pool`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_parent_plan` (`parent_plan_id`),
  ADD KEY `idx_option_plan` (`option_plan_id`),
  ADD KEY `idx_option_menu` (`option_menu_item_id`);

--
-- Indexes for table `subscription_plan_config`
--
ALTER TABLE `subscription_plan_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_config_plan_id` (`subscription_plan_id`);

--
-- Indexes for table `subscription_plan_nutrition`
--
ALTER TABLE `subscription_plan_nutrition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nutrition_plan_id` (`subscription_plan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
