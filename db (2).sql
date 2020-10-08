-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2020 at 09:24 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` char(1) NOT NULL,
  `name` char(50) NOT NULL,
  `unlist` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `unlist`) VALUES
('B', 'Beverage', 0),
('M', 'Main Course', 0),
('S', 'Snack', 0);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `username` char(50) NOT NULL,
  `password` char(100) NOT NULL,
  `email` char(50) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `role` char(8) DEFAULT 'customer',
  `photo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `username`, `password`, `email`, `status`, `role`, `photo`) VALUES
(1, 'GUEST', 'c8d6ea7f8e6850e9ed3b642900ca27683a257201', 'guest@example.com', 1, 'customer', ''),
(2, 'MEMBER1', '67bdf1550c877028e61d4b072a99d588a7ea522d', 'MEMBER1@EXAMPLE.COM', 1, 'customer', '5ea2eb16d1121.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` char(4) NOT NULL,
  `name` char(20) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `price` decimal(6,2) NOT NULL,
  `category` char(1) NOT NULL,
  `image` varchar(50) NOT NULL,
  `unlist` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `name`, `description`, `price`, `category`, `image`, `unlist`) VALUES
('B001', 'LEMON TEA', '', '6.00', 'B', '5e8c230479c9f.jpg', 0),
('B002', 'WATER', '', '3.00', 'B', '5ea1767adf06f.jpg', 0),
('B003', 'LEMON ICED', '', '5.00', 'B', '5e8d80aee9860.jpg', 0),
('B004', 'LATTE', '', '6.00', 'B', '5e8d86645a2bb.jpg', 0),
('B005', 'MILK', '', '5.00', 'B', '5e8c5bee0136f.jpg', 0),
('B006', 'BANANA MILK', '', '6.00', 'B', '5e8d86d938907.jpg', 0),
('B007', 'WATER 2.0', '', '3.00', 'B', '5ea3d4fbcf882.jpg', 0),
('M001', 'RICE', '', '1.00', 'M', '5e8d891b3fea4.jpg', 0),
('M002', 'NASI LEMAK', '', '5.00', 'M', '5ea3d512ef720.jpg', 0),
('M003', 'CHICKEN DINNER', '', '6.00', 'M', '5ea3d54feae52.jpg', 0),
('S001', 'FRIES', '', '5.00', 'S', '5e8ec598446cf.jpg', 0),
('S002', 'ICE CREAM', '', '0.00', 'S', '5ea2d47eefe76.jpg', 0),
('S003', 'CHEESY WEDGES', '', '7.00', 'S', '5e9c0866b16b8.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` char(10) NOT NULL,
  `customer` int(11) DEFAULT NULL,
  `table` char(4) NOT NULL,
  `staff` int(11) DEFAULT NULL,
  `totalamount` decimal(10,2) DEFAULT NULL,
  `pay` decimal(10,2) DEFAULT NULL,
  `change` decimal(10,2) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `payment` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `customer`, `table`, `staff`, `totalamount`, `pay`, `change`, `datetime`, `status`, `payment`) VALUES
