-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 19, 2024 at 05:16 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wardrobe`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE IF NOT EXISTS `cart_items` (
  `cart_item_id` int NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `product_id` int NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cart_item_id`),
  KEY `user_id` (`UserID`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL,
  `category_name` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `parent_id`, `category_name`) VALUES
(1, 0, 'Women'),
(2, 0, 'Men'),
(3, 1, 'Dresses'),
(4, 1, 'Tops'),
(17, 2, 'Shorts'),
(8, 2, 'T-Shirts'),
(16, 2, 'Pants'),
(14, 2, 'Shirts'),
(12, 1, 'Moods'),
(13, 1, 'Batik');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) DEFAULT 'Pending',
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `ItemTot` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `new_price` decimal(10,2) DEFAULT NULL,
  `category_id` int NOT NULL,
  `subcategory_id` int NOT NULL,
  `sizes` varchar(255) DEFAULT NULL,
  `stock_quantity` int NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `image_url2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `image_url3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_new_arrival` tinyint(1) DEFAULT '0',
  `on_sale` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`product_id`),
  KEY `category_id` (`category_id`),
  KEY `subcategory_id` (`subcategory_id`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `new_price`, `category_id`, `subcategory_id`, `sizes`, `stock_quantity`, `image_url`, `image_url2`, `image_url3`, `is_new_arrival`, `on_sale`) VALUES
(45, 'V-Neck Floral Baggy Top', 3990.00, 0.00, 1, 4, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a18fb4356c-V-Neck Floral Baggy Top 1.webp', 'IMG/products/669a18fb436c7-V-Neck Floral Baggy Top 2.webp', 'IMG/products/669a18fb43802-V-Neck Floral Baggy Top 3.webp', 0, 0),
(34, 'Batik Pin Tucked Top', 4850.00, 0.00, 1, 4, 'XS,S,M,L,XL,XXL', 13, 'IMG/products/669a13ec3f1e5-Batik Pin Tucked Top 1.webp', 'IMG/products/669a13ec3f399-Batik Pin Tucked Top 2.webp', 'IMG/products/669a13ec3f4b6-Batik Pin Tucked Top 3.webp', 1, 0),
(66, 'Elasticated Waist Pant', 3150.00, 0.00, 1, 12, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a746f5ad93-Elasticated Waist Pant 1.webp', 'IMG/products/669a746f5af12-Elasticated Waist Pant 2.webp', 'IMG/products/669a746f5b0fb-Elasticated Waist Pant 3.webp', 0, 0),
(43, 'Oversized L/S T-Shirt', 3490.00, 0.00, 2, 8, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a7e267b8fc-Oversized Shirt 1.webp', 'IMG/products/669a7e267ba78-Oversized Shirt 2.webp', 'IMG/products/669a7e267bbaf-Oversized T-Shirt 3.webp', 0, 0),
(44, 'Party Wear L/S Shirt', 4490.00, 0.00, 2, 14, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a7e84cd3c0-Party Wear Shirt (Deep Purple).webp', 'IMG/products/669a7e84cd590-Party Wear Shirt (Deep Purple) 2.webp', '', 1, 0),
(38, 'V-Neck Batik Kaftan Dress', 5000.00, 0.00, 1, 3, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a1204977ae-V-Neck Batik Kaftan Dress 1.webp', 'IMG/products/669a1204978fb-V-Neck Batik Kaftan Dress 2.webp', 'IMG/products/669a1204979fe-V-Neck Batik Kaftan Dress 3.webp', 1, 0),
(39, 'Two Side Slit Batik Dress', 4500.00, 0.00, 1, 3, 'XS,S,M,L,XL,XXL', 19, 'IMG/products/669a131eb212d-Two Side Slit Batik Dress 1.webp', 'IMG/products/669a131eb2365-Two Side Slit Batik Dress 2.webp', 'IMG/products/669a131eb2485-Two Side Slit Batik Dress 3.webp', 1, 0),
(40, 'Pin Tucked Batik Dress', 4250.00, 0.00, 1, 3, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a844b0b719-Pin Tucked Detailed Batik Floral Dress 1.webp', 'IMG/products/669a844b0b8d2-Pin Tucked Detailed Batik Floral Dress 2.webp', 'IMG/products/669a844b0b9f5-Pin Tucked Detailed Batik Floral Dress 3.webp', 1, 0),
(41, 'Party Wear L/S Shirt', 4990.00, 0.00, 2, 14, 'XS,S,M,L,XL,XXL', 19, 'IMG/products/669a7ed3324ad-Party Wear Sh 1.webp', 'IMG/products/669a7ed33262a-Party Wear Sh 2.webp', 'IMG/products/669a7ed332748-Party Wear Sh 3.webp', 0, 0),
(42, 'Casual Wear Slim Fit Shirt', 4490.00, 3990.00, 2, 14, 'XS,S,M,L,XL,XXL', 19, 'IMG/products/669a7f2bd2edb-Casual Wear Slim Fit  Shirt 1.webp', 'IMG/products/669a7f2bd3069-Casual Wear Slim Fit  Shirt 2.webp', 'IMG/products/669a7f2bd31f3-Casual Wear Slim Fit  Shirt 3.webp', 0, 1),
(47, 'Printed Button Down Blouse', 2990.00, 0.00, 1, 4, 'XS,S,M,L,XL', 20, 'IMG/products/669a19fe6ab6a-Printed Button Down Blouse 1.webp', 'IMG/products/669a19fe6ace4-Printed Button Down Blouse 2.webp', 'IMG/products/669a19fe6adfc-Printed Button Down Blouse 3.webp', 0, 0),
(48, 'Printed Button Down Shirt', 2990.00, 0.00, 1, 4, 'XS,S,M,L,XL', 20, 'IMG/products/669a1a685af6d-Printed Button Down Shirt 1.webp', 'IMG/products/669a1a685b0cd-Printed Button Down Shirt 2.webp', 'IMG/products/669a1a685b1c2-Printed Button Down Shirt 3.webp', 0, 0),
(65, 'Basic Shift Dress', 4350.00, 0.00, 1, 12, 'S,M,L,XL,XXL', 20, 'IMG/products/669a743190fc9-Basic Shift Dress 1.webp', 'IMG/products/669a743191968-Basic Shift Dress 2.webp', 'IMG/products/669a743191b25-Basic Shift Dress 3.webp', 0, 0),
(49, 'Oversized Button Down Shirt', 3450.00, 0.00, 1, 4, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a1b6a0dedc-Oversized Button Down Shirt 1.webp', 'IMG/products/669a1b6a0e03a-Oversized Button Down Shirt 2.webp', 'IMG/products/669a1b6a0e13c-Oversized Button Down Shirt 3.webp', 0, 0),
(50, 'Long Sleeve Blouse With Side Detail', 3650.00, 3450.00, 1, 4, 'S,M,L,XL', 19, 'IMG/products/669a1c1b3dd09-Long Sleeve Blouse With Side Detail 1.webp', 'IMG/products/669a1c1b3de82-Long Sleeve Blouse With Side Detail 2.webp', 'IMG/products/669a1c1b3df81-Long Sleeve Blouse With Side Detail 3.webp', 0, 1),
(51, 'Pleat Detailed Sleeveless Blouse', 2250.00, 0.00, 1, 4, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a1c7e18c13-Pleat Detailed Sleeveless Blouse 1.webp', 'IMG/products/669a1c7e18d8a-Pleat Detailed Sleeveless Blouse 2.webp', 'IMG/products/669a1c7e18e96-Pleat Detailed Sleeveless Blouse 3.webp', 0, 0),
(52, 'Cuban Collar Blouse', 2950.00, 0.00, 1, 4, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a1ce5bc99f-Cuban Collar Blouse 1.webp', 'IMG/products/669a1ce5bcc1d-Cuban Collar Blouse 2.webp', 'IMG/products/669a1ce5bcdbb-Cuban Collar Blouse 3.webp', 0, 0),
(53, 'Wide Neck Blouse', 4650.00, 4290.00, 1, 4, 'XS,S,M,L,XL,XXL', 16, 'IMG/products/669a1d5dc5c68-Wide Neck Blouse 1.webp', 'IMG/products/669a1d5dc5dc3-Wide Neck Blouse 2.webp', 'IMG/products/669a1d5dc5ec4-Wide Neck Blouse 4.webp', 0, 1),
(54, 'Round Neck Kaftan Dress', 4690.00, 0.00, 1, 3, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a1e3ccb00f-Round Neck Kaftan Dress 1.webp', 'IMG/products/669a1e3ccb159-Round Neck Kaftan Dress 2.webp', 'IMG/products/669a1e3ccb256-Round Neck Kaftan Dress 3.webp', 1, 0),
(55, 'Printed V Neck Dress', 4690.00, 0.00, 1, 3, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a1f530442c-Printed V Neck Dress 1.webp', 'IMG/products/669a1f53045d7-Printed V Neck Dress 2.webp', 'IMG/products/669a1f53046f2-Printed V Neck Dress 3.webp', 1, 0),
(56, 'Midi Dress With Collar', 4450.00, 0.00, 1, 3, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a200959fce-Midi Dress With Collar 1.webp', 'IMG/products/669a20095a185-Midi Dress With Collar 2.webp', 'IMG/products/669a20095a2a3-Midi Dress With Collar 3.webp', 1, 0),
(57, 'Active Wear T-Shirt', 1890.00, 1650.00, 2, 8, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a80b3dc111-Active Wear T-Shirt With Front Contrast 1.webp', 'IMG/products/669a80b3dc2d4-Active Wear T-Shirt With Front Contrast 2.webp', 'IMG/products/669a80b3dc3fb-Active Wear T-Shirt With Front Contrast 3.webp', 0, 1),
(58, 'Embroidered Cuban Shirt', 3750.00, 0.00, 2, 14, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a81585afe4-Embroidered Cuban Collar Shirt 1.webp', 'IMG/products/669a81585b156-Embroidered Cuban Collar Shirt 2.webp', 'IMG/products/669a81585b25d-Embroidered Cuban Collar Shirt 3.webp', 1, 0),
(80, 'Casual Wear Cuban Shirt', 2490.00, 0.00, 2, 14, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a85231dc69-Casual Wear Cuban Shirt 1.webp', 'IMG/products/669a85231de06-Casual Wear Cuban Shirt 2.webp', 'IMG/products/669a85231df1a-Casual Wear Cuban Shirt 3.webp', 1, 0),
(59, 'Sleeveless Flowy Dress', 4950.00, 4550.00, 1, 3, 'S,M,L,XL', 20, 'IMG/products/669a6110c62e2-Sleeveless Flowy Dress 1.webp', 'IMG/products/669a6110c64c8-Sleeveless Flowy Dress 2.webp', 'IMG/products/669a6110c6726-Sleeveless Flowy Dress 3.webp', 0, 1),
(60, 'Strappy Stripe Long Dress', 4650.00, 3990.00, 1, 3, 'S,M,L,XL', 20, 'IMG/products/669a62227cac1-Strappy Stripe Long Dress 1.webp', 'IMG/products/669a62227cc34-Strappy Stripe Long Dress 2.webp', 'IMG/products/669a62227cd4e-Strappy Stripe Long Dress 3.webp', 0, 1),
(61, 'Embroidery Detailed Blouse', 3250.00, 2490.00, 1, 4, 'S,M,L', 19, 'IMG/products/669a63449e2b6-Embroidery Detailed Blouse 1.webp', 'IMG/products/669a63449e488-Embroidery Detailed Blouse 2.webp', 'IMG/products/669a63449e6b9-Embroidery Detailed Blouse 3.webp', 0, 1),
(62, 'Blouse With Embroidered Sleeves', 2750.00, 1950.00, 1, 4, 'S,M,L,XL', 20, 'IMG/products/669a639f87c3c-Blouse With Embroidered Sleeves 1.webp', 'IMG/products/669a639f87dbc-Blouse With Embroidered Sleeves 2.webp', 'IMG/products/669a639f87ecb-Blouse With Embroidered Sleeves 3.webp', 0, 1),
(63, 'Printed Blouse With Front Tie', 3650.00, 0.00, 1, 12, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a70619c429-Printed Blouse With Front Tie 1.webp', 'IMG/products/669a70619c5b6-Printed Blouse With Front Tie 2.webp', 'IMG/products/669a70619c6dc-Printed Blouse With Front Tie 3.webp', 0, 0),
(64, 'Printed Mock Wrap Skirt', 2350.00, 0.00, 1, 12, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a71433ac5e-Printed Mock Wrap Skirt 1.webp', 'IMG/products/669a71433ade1-Printed Mock Wrap Skirt 2.webp', 'IMG/products/669a71433aef1-Printed Mock Wrap Skirt 3.webp', 0, 0),
(67, 'Button Down Shirt', 3250.00, 0.00, 1, 12, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a74cc839c6-Button Down Shirt 1.webp', 'IMG/products/669a74cc83b45-Button Down Shirt 2.webp', 'IMG/products/669a74cc83c6b-Button Down Shirt 3.webp', 0, 0),
(68, 'Over Sized Button Down Shirt', 3950.00, 0.00, 1, 4, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a75955e86c-Over Sized Button Down Shirt 1.webp', 'IMG/products/669a75955ea24-Over Sized Button Down Shirt 2.webp', 'IMG/products/669a75955eb3e-Over Sized Button Down Shirt 3.webp', 0, 0),
(69, 'Colour Block Dress', 2350.00, 0.00, 1, 12, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a766f32c38-Colour Block Dress 1.webp', 'IMG/products/669a766f32dc5-Colour Block Dress 2.webp', 'IMG/products/669a766f32ee1-Colour Block Dress 3.webp', 0, 0),
(70, 'Basic Printed Short', 1990.00, 0.00, 1, 12, 'XS,S,M,L,XL', 20, 'IMG/products/669a76be9d1b7-Basic Printed Short 1.webp', 'IMG/products/669a76be9d358-Basic Printed Short 2.webp', 'IMG/products/669a76be9d48d-Basic Printed Short 3.webp', 0, 0),
(71, 'Basic Flowy Dress', 2450.00, 0.00, 1, 12, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a76fb79cc6-Basic Flowy Dress 1.webp', 'IMG/products/669a76fb79e66-Basic Flowy Dress 2.webp', 'IMG/products/669a76fb79fce-Basic Flowy Dress 3.webp', 0, 0),
(72, 'Birdie Detailed Batik', 2250.00, 0.00, 1, 13, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a77d585d9b-Birdie Detailed Batik 1.webp', 'IMG/products/669a77d585f42-Birdie Detailed Batik  2.webp', 'IMG/products/669a77d586068-Birdie Detailed Batik  3.webp', 0, 0),
(73, 'Bishop Sleeved Flared', 2750.00, 2150.00, 1, 13, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a784911305-Bishop Sleeved Flared 1.webp', 'IMG/products/669a784911489-Bishop Sleeved Flared 2.webp', 'IMG/products/669a7849115e2-Bishop Sleeved Flared 3.webp', 0, 1),
(74, 'Batik Floral Dress', 5450.00, 4990.00, 1, 13, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a7970c6358-Batik Floral Dress 1.webp', 'IMG/products/669a7970c64d2-Batik Floral Dress 2.webp', 'IMG/products/669a7970c65ea-Batik Floral Dress 3.webp', 0, 1),
(75, 'Batik Kaftan Top', 3650.00, 0.00, 1, 13, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a79f520f98-Batik Kaftan Top 1.webp', 'IMG/products/669a79f521118-Batik Kaftan Top 2.webp', 'IMG/products/669a79f521560-Batik Kaftan Top 3.webp', 0, 0),
(76, 'Long Sleeved Dres', 5450.00, 0.00, 1, 13, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a7a9028924-Long Sleeved Dress 1.webp', 'IMG/products/669a7a9028ab4-Long Sleeved Dress 2.webp', 'IMG/products/669a7a9028bd2-Long Sleeved Dress 3.webp', 0, 0),
(77, 'Anthurium Batik Top', 3450.00, 0.00, 1, 13, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a7b2a02123-Anthurium Batik Top 1.webp', 'IMG/products/669a7b2a022cb-Anthurium Batik Top 2.webp', 'IMG/products/669a7b2a023e8-Anthurium Batik Top  3.webp', 0, 0),
(78, 'Batik Kurtha Top', 4750.00, 3990.00, 1, 13, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a7be60a9b8-Batik Kurtha Top 1.webp', 'IMG/products/669a7be60ab48-Batik Kurtha Top 2.webp', 'IMG/products/669a7be60ac6f-Batik Kurtha Top 3.webp', 0, 1),
(79, 'Long Straight Floral', 4500.00, 0.00, 1, 13, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a7c7773718-Long Straight Floral 1.webp', 'IMG/products/669a7c77738ee-Long Straight Floral 2.webp', 'IMG/products/669a7c77739f1-Long Straight Floral 3.webp', 0, 0),
(81, 'Over Headed Hoody', 2290.00, 0.00, 2, 8, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a85978bd62-Over Headed Hoody 1.webp', 'IMG/products/669a85978bef1-Over Headed Hoody 2.webp', 'IMG/products/669a85978c024-Over Headed Hoody 3.webp', 1, 0),
(82, 'Casual Check Shirt', 2650.00, 2250.00, 2, 14, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a860dd1570-Casual Wear Check Shirt 1.webp', 'IMG/products/669a860dd16fb-Casual Wear Check Shirt 2.webp', 'IMG/products/669a860dd181b-Casual Wear Check Shirt 3.webp', 0, 1),
(83, 'Casual Cuban Shirt', 2450.00, 0.00, 2, 14, 'S,M,L,XL', 20, 'IMG/products/669a86d3d2b91-Casual Cuban Shirt 1.webp', 'IMG/products/669a86d3d2d0a-Casual Cuban Shirt 2.webp', 'IMG/products/669a86d3d2e2b-Casual Cuban Shirt 3.webp', 0, 0),
(84, 'Contrast Detailed Shirt', 2490.00, 0.00, 2, 14, 'S,M,L,XL,XXL', 20, 'IMG/products/669a87241e786-Contrast Detailed Shirt.webp', 'IMG/products/669a87241e9f5-Contrast Detailed Shirt 2.webp', 'IMG/products/669a87241eb63-Contrast Detailed Shirt 3.webp', 0, 0),
(85, 'Over Headed Hoody', 2290.00, 0.00, 2, 8, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a878813023-Over Headed Hoody Jersey 1.webp', 'IMG/products/669a8788131be-Over Headed Hoody Jersey 2.webp', 'IMG/products/669a8788132f8-Over Headed Hoody Jersey 3.webp', 0, 0),
(86, 'Slim Fit Polo T-Shirt', 2190.00, 1690.00, 2, 8, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a87d30ccbd-Slim Fit Polo T-Shirt 1.webp', 'IMG/products/669a87d30ce67-Slim Fit Polo T-Shirt 2.webp', 'IMG/products/669a87d30cfc8-Slim Fit Polo T-Shirt 3.webp', 0, 1),
(87, 'Winter Wear Sweatshirt', 1990.00, 0.00, 2, 8, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a8840aead2-Winter Wear Sweatshirt 1.webp', 'IMG/products/669a8840aec1d-Winter Wear Sweatshirt 2.webp', 'IMG/products/669a8840aedb3-Winter Wear Sweatshirt 3.webp', 0, 0),
(88, 'Half Placket T-shirt', 1990.00, 0.00, 2, 8, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a888d9a1ac-Half Placket T-shirt 1.webp', 'IMG/products/669a888d9a311-Half Placket T-shirt 2.webp', 'IMG/products/669a888d9a488-Half Placket T-shirt 3.webp', 0, 0),
(89, 'Casual Self T-shirt', 2190.00, 0.00, 2, 8, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a8950a777b-Casual Self T-shirt.webp', 'IMG/products/669a8950a7930-Casual Self T-shirt 2.webp', 'IMG/products/669a8950a7af1-Casual Self T-shirt 3.webp', 0, 0),
(90, 'Winter Sweat Pant', 2290.00, 0.00, 2, 16, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a89d2968a1-Winter Wear Sweat Pant 1.webp', 'IMG/products/669a89d296a04-Winter Wear Sweat Pant 2.webp', 'IMG/products/669a89d296b0f-Winter Wear Sweat Pant 3.webp', 0, 0),
(91, 'ACTIVE WEAR JOGGER', 2490.00, 1990.00, 2, 16, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a8a9d1adbc-ACTIVE WEAR JOGGER 1.webp', 'IMG/products/669a8a9d1af6b-ACTIVE WEAR JOGGER 2.webp', 'IMG/products/669a8a9d1b067-ACTIVE WEAR JOGGER 3.webp', 0, 1),
(92, 'Casual Cargo Pant', 2450.00, 0.00, 2, 16, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a8b939bab6-CASUAL WEAR CARGO PANT 1.webp', 'IMG/products/669a8b939bec0-CASUAL WEAR CARGO PANT 2.webp', 'IMG/products/669a8b939c01d-CASUAL WEAR CARGO PANT 3.webp', 0, 0),
(96, 'Organic Cotton Short', 1750.00, 1550.00, 2, 17, 'S,M,L,XL', 20, 'IMG/products/669a8f14b0b10-Organic Cotton Short 1.webp', 'IMG/products/669a8f14b0d57-Organic Cotton Short 2.webp', 'IMG/products/669a8f14b0f0e-Organic Cotton Short 3.webp', 0, 1),
(94, '5 PKT Denim Trouser', 2590.00, 0.00, 2, 16, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a8df37685c-5 PKT Denim Trouser 2.webp', 'IMG/products/669a8df376a3a-5 PKT Denim Trouser 3.webp', 'IMG/products/669a8df376b41-5 PKT Denim Trouser 1.webp', 0, 0),
(97, 'Elasticated Draw Short', 1850.00, 1650.00, 2, 17, 'XS,S,M,L,XL,XXL', 20, 'IMG/products/669a8fbf2da7f-Elasticated Draw Cord 1.webp', 'IMG/products/669a8fbf2dbed-Elasticated Draw Cord 2.jpg', '', 0, 1),
(98, 'Active Wear Short', 1690.00, 0.00, 2, 17, 'S,M,L', 20, 'IMG/products/669a9020a82b8-Active Wear Short 1.webp', 'IMG/products/669a9020a8438-Active Wear Short 2.webp', 'IMG/products/669a9020a85d9-Active Wear Short 3.webp', 0, 0),
(99, 'Sweat Short', 1490.00, 0.00, 2, 17, 'S,M,L,XL', 20, 'IMG/products/669a90bc56127-Sweat Short 1.webp', 'IMG/products/669a90bc5628c-Sweat Short 2.webp', 'IMG/products/669a90bc563b4-Sweat Short 3.webp', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `rating` int NOT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `UserType` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Admin/Customer	',
  `Username` varchar(200) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `PhoneNumber` varchar(200) NOT NULL,
  `Password` varchar(200) NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `UserType`, `Username`, `Email`, `PhoneNumber`, `Password`) VALUES
(7, 'Admin', 'admin', 'admin@thewardrobe.lk', '1234567898', '$2y$10$axAf9pMoYh/nJduADx.rK.oEkJpQfYWI5e7vM8n7vfTPrQT5qYy3O');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
