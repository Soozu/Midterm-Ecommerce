-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2024 at 10:54 AM
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
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','purchased','saved') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `quantity`, `added_at`, `status`) VALUES
(17, 4, 11, 4, '2024-06-18 09:57:24', 'active'),
(18, 4, 12, 1, '2024-06-18 10:28:56', 'active'),
(36, 3, 10, 2, '2024-06-21 06:44:10', 'active'),
(37, 4, 10, 1, '2024-06-21 08:22:02', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Make Up'),
(2, 'Skin Care'),
(3, 'Other Categories');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','shipped','in_transit','delivered') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `shipping_address` varchar(255) NOT NULL,
  `shipping_city` varchar(100) NOT NULL,
  `shipping_postal_code` varchar(20) NOT NULL,
  `shipping_country` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `created_at`, `updated_at`, `shipping_address`, `shipping_city`, `shipping_postal_code`, `shipping_country`) VALUES
(1, 3, 100.00, 'pending', '2024-06-21 05:36:57', '2024-06-21 05:36:57', '', '', '', ''),
(2, 3, 3148.00, 'pending', '2024-06-21 05:47:54', '2024-06-21 05:47:54', '', '', '', ''),
(3, 3, 20.00, 'pending', '2024-06-21 05:52:04', '2024-06-21 05:52:04', '', '', '', ''),
(4, 3, 20.00, 'pending', '2024-06-21 05:53:46', '2024-06-21 05:53:46', '', '', '', ''),
(5, 3, 20.00, 'pending', '2024-06-21 05:54:12', '2024-06-21 05:54:12', '', '', '', ''),
(6, 3, 20.00, 'pending', '2024-06-21 05:57:26', '2024-06-21 05:57:26', '', '', '', ''),
(7, 4, 60000.00, 'pending', '2024-06-21 08:51:10', '2024-06-21 08:51:10', 'BACOOR', 'CAVITE', '4102', 'PHILIPPINES');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_shipping`
--

CREATE TABLE `order_shipping` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_shipping`
--