('TX00000001', 1, 'T001', 9000, '38.50', '100.00', '61.50', '2020-03-18 17:51:03', 1, 'CH'),
('TX00000002', 1, 'T001', 9000, '23.10', '23.50', '0.40', '2020-03-19 16:13:58', 1, 'CH'),
('TX00000003', 1, 'T001', 9000, '11.00', '20.00', '9.00', '2020-03-20 17:41:02', 1, 'CH'),
('TX00000004', 1, 'T001', 9000, '63.80', '63.80', '0.00', '2020-03-21 19:25:56', 1, 'CH'),
('TX00000005', 1, 'T002', 9000, '13.20', '15.00', '1.80', '2020-03-22 19:28:35', 1, 'CH'),
('TX00000006', 1, 'T001', 9000, '17.60', '50.00', '32.40', '2020-03-23 15:43:13', 1, 'CH'),
('TX00000007', 1, 'T001', 9000, '55.00', '55.00', '0.00', '2020-03-24 16:21:20', 1, 'CH'),
('TX00000008', 1, 'T001', 9000, '38.50', '100.00', '61.50', '2020-03-25 17:51:03', 1, 'CH'),
('TX00000009', 1, 'T001', 9000, '23.10', '23.50', '0.40', '2020-03-26 16:13:58', 1, 'CH'),
('TX00000010', 1, 'T003', 9000, '11.00', '20.00', '9.00', '2020-03-27 17:41:02', 1, 'CH'),
('TX00000011', 1, 'T001', 9000, '63.80', '63.80', '0.00', '2020-03-19 19:25:56', 1, 'CH'),
('TX00000012', 1, 'A001', 9000, '13.20', '15.00', '1.80', '2020-03-19 19:28:35', 1, 'CH'),
('TX00000013', 1, 'A001', 9000, '17.60', '50.00', '32.40', '2020-03-20 15:43:13', 1, 'CH'),
('TX00000014', 1, 'T003', 9000, '144.00', '144.00', '0.00', '2020-03-30 15:33:58', 1, 'CH'),
('TX00000015', 1, 'T003', 9000, '11.00', '20.00', '9.00', '2020-04-01 17:41:02', 1, 'CH'),
('TX00000016', 1, 'T001', 9000, '63.80', '63.80', '0.00', '2020-04-02 19:25:56', 1, 'CH'),
('TX00000017', 1, 'A001', 9000, '13.20', '15.00', '1.80', '2020-04-03 19:28:35', 1, 'CH'),
('TX00000018', 1, 'A001', 9000, '17.60', '50.00', '32.40', '2020-04-04 15:43:13', 1, 'CH'),
('TX00000019', 1, 'T004', 9000, '123.00', '123.00', '0.00', '2020-04-05 15:37:12', 1, 'CH'),
('TX00000020', 1, 'T002', 9000, '52.00', '52.00', '0.00', '2020-04-06 15:37:47', 1, 'CH'),
('TX00000021', 1, 'T001', 9000, '38.50', '100.00', '61.50', '2020-04-07 17:51:03', 1, 'CH'),
('TX00000022', 1, 'T001', 9000, '23.10', '23.50', '0.40', '2020-04-08 16:13:58', 1, 'CH'),
('TX00000023', 1, 'T001', 9000, '11.00', '20.00', '9.00', '2020-04-09 17:41:02', 1, 'CH'),
('TX00000024', 1, 'T001', 9000, '63.80', '63.80', '0.00', '2020-04-10 19:25:56', 1, 'CH'),
('TX00000025', 1, 'T002', 9000, '13.20', '15.00', '1.80', '2020-04-11 19:28:35', 1, 'CH'),
('TX00000026', 1, 'T001', 9000, '17.60', '50.00', '32.40', '2020-04-12 15:43:13', 1, 'CH'),
('TX00000027', 1, 'T001', 9000, '55.00', '55.00', '0.00', '2020-04-13 16:21:20', 1, 'CH'),
('TX00000028', 1, 'T001', 9000, '38.50', '100.00', '61.50', '2020-04-14 17:51:03', 1, 'CH'),
('TX00000029', 1, 'T001', 9000, '23.10', '23.50', '0.40', '2020-04-15 16:13:58', 1, 'CH'),
('TX00000030', 1, 'T003', 9000, '11.00', '20.00', '9.00', '2020-04-16 17:41:02', 1, 'CH'),
('TX00000031', 1, 'T001', 9000, '63.80', '63.80', '0.00', '2020-04-17 19:25:56', 1, 'CH'),
('TX00000032', 1, 'A001', 9000, '13.20', '15.00', '1.80', '2020-04-18 19:28:35', 1, 'CH'),
('TX00000033', 1, 'A001', 9000, '17.60', '50.00', '32.40', '2020-04-19 15:43:13', 1, 'CH'),
('TX00000034', 1, 'T003', 9000, '144.00', '144.00', '0.00', '2020-04-20 15:33:58', 1, 'CH'),
('TX00000035', 1, 'T003', 9000, '11.00', '20.00', '9.00', '2020-04-21 17:41:02', 1, 'CH'),
('TX00000036', 1, 'T001', 9000, '63.80', '63.80', '0.00', '2020-04-22 19:25:56', 1, 'CH'),
('TX00000037', 1, 'A001', 9000, '13.20', '15.00', '1.80', '2020-04-23 19:28:35', 1, 'CH'),
('TX00000038', 1, 'A001', 9000, '17.60', '50.00', '32.40', '2020-04-24 15:43:13', 1, 'CH'),
('TX00000039', 1, 'T004', 9000, '123.00', '123.00', '0.00', '2020-04-25 15:37:12', 1, 'CH'),
('TX00000040', 1, 'T002', 9000, '52.00', '52.00', '0.00', '2020-04-26 15:37:47', 1, 'CH'),
('TX00000041', 1, 'T001', 9000, '6.60', '7.00', '0.40', '2020-04-23 21:49:49', 1, 'CH'),
('TX00000042', 1, 'T001', 9000, '11.00', '15.00', '4.00', '2020-04-24 00:27:34', 1, 'CH'),
('TX00000043', 1, 'T001', 9000, '5.50', '6.00', '0.50', '2020-04-24 00:28:06', 1, 'CH'),
('TX00000044', 1, 'T001', 9000, '169.40', '200.00', '30.60', '2020-04-24 00:34:16', 1, 'CH'),
('TX00000048', 1, 'T002', 9000, '8.80', '8.80', '0.00', '2020-04-24 18:20:50', 1, 'PP'),
('TX00000049', 1, 'T002', 9000, '33.00', '33.00', '0.00', '2020-04-24 18:32:37', 1, 'PP'),
('TX00000050', 1, 'T001', 9000, '3.30', '4.00', '0.70', '2020-04-25 14:33:37', 1, 'CH'),
('TX00000051', 1, 'T002', NULL, '12.10', '12.10', '0.00', '2020-04-25 14:20:53', 1, 'PP'),
('TX00000052', NULL, 'A002', 9001, '3.30', '6.00', '2.70', '2020-04-24 21:42:31', 1, NULL),
('TX00000053', 1, 'T001', 9000, '11.00', '11.00', '0.00', '2020-04-25 14:51:12', 1, 'CH'),
('TX00000054', 1, 'T002', NULL, NULL, NULL, NULL, NULL, 0, NULL),
('TX00000055', 1, 'T003', NULL, NULL, NULL, NULL, NULL, 0, NULL),
('TX00000056', 1, 'T004', NULL, NULL, NULL, NULL, NULL, 0, NULL),
('TX00000057', 1, 'T001', NULL, '11.00', '11.00', '0.00', '2020-04-25 15:11:05', 1, 'PP'),
('TX00000058', NULL, 'T001', NULL, NULL, NULL, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `id` int(11) NOT NULL,
  `order` char(10) NOT NULL,
  `item` char(4) NOT NULL,
  `quantity` int(11) NOT NULL,
  `remark` char(100) DEFAULT NULL,
  `subtotal` decimal(6,2) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`id`, `order`, `item`, `quantity`, `remark`, `subtotal`, `status`) VALUES
