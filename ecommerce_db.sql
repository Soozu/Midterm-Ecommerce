-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2024 at 04:34 AM
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
(73, 5, 27, 1, '2024-06-30 13:54:57', 'purchased'),
(74, 5, 27, 1, '2024-06-30 13:55:27', 'purchased'),
(75, 1, 28, 1, '2024-06-30 13:56:42', 'purchased'),
(76, 1, 29, 1, '2024-06-30 14:09:05', 'purchased'),
(77, 1, 30, 1, '2024-07-01 08:25:55', 'purchased'),
(78, 1, 31, 1, '2024-07-01 08:33:20', 'purchased'),
(79, 1, 28, 1, '2024-07-01 22:49:59', '');

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
-- Table structure for table `delivered_orders`
--

CREATE TABLE `delivered_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `shipping_address` varchar(255) DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_postal_code` varchar(20) DEFAULT NULL,
  `shipping_country` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivered_orders`
--

INSERT INTO `delivered_orders` (`id`, `user_id`, `total`, `status`, `created_at`, `updated_at`, `shipping_address`, `shipping_city`, `shipping_postal_code`, `shipping_country`) VALUES
(2, 1, 249.00, 'delivered', '2024-07-01 08:33:26', '2024-07-01 08:33:53', 'BACOOR', 'CAVITE', '4102', 'PHILIPPINES');

-- --------------------------------------------------------

--
-- Table structure for table `delivered_order_items`
--

CREATE TABLE `delivered_order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivered_order_items`
--

INSERT INTO `delivered_order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(2, 2, 31, 1, 249.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','shipped','in_transit','delivered','paid','Cancel Order','refund item') NOT NULL DEFAULT 'pending',
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
(37, 1, 249.00, 'delivered', '2024-07-01 08:33:26', '2024-07-01 08:33:53', 'BACOOR', 'CAVITE', '4102', 'PHILIPPINES'),
(38, 1, 149.00, 'paid', '2024-07-01 22:50:06', '2024-07-01 22:51:43', 'BACOOR', '123', '123', '123');

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

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(29, 37, 31, 1, 249.00),
(30, 38, 28, 1, 149.00);

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
(18, 1, 'c29bc8c0a8b3535d861921220b965426717dcc184d93158494feb00c2e7a28298d2189d374848e6380f52a8eb361496c0a70', '2024-06-16 13:36:58'),
(19, 1, 'eafba72dc36435e47c048f136250fe7e4449593c33a3958ec42d11a38a6335c568b0689e1d126db736c945b6775969670ee8', '2024-07-01 18:42:26'),
(20, 1, 'e5b91b593e8dc4fda2dc10acd96597edc8d12bcf60863b351b018b603a1e0c037233368d2faa92980d0a16f1cef7e7421f26', '2024-07-01 18:46:16'),
(21, 1, 'cfdc74ee6f30e6685c7492746b560ae3f466bc53a5cb6b212922eed79740810d4be15a783f0603d29549a32b21ba177109f4', '2024-07-01 18:46:59'),
(22, 1, 'a8999ee0c6846865e132782823fb8f2a1e854c38cfb4716d69ebdb7bf9eb27c424fd0c35dd3243330fadb9f7afd3471eb110', '2024-07-01 18:53:19'),
(23, 1, 'd75f0519dd517087611b44a0d20bb2e93e2a8e5475e7fdf5adc04e1826a554be7da3492ba3ccc873bed01f6e7438edce8570', '2024-07-01 18:55:05');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `avg_rating` float DEFAULT 0,
  `num_sold` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `image`, `stock_quantity`, `status`, `created_at`, `updated_at`, `avg_rating`, `num_sold`) VALUES