INSERT INTO `order_shipping` (`id`, `order_id`, `address`, `city`, `postal_code`, `country`) VALUES
(1, 7, 'BACOOR', 'CAVITE', '4102', 'PHILIPPINES');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`) VALUES
(1, 1, '19a1d0a81a438e0a4da236951a79fbb1f8e630427cbdece46b54e9322c341125aa53214ba756d5a0a96abda81392c388104d', '2024-06-11 13:46:53'),
(2, 1, '5a2cf0257ee8af06e2db9973655d8e3fdc28955b3e60d4c06b44f3cacd1a944052119135a5e78af18ef91d598f61c1adf5b3', '2024-06-11 14:16:24'),
(3, 1, '4dc1cae6bb63104b9b3d33c8de78c8fab659b866963f82057b2a52ab8cbd1ebcf8af804902a67777a77c85fdd8dd97617b49', '2024-06-11 14:16:26'),
(4, 1, 'b4d5503c1c9448fcb28d3c735885eed7307e945327570d94c97abcd908fff07743399a1a207c329e4f4276f19566bbfe0506', '2024-06-11 14:18:01'),
(5, 1, '2023008edc2e238b807f3e5c9a0834dc64293590bd2b71927854cfb9a0504e16a242cc4709e6fa0bc7fc3ce91b877b0a670d', '2024-06-11 14:19:07'),
(6, 1, '02567123e60c9e159ab09e3aae2b89ae702ea162127b6716ac55b071d87e72737df45315f85fc9133b2ff9f17bb46883ded7', '2024-06-11 14:22:23'),
(7, 4, '882a496f03d073ce26469247cb3443ad1a4215b46e6a4a869601c1abff6692f1e148457f6b6e6f4a3cb0756fcc41e15ee2e9', '2024-06-11 14:24:16'),
(8, 4, 'e98f47d989f72304e41501671231ae2e5891a3e0a3bdd03486dabf6aa829397f440a77fe2bcb603824386cecba7b1ee5164a', '2024-06-11 15:40:43'),
(9, 1, 'be95824ff1ca4acaac3c02eb4572b5a20ab7e1d1d799a603945a0a55414cb921fc0db384c3f9f49bf36d92b560b7d567f878', '2024-06-11 19:39:57'),
(10, 1, '9df0245178ae0d87f1f3beb3306a971b7ebbe3ab91d7cbe5db5d6978e9bcc332eb7287e75be484f2f59861816fda54d3fa1d', '2024-06-11 19:48:33'),
(11, 1, 'c93fd4c6ea0ead291ff7056bda14b83d309df6a0fb735880483dc32e41aff68ad165c0e6efd4739c394fc29ba76deb31a25b', '2024-06-11 19:50:23'),
(12, 1, '03cd1ac2e3e563ecece7ee1626b44470390e201e56cd365f5212d82ce4db2deac3d7b994e89701e82b49e15fd09943ec796a', '2024-06-11 19:51:03'),
(13, 1, '156b83c552fa67bdeeaa179f12c02b97b114bf3d6e6413a5de81b34a68f3a208f0d613eff27688bb4f30ba3b0dca6fd1c3b5', '2024-06-11 19:51:27'),
(16, 1, 'b070931938b24043ca216941e3492ce6f61da5238f09d01695909ea45286a7a4fa149bc07a8ed64f82f87edc49c5071bce6b', '2024-06-12 08:39:37'),
(18, 1, 'c29bc8c0a8b3535d861921220b965426717dcc184d93158494feb00c2e7a28298d2189d374848e6380f52a8eb361496c0a70', '2024-06-16 13:36:58');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `image`, `stock_quantity`, `status`, `created_at`, `updated_at`) VALUES
(10, 'qweqweqw', 'Christian', 10000.00, 1, 'product1.jpg', 18, 'active', '2024-06-11 05:47:22', '2024-06-21 08:22:02'),
(11, 'King', 'Christian', 10000.00, 1, 'product1.jpg', 0, 'active', '2024-06-11 05:47:22', '2024-06-21 06:55:24'),
(12, 'Pacifico', 'Christian', 10000.00, 1, 'product1.jpg', 20, 'active', '2024-06-11 05:47:22', '2024-06-11 06:00:22'),
(13, 'LOLking', 'Christian', 10000.00, 1, 'product1.jpg', 20, 'active', '2024-06-11 05:47:22', '2024-06-11 06:00:22'),
(17, '123', '213123123', 12312.00, 1, 'product1.jpg', 20, 'active', '2024-06-11 05:47:22', '2024-06-11 06:00:22'),
(18, 'Pogi', '123', 123.00, 1, 'product1.jpg', 20, 'active', '2024-06-11 05:47:22', '2024-06-11 06:00:22'),
(19, '123', '2313', 123.00, 1, 'product1.jpg', 20, 'active', '2024-06-11 05:47:22', '2024-06-11 06:00:22'),
(20, '12323123', '3123123', 2.00, 1, 'product4.jpg', 20, 'active', '2024-06-11 05:47:22', '2024-06-11 06:00:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `role` enum('customer','seller','admin') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `salt`, `email`, `created_at`, `last_login`, `status`, `role`) VALUES
(1, 'king', '$2y$10$PI9rmBmWgLetuA6frMqoJusEY5w4fN4rEhHNf1ka.tbWN61UlTdV6', NULL, 'kingpacifico009@gmail.com', '2024-04-02 05:50:13', '2024-06-14 11:45:19', 'active', 'customer'),
(2, 'admin', '$2y$10$BJPQUnPnufzeR.6XZgdOLeZz7idv2052Td28.6mM1INDcsx7ZXkeW$2y$10$PI9rmBmWgLetuA6frMqoJusEY5w4fN4rEhHNf1ka.tbWN61UlTdV6', NULL, 'admin@example.com', '2024-04-09 06:05:00', NULL, 'active', 'admin'),
(3, 'king23', '$2y$10$kygSCBJdCiTLefknVvfs0.N5czSyJ5TPE0.m3OtGZefn8yS8rbMdm', NULL, 'king@gmail.com', '2024-06-02 10:27:46', '2024-06-21 07:28:17', 'active', 'customer'),
(4, 'soozu', '$2y$10$MUZXa5Mp3lTaGjqybhMNHeUr3dhA5XPIkdJfmXmmtxEZOQAh0caH6', NULL, 'kingpacifico0021@gmail.com', '2024-06-11 05:24:00', '2024-06-21 07:26:20', 'active', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `user_favorites`
--

CREATE TABLE `user_favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_favorites`
--

INSERT INTO `user_favorites` (`id`, `user_id`, `product_id`) VALUES
(2, 3, 10),
(3, 3, 11),
(4, 4, 10),
(5, 3, 12);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_shipping`
--
ALTER TABLE `order_shipping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_shipping`
--
ALTER TABLE `order_shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_favorites`
--
ALTER TABLE `user_favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `order_shipping`
--
ALTER TABLE `order_shipping`
  ADD CONSTRAINT `order_shipping_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_category_products` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD CONSTRAINT `user_favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