(35, 'TX00000041', 'B001', 1, '', '6.00', 1),
(37, 'TX00000042', 'B003', 2, '', '10.00', 1),
(38, 'TX00000043', 'B003', 1, '', '5.00', 3),
(39, 'TX00000044', 'B004', 5, '', '30.00', 3),
(40, 'TX00000044', 'B004', 5, '', '30.00', 3),
(41, 'TX00000044', 'B004', 5, '', '30.00', 3),
(42, 'TX00000044', 'B003', 5, '', '25.00', 3),
(43, 'TX00000044', 'B002', 5, '', '15.00', 3),
(44, 'TX00000044', 'B002', 5, '', '15.00', 3),
(45, 'TX00000044', 'B002', 1, '', '3.00', 3),
(46, 'TX00000044', 'B002', 1, '', '3.00', 3),
(47, 'TX00000044', 'B002', 1, '', '3.00', 3),
(51, 'TX00000048', 'B002', 1, '', '3.00', 1),
(52, 'TX00000048', 'B003', 1, '', '5.00', 1),
(54, 'TX00000049', 'B004', 5, '', '30.00', 1),
(56, 'TX00000050', 'B002', 1, '', '3.00', 1),
(57, 'TX00000052', 'B002', 1, '', '3.00', 1),
(58, 'TX00000051', 'B003', 1, '', '5.00', 3),
(60, 'TX00000051', 'B001', 1, '', '6.00', 3),
(61, 'TX00000053', 'B003', 2, '', '10.00', 1),
(62, 'TX00000057', 'B003', 2, '', '10.00', 1),
(63, 'TX00000054', 'B004', 2, '', '12.00', 3),
(64, 'TX00000058', 'B006', 1, 'MORE BANANA', '6.00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `username` char(50) NOT NULL,
  `password` char(100) NOT NULL,
  `email` char(50) NOT NULL,
  `status` char(1) NOT NULL DEFAULT '0',
  `role` char(10) NOT NULL DEFAULT 'staff',
  `photo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `username`, `password`, `email`, `status`, `role`, `photo`) VALUES
(9000, 'ADMIN', 'f43faa584c50b9eb77545310137bbf0c626ac37c', 'themrheng99@gmail.com', '1', 'admin', ''),
(9001, 'STAFF1', '754c10c0f66c80fa8b298614ec862d742ef90bd1', 'staff1@example.com', '1', 'staff', '');

-- --------------------------------------------------------

--
-- Table structure for table `table`
--

CREATE TABLE `table` (
  `id` char(4) NOT NULL,
  `type` char(1) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `unlist` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `table`
--

INSERT INTO `table` (`id`, `type`, `status`, `unlist`) VALUES
('A001', 'A', 0, 0),
('A002', 'A', 0, 0),
('T001', 'T', 0, 0),
('T002', 'T', 0, 0),
('T003', 'T', 0, 0),
('T004', 'T', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tabletype`
--

CREATE TABLE `tabletype` (
  `id` char(1) NOT NULL,
  `type` char(10) NOT NULL,
  `unlist` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabletype`
--

INSERT INTO `tabletype` (`id`, `type`, `unlist`) VALUES
('A', 'TAKE AWAY', 0),
('T', 'TABLE', 0);

-- --------------------------------------------------------

--
-- Stand-in structure for view `user`
-- (See below for the actual view)
--
CREATE TABLE `user` (
`id` int(11)
,`username` char(50)
,`password` char(100)
,`email` char(50)
,`status` char(11)
,`role` char(10)
,`photo` varchar(20)
);

-- --------------------------------------------------------

--
-- Structure for view `user`
--
DROP TABLE IF EXISTS `user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user`  AS  select `staff`.`id` AS `id`,`staff`.`username` AS `username`,`staff`.`password` AS `password`,`staff`.`email` AS `email`,`staff`.`status` AS `status`,`staff`.`role` AS `role`,`staff`.`photo` AS `photo` from `staff` union select `customer`.`id` AS `id`,`customer`.`username` AS `username`,`customer`.`password` AS `password`,`customer`.`email` AS `email`,`customer`.`status` AS `status`,`customer`.`role` AS `role`,`customer`.`photo` AS `photo` from `customer` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer` (`customer`,`table`,`staff`),
  ADD KEY `table` (`table`),
  ADD KEY `staff` (`staff`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_ibfk_2` (`item`),
  ADD KEY `order` (`order`,`item`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `table`
--
ALTER TABLE `table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `tabletype`
--
ALTER TABLE `tabletype`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9002;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`category`) REFERENCES `category` (`id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`table`) REFERENCES `table` (`id`),
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`customer`) REFERENCES `customer` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `order_ibfk_3` FOREIGN KEY (`staff`) REFERENCES `staff` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`item`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `table`
--
ALTER TABLE `table`
  ADD CONSTRAINT `table_ibfk_1` FOREIGN KEY (`type`) REFERENCES `tabletype` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