(27, 'Brushes', 'Tools used to apply makeup products. They come in various shapes and sizes, each designed for specific tasks like applying foundation, blending eye shadow, or contouring.', 299.00, 1, 'Brushes(product1).jpeg', 18, NULL, '2024-06-30 11:33:21', '2024-07-01 08:22:22', 0, 0),
(28, 'Mascara', 'A cosmetic used to enhance the eyelashes by darkening, lengthening, and thickening them. It is available in different formulas like waterproof and volumizing.', 149.00, 1, 'Mascara(product8).jpeg', 18, 'active', '2024-06-30 11:35:14', '2024-07-01 22:49:59', 0, 0),
(29, 'Lip Gloss', 'A shiny, often translucent cosmetic applied to the lips for a glossy finish. It can be worn alone or over lipstick for added shine.', 479.00, 1, 'LipGloss(product5).jpeg', 19, 'active', '2024-06-30 11:35:55', '2024-07-01 08:22:41', 0, 0),
(30, 'Lip Liner', 'A pencil-like product used to outline the lips, preventing lipstick from feathering and providing a defined shape.', 399.00, 1, 'LipLiner(product6).png', 19, 'active', '2024-06-30 11:36:53', '2024-07-01 08:25:55', 0, 0),
(31, 'Eye Liner', 'A makeup product used to define the eyes. It can be applied along the lash line or waterline and comes in forms such as pencil, liquid, gel, and powder.', 249.00, 1, 'EyeLiner(product2).png', 19, 'active', '2024-06-30 11:37:43', '2024-07-01 08:33:20', 0, 0),
(32, 'Lip Stick', 'A cosmetic product applied to the lips to add color and texture. It is available in various finishes like matte, satin, and glossy.', 250.00, 1, 'Lipstick(product7).jpeg', 20, 'active', '2024-06-30 11:38:19', '2024-07-01 08:22:50', 0, 0),
(33, 'Eye Shadow', 'A cosmetic applied to the eyelids and under the eyes to add color, depth, and dimension. It comes in various forms such as powder, cream, and liquid.', 799.00, 1, 'EyeShadow(product3).jpeg', 200, 'active', '2024-06-30 11:39:01', '2024-07-01 08:22:54', 0, 0),
(34, 'Foundation Cream', 'A skin-colored makeup applied to the face to create an even, uniform complexion, cover imperfections, and sometimes alter skin tone. It comes in liquid, cream, powder, and stick forms.', 249.00, 1, 'FoundationCream(product4).jpeg', 20, NULL, '2024-06-30 11:39:56', '2024-06-30 11:39:56', 0, 0),
(35, 'Eye Cream', 'A specialized cream formulated for the delicate skin around the eyes. It targets concerns like puffiness, dark circles, and fine lines, providing hydration and nourishment.', 179.00, 2, 'EyeCream(product1).jpeg', 20, 'active', '2024-06-30 11:40:34', '2024-06-30 11:41:59', 0, 0),
(36, 'Toner', 'A liquid skincare product used after cleansing to help remove any remaining impurities, balance the skin\'s pH, and prepare the skin for subsequent products like serums and moisturizers.', 329.00, 2, 'Toner(product2).jpeg', 20, NULL, '2024-06-30 11:41:48', '2024-06-30 11:41:48', 0, 0),
(37, 'Moisturizer', 'A skincare product that hydrates and locks in moisture in the skin. It helps to maintain the skin\'s barrier function, keeping it soft, smooth, and supple. Moisturizers are available in different formulations like creams, lotions, gels, and ointments for various skin types.', 129.00, 2, 'Moisturizer(product3).jpeg', 20, NULL, '2024-06-30 11:53:57', '2024-06-30 11:53:57', 0, 0),
(38, 'Facial Cleanser', 'A product used to remove dirt, oil, makeup, and impurities from the skin. It comes in various forms such as gel, cream, foam, oil, and micellar water, catering to different skin types and concerns.', 299.00, 2, 'FacialCleanser(product4).jpeg', 20, NULL, '2024-06-30 11:54:32', '2024-06-30 11:54:32', 0, 0),
(39, 'Milky Bleaching Whipped Cream', 'All in one whitening cream with exfoliant for face and body. With micro beads to physically exfoliate the skin. Improves skin texture, with instant whitening effect, infused with powerful whitening active ingredients, helps to fade stretch marks, whitens dark areas of your skin such as armpit, butt, knees, elbows, gives a youthful glow with regular use.', 279.00, 2, 'MilkyBleachingWhippedCream(product5).jpeg', 20, NULL, '2024-06-30 11:55:15', '2024-06-30 11:55:15', 0, 0),
(40, 'Niacinamide Serum and Premium Sunscreen', '- Anti-inflamatory\r\n- Anti-aging\r\n- Anti oxidant\r\n- Brightens the skin\r\n- Helps boost tone & lighten dark spots\r\n- Reduced pore appearance\r\n- Shields your skin from developing\r\nwrinkles, fine lines & hyperpigmentation\r\n- Protects our skin from harmful rays\r\n- Improves skin texture', 295.00, 2, 'NiacinamideSerumandPremiumSunscreen(product6).jpeg', 20, NULL, '2024-06-30 11:56:18', '2024-06-30 11:56:18', 0, 0),
(41, 'Pure Kojic Whitening Soap', '- Reducing hyperpigmentation, dark   spots, and age spots.\r\n- May fade the appearance of scars and acne marks. \r\n- Act as a gentle exfoliator\r\n- Removing dead skin cells and promoting cell renewal.', 55.00, 2, 'PureKojicWhiteningSoap(product7).jpeg', 20, NULL, '2024-06-30 11:57:10', '2024-06-30 11:57:10', 0, 0),
(42, 'Glass Skin Foam Facial Wash', 'A lightweight, airy facial cleanser that creates a rich foam. It\'s designed to gently cleanse the skin, removing impurities and excess oil without stripping moisture.', 199.00, 2, 'GlassSkinFoamFacialWash(product8).jpeg', 20, NULL, '2024-06-30 11:58:15', '2024-06-30 11:58:15', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`, `order_id`) VALUES
(10, 27, 5, 5, 'Testing', '2024-06-30 13:57:21', 32),
(11, 29, 1, 5, '123', '2024-06-30 14:10:42', 35),
(12, 31, 1, 5, 'coool\r\n', '2024-07-01 08:42:39', 0),
(13, 31, 1, 4, '123', '2024-07-01 22:59:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_id` int(11) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `refunds`
--

INSERT INTO `refunds` (`id`, `user_id`, `product_id`, `reason`, `status`, `created_at`, `order_id`, `token`, `product_image`, `contact_number`) VALUES
(22, 1, 31, 'e', 'pending', '2024-07-02 02:33:36', 2, NULL, NULL, NULL);

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
(3, 'king23', '$2y$10$kygSCBJdCiTLefknVvfs0.N5czSyJ5TPE0.m3OtGZefn8yS8rbMdm', NULL, 'king@gmail.com', '2024-06-02 10:27:46', '2024-06-30 06:27:16', 'active', 'customer'),
(4, 'soozu', '$2y$10$MUZXa5Mp3lTaGjqybhMNHeUr3dhA5XPIkdJfmXmmtxEZOQAh0caH6', NULL, 'kingpacifico0021@gmail.com', '2024-06-11 05:24:00', '2024-06-30 06:27:49', 'active', 'admin'),
(5, 'admin', '$2y$10$TlOaovFkxxsxTNucUQBpeerYwIzEgg.18dWEJddCBIiwWPWAKTFUO', NULL, 'christian.pacifico@cvsu.edu.ph', '2024-06-30 09:21:40', NULL, 'active', 'admin'),
(6, 'pacifico123', '$2y$10$hxc4YMLWkdVf42ebzWly/.zHgkfrqRbutPSJ1FE8z6U5X2lJeDH0m', NULL, 'pacificoc77@gmail.com', '2024-06-30 09:28:25', NULL, 'active', 'customer');

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
-- Indexes for table `delivered_orders`
--
ALTER TABLE `delivered_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivered_order_items`
--
ALTER TABLE `delivered_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

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
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `delivered_orders`
--
ALTER TABLE `delivered_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `delivered_order_items`
--
ALTER TABLE `delivered_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_shipping`
--
ALTER TABLE `order_shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_favorites`
--
ALTER TABLE `user_favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `delivered_order_items`
--
ALTER TABLE `delivered_order_items`
  ADD CONSTRAINT `delivered_order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `delivered_orders` (`id`),
  ADD CONSTRAINT `delivered_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

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
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `refunds_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

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
