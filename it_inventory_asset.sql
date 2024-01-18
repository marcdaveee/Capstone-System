-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2023 at 05:40 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `it_inventory_asset`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_rights_table`
--

CREATE TABLE `access_rights_table` (
  `access_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `access_level` int(11) NOT NULL,
  `folder_path_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `access_rights_table`
--

INSERT INTO `access_rights_table` (`access_id`, `email`, `access_level`, `folder_path_name`) VALUES
(322, 'hazelhernandez@gmail.com', 1, 'root/IT Department'),
(323, 'hazelhernandez@gmail.com', 1, 'root/IT Department/Policies'),
(324, 'hazelhernandez@gmail.com', 2, 'root/Agriculture'),
(401, 'davevalencia17@gmail.com', 1, 'root/Human Resource'),
(402, 'davevalencia17@gmail.com', 1, 'root/Human Resource/Payroll/2023'),
(403, 'davevalencia17@gmail.com', 1, 'root/Human Resource/Personal Data Sheet'),
(404, 'davevalencia17@gmail.com', 1, 'root/Human Resource/Payroll'),
(405, 'davevalencia17@gmail.com', 1, 'root/IT Department'),
(406, 'davevalencia17@gmail.com', 2, 'root/IT Department/Policies'),
(407, 'davevalencia17@gmail.com', 1, 'root/Human Resource/Payroll/2023/Dave Valencia'),
(408, 'leadimaculangan@gmail.com', 2, 'root/Human Resource'),
(409, 'leadimaculangan@gmail.com', 2, 'root/Human Resource/Payroll'),
(410, 'leadimaculangan@gmail.com', 2, 'root/Human Resource/Payroll/2023'),
(411, 'leadimaculangan@gmail.com', 2, 'root/Human Resource/Payroll/2023/Mikmik Hernandez'),
(412, 'leadimaculangan@gmail.com', 2, 'root/Human Resource/Personal Data Sheet'),
(413, 'leadimaculangan@gmail.com', 2, 'root/Human Resource/Payroll/2023/Dave Valencia'),
(414, 'leadimaculangan@gmail.com', 1, 'root/IT Department'),
(415, 'leadimaculangan@gmail.com', 1, 'root/IT Department/Policies'),
(422, 'mikmik@gmail.com', 1, 'root/Human Resource/Payroll'),
(423, 'mikmik@gmail.com', 1, 'root/Human Resource/Payroll/2023/Mikmik Hernandez'),
(424, 'mikmik@gmail.com', 1, 'root/Human Resource/Payroll/2023'),
(425, 'mikmik@gmail.com', 1, 'root/IT Department/Policies'),
(426, 'mikmik@gmail.com', 1, 'root/IT Department'),
(427, 'mikmik@gmail.com', 1, 'root/Human Resource');

-- --------------------------------------------------------

--
-- Table structure for table `admin_account_tb`
--

CREATE TABLE `admin_account_tb` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_role` varchar(50) NOT NULL DEFAULT 'admin',
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_account_tb`
--

INSERT INTO `admin_account_tb` (`id`, `username`, `email`, `password`, `user_role`, `reset_token_hash`, `reset_token_expires_at`) VALUES
(1, 'admin', 'tech.lgusaq@gmail.com', '0192023a7bbd73250516f069df18b500', 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `file_table`
--

CREATE TABLE `file_table` (
  `file_id` int(11) NOT NULL,
  `file_path_name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_type` varchar(20) NOT NULL,
  `folder_path_name` varchar(255) NOT NULL,
  `file_owner` varchar(100) NOT NULL,
  `file_classification` varchar(100) NOT NULL,
  `date_uploaded` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `file_table`
--

INSERT INTO `file_table` (`file_id`, `file_path_name`, `file_name`, `file_size`, `file_type`, `folder_path_name`, `file_owner`, `file_classification`, `date_uploaded`) VALUES
(58, 'root/Human Resource/Payroll/2023/Mikmik Hernandez/Payroll-Kuya_Mikmik.pdf', 'Payroll-Kuya_Mikmik.pdf', 41774, 'pdf', 'root/Human Resource/Payroll/2023/Mikmik Hernandez', '9', 'Confidential', '2023-11-08'),
(79, 'root/Human Resource/Payroll/2023/Dave Valencia/Payroll-Valencia_Dave.pdf', 'Payroll-Valencia_Dave.pdf', 40416, 'pdf', 'root/Human Resource/Payroll/2023/Dave Valencia', '9', 'Confidential', '2023-11-29'),
(80, 'root/Human Resource/Payroll/2023/Dave Valencia/Present the Results.docx', 'Present the Results.docx', 17766, 'docx', 'root/Human Resource/Payroll/2023/Dave Valencia', '9', 'Internal Use Only', '2023-11-29');

-- --------------------------------------------------------

--
-- Table structure for table `folder_relation_table`
--

CREATE TABLE `folder_relation_table` (
  `subfolder_id` int(11) NOT NULL,
  `folder_path_name` varchar(255) NOT NULL,
  `folder_name` varchar(255) NOT NULL,
  `parent_folder_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `folder_relation_table`
--

INSERT INTO `folder_relation_table` (`subfolder_id`, `folder_path_name`, `folder_name`, `parent_folder_id`) VALUES
(18, 'root/Human Resource', 'Human Resource', 1),
(19, 'root/IT Department', 'IT Department', 1),
(20, 'root/Human Resource/Payroll', 'Payroll', 31),
(21, 'root/Human Resource/Payroll/2023', '2023', 33),
(22, 'root/Human Resource/Payroll/2022', '2022', 33),
(23, 'root/Human Resource/Payroll/2021', '2021', 33),
(24, 'root/Human Resource/Payroll/2023/Mikmik Hernandez', 'Mikmik Hernandez', 34),
(25, 'root/Human Resource/Payroll/2023/Dave Valencia', 'Dave Valencia', 34),
(26, 'root/Human Resource/Payroll/2020', '2020', 33),
(28, 'root/Accounting Department', 'Accounting Department', 1),
(31, 'root/MENRO', 'MENRO', 1),
(32, 'root/IT Department/Policies', 'Policies', 32),
(34, 'root/Agriculture', 'Agriculture', 1),
(35, 'root/Human Resource/Personal Data Sheet', 'Personal Data Sheet', 31),
(36, 'root/Human Resource/Payroll/2023/Dave Valencia/test', 'test', 38);

-- --------------------------------------------------------

--
-- Table structure for table `folder_table`
--

CREATE TABLE `folder_table` (
  `folder_id` int(11) NOT NULL,
  `folder_path_name` varchar(255) NOT NULL,
  `folder_name` varchar(255) NOT NULL,
  `folder_classification` varchar(100) NOT NULL,
  `requested_by` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `folder_table`
--

INSERT INTO `folder_table` (`folder_id`, `folder_path_name`, `folder_name`, `folder_classification`, `requested_by`, `created_at`) VALUES
(1, 'root', 'root', 'internal', '', '2023-11-07 19:23:57'),
(31, 'root/Human Resource', 'Human Resource', 'Internal', '', '2023-11-07 19:23:57'),
(32, 'root/IT Department', 'IT Department', 'Internal', '', '2023-11-07 19:23:57'),
(33, 'root/Human Resource/Payroll', 'Payroll', 'Internal', 'Sir Arnel Ilao', '2023-11-07 19:23:57'),
(34, 'root/Human Resource/Payroll/2023', '2023', 'Internal', 'Sir Arnel Ilao', '2023-11-07 19:23:57'),
(35, 'root/Human Resource/Payroll/2022', '2022', 'Internal', 'Sir Arnel Ilao', '2023-11-07 19:23:57'),
(36, 'root/Human Resource/Payroll/2021', '2021', 'Internal', 'Sir Arnel Ilao', '2023-11-07 19:23:57'),
(37, 'root/Human Resource/Payroll/2023/Mikmik Hernandez', 'Mikmik Hernandez', 'Internal', 'Sir Arnel Ilao', '2023-11-07 19:23:57'),
(38, 'root/Human Resource/Payroll/2023/Dave Valencia', 'Dave Valencia', 'Internal', 'Sir Arnel Ilao\r\n', '2023-11-07 19:23:57'),
(39, 'root/Human Resource/Payroll/2020', '2020', 'Internal', 'Sir Arnel Ilao', '2023-11-07 19:23:57'),
(41, 'root/Accounting Department', 'Accounting Department', 'Internal', '', '2023-11-07 19:23:57'),
(44, 'root/MENRO', 'MENRO', 'Internal', '', '2023-11-07 19:23:57'),
(45, 'root/IT Department/Policies', 'Policies', 'Internal', '', '2023-11-07 19:23:57'),
(47, 'root/Agriculture', 'Agriculture', 'Internal', '', '2023-11-08 08:57:37'),
(48, 'root/Human Resource/Personal Data Sheet', 'Personal Data Sheet', 'Internal', 'Sir Arnel Ilao', '2023-11-08 10:31:18'),
(49, 'root/Human Resource/Payroll/2023/Dave Valencia/test', 'test', 'Internal', 'Sir Arnel Ilao', '2023-11-15 22:10:51');

-- --------------------------------------------------------

--
-- Table structure for table `hardware_asset`
--

CREATE TABLE `hardware_asset` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `serial_no` varchar(255) NOT NULL,
  `item_type` varchar(255) NOT NULL,
  `item_brand` varchar(255) NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `hardware_description` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `curr_location` varchar(255) NOT NULL,
  `curr_status` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hardware_asset`
--

INSERT INTO `hardware_asset` (`id`, `item_name`, `serial_no`, `item_type`, `item_brand`, `manufacturer`, `hardware_description`, `user`, `curr_location`, `curr_status`, `created_at`) VALUES
(97, 'Laptop-1', 'test001LAPTOP', 'Laptop', 'Asus', 'Shopee', 'New arrived items', 'None', 'IT Department', 'Inactive', '2023-10-17 05:27:07'),
(98, 'Laptop-02-HR', 'test002LAPTOP', 'Laptop', 'huawei', 'Gigabytes', 'some description to describe this assets', 'None', 'IT Department', 'Inactive', '2023-10-17 05:53:53'),
(99, 'Desktop-01-AG', 'test01Desktop', 'Desktop', 'Intel', 'Gigabytes', 'This is a government issued device', 'None', 'IT Department', 'Inactive', '2023-10-17 05:58:43'),
(100, 'test item name', 'test003LAPTOP', 'test type 20', 'ASUS TUF', 'Gov-issued', '', 'Menro staff', 'MENRO', 'Active', '2023-10-19 13:17:04'),
(101, 'Desktop 004', 'test004Desktop', 'test type 9', 'Intel Core i5', 'Gigabytes', 'For upgrade', 'None', 'IT Department', 'Inactive', '2023-10-19 13:42:16'),
(102, 'Desktop-05-AG', 'test02Desktop', 'Desktop', 'Intel Core i5', 'Shopee', 'Being repaired\r\n', 'Charles Leyesa', 'Agriculture Dept.', 'Active', '2023-10-19 13:43:10'),
(103, 'PLDT Router AG', 'test001Router', 'Network Device', 'PLDT', 'PLDT', 'New bought router', 'Treasurer staffs', 'Treasurer Office', 'Active', '2023-10-21 07:59:15'),
(104, 'Laptop-Menro-01', 'test005LAPTOP', 'Laptop', 'Mac book pro', 'Apple', '', 'Kenneth Silva', 'MENRO', 'Active', '2023-10-21 08:00:03'),
(105, 'Desktop 004 AC', '001-Desk-AC', 'Desktop', 'Ryzen ', 'Gov-issued', 'Being Repaired', 'None', 'Accounting Dept.', 'Inactive', '2023-10-21 08:43:10'),
(106, 'Laptop-04-AC', 'test004LAPTOP', 'Laptop', 'Lenovo', 'Lenovo', 'Government Issued', 'Eddie Toh', 'Accounting Dept.', 'Active', '2023-10-31 08:33:09'),
(107, 'Laptop-01-HR', 'testSerialNo1', 'Laptop', 'Lenovo', 'Lenovo', 'Government Issued Device', 'Lea Dimaculangan', 'Human Resource Dept.', 'Active', '2023-11-13 04:55:15');

-- --------------------------------------------------------

--
-- Table structure for table `ha_item_type_property`
--

CREATE TABLE `ha_item_type_property` (
  `id` int(11) NOT NULL,
  `item_type_category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ha_item_type_property`
--

INSERT INTO `ha_item_type_property` (`id`, `item_type_category`) VALUES
(1, 'Laptop'),
(3, 'Desktop'),
(4, 'Peripheral Device'),
(5, 'Network Device'),
(6, 'Tablet'),
(7, 'Phone');

-- --------------------------------------------------------

--
-- Table structure for table `ha_location_option`
--

CREATE TABLE `ha_location_option` (
  `id` int(11) NOT NULL,
  `location_option` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ha_location_option`
--

INSERT INTO `ha_location_option` (`id`, `location_option`) VALUES
(1, 'IT Department'),
(2, 'Human Resource Dept.'),
(3, 'Agriculture Dept.'),
(4, 'Treasurer Office'),
(5, 'Assessor Office'),
(6, 'Accounting Dept.'),
(7, 'Office of the Mayor'),
(8, 'MENRO'),
(9, 'Engineering Dept.'),
(10, 'GSD');

-- --------------------------------------------------------

--
-- Table structure for table `lgu_saq_dept`
--

CREATE TABLE `lgu_saq_dept` (
  `id` int(11) NOT NULL,
  `dept_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lgu_saq_dept`
--

INSERT INTO `lgu_saq_dept` (`id`, `dept_name`) VALUES
(12, 'Accounting'),
(1, 'Agriculture'),
(3, 'Human Resource'),
(2, 'IT Department'),
(13, 'Job Order'),
(14, 'Judiciary');

-- --------------------------------------------------------

--
-- Table structure for table `sa_type_property`
--

CREATE TABLE `sa_type_property` (
  `id` int(11) NOT NULL,
  `software_type_option` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sa_type_property`
--

INSERT INTO `sa_type_property` (`id`, `software_type_option`) VALUES
(1, 'Productivity Software'),
(2, 'Security Software'),
(3, 'Graphics and Design'),
(4, 'Database Software'),
(5, 'Custom/In-House Software'),
(6, 'Virtualization Software');

-- --------------------------------------------------------

--
-- Table structure for table `software_asset`
--

CREATE TABLE `software_asset` (
  `id` int(11) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `software` varchar(255) NOT NULL,
  `software_type` varchar(255) NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `date_of_purchase` date NOT NULL,
  `no_of_installation` int(11) NOT NULL,
  `validity` date NOT NULL,
  `soft_description` varchar(255) NOT NULL,
  `curr_status` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `software_asset`
--

INSERT INTO `software_asset` (`id`, `product_id`, `software`, `software_type`, `manufacturer`, `date_of_purchase`, `no_of_installation`, `validity`, `soft_description`, `curr_status`, `created_at`) VALUES
(5, '123123123', 'MS Excel', 'Productivity Software', 'Microsoft', '2023-11-18', 6, '2023-11-18', 'Installed on the 6 new laptops in Agriculture Dept.', 'expired', '2023-10-20 08:04:30'),
(6, '321321321', 'Mc Affee Antivirus', 'Security Software', 'Mc Affee', '2023-10-19', 10, '2023-11-20', '', 'expired', '2023-10-20 09:50:16'),
(7, '11.11.11.11', 'Oracle', 'Database Software', 'Oracle', '2023-10-18', 3, '2023-10-30', 'Installed on 3 desktops in the engineering dept. ', 'expired', '2023-10-20 12:05:22'),
(8, '231231231', 'Avast Anti-virus', 'Security Software', 'Avast', '2023-10-21', 3, '2023-10-31', 'Some notes\r\nSome notes\r\nSome notes\r\nSome notes\r\nSome notes\r\nSome notes\r\nSome notes', 'expired', '2023-10-21 09:02:14'),
(9, '11.11.11.12', 'Adobe XD', 'Graphics and Design', 'Adobe', '2023-10-26', 3, '2023-11-26', '', 'expired', '2023-10-26 14:08:35'),
(10, '22222222', 'Figma', 'Graphics and Design', 'Figma Inc', '2023-11-16', 5, '2023-11-30', '', 'expires in 2 days', '2023-11-16 15:07:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_account_table`
--

CREATE TABLE `user_account_table` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_role` varchar(100) NOT NULL,
  `department` varchar(255) NOT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_account_table`
--

INSERT INTO `user_account_table` (`user_id`, `email`, `username`, `password`, `user_role`, `department`, `reset_token_hash`, `reset_token_expires_at`) VALUES
(3, 'mikmik@gmail.com', 'Mikmik Hernandez JUDI', '46f0cac183682913b2d9e685cd7da3a7', 'Judiciary', 'Judiciary', 'e50cc33b2c1d84ef2908d3345d84bf6796c52ce5c4cd75bdb3257bb4d376193e', '2023-11-04 03:50:23'),
(7, 'hazelhernandez@gmail.com', 'Hazel Hernandez AGRI', 'a1fb980d1c4326cc7ffb67f0b3a0ccf3', 'Agriculture Dept. Vice Head', 'Agriculture', NULL, NULL),
(8, 'davevalencia17@gmail.com', 'Marc Dave Valencia IT', '57b29667cd2d87f7ea750cae52c3355b', 'IT Head', 'IT Department', '8e4195a505d3a80333ef34e88bfdaca1f6e42b2420c7927970028f801f7e84bd', '2023-11-17 14:49:14'),
(9, 'leadimaculangan@gmail.com', 'Lea Dimaculangan HR', 'b0cd357a83d1ff4c915d2c257645f9fb', 'HR head', 'Human Resource', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_role_options_table`
--

CREATE TABLE `user_role_options_table` (
  `id` int(11) NOT NULL,
  `role_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_role_options_table`
--

INSERT INTO `user_role_options_table` (`id`, `role_type`) VALUES
(15, 'Accounting Clerk'),
(18, 'Agriculture Dept. Head'),
(20, 'Agriculture Dept. Intern'),
(16, 'Agriculture Dept. Vice Head'),
(28, 'HR employee'),
(14, 'HR head'),
(19, 'IT Head'),
(30, 'IT Support'),
(13, 'Judiciary'),
(29, 'System Administrator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_rights_table`
--
ALTER TABLE `access_rights_table`
  ADD PRIMARY KEY (`access_id`);

--
-- Indexes for table `admin_account_tb`
--
ALTER TABLE `admin_account_tb`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `file_table`
--
ALTER TABLE `file_table`
  ADD PRIMARY KEY (`file_id`),
  ADD UNIQUE KEY `file_path_name` (`file_path_name`);

--
-- Indexes for table `folder_relation_table`
--
ALTER TABLE `folder_relation_table`
  ADD PRIMARY KEY (`subfolder_id`);

--
-- Indexes for table `folder_table`
--
ALTER TABLE `folder_table`
  ADD PRIMARY KEY (`folder_id`),
  ADD UNIQUE KEY `folder_path_name` (`folder_path_name`);

--
-- Indexes for table `hardware_asset`
--
ALTER TABLE `hardware_asset`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial_no` (`serial_no`);

--
-- Indexes for table `ha_item_type_property`
--
ALTER TABLE `ha_item_type_property`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ha_location_option`
--
ALTER TABLE `ha_location_option`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lgu_saq_dept`
--
ALTER TABLE `lgu_saq_dept`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dept_name` (`dept_name`);

--
-- Indexes for table `sa_type_property`
--
ALTER TABLE `sa_type_property`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `software_asset`
--
ALTER TABLE `software_asset`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`);

--
-- Indexes for table `user_account_table`
--
ALTER TABLE `user_account_table`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `reset_token_hash` (`reset_token_hash`);

--
-- Indexes for table `user_role_options_table`
--
ALTER TABLE `user_role_options_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_type` (`role_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_rights_table`
--
ALTER TABLE `access_rights_table`
  MODIFY `access_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=428;

--
-- AUTO_INCREMENT for table `admin_account_tb`
--
ALTER TABLE `admin_account_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `file_table`
--
ALTER TABLE `file_table`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `folder_relation_table`
--
ALTER TABLE `folder_relation_table`
  MODIFY `subfolder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `folder_table`
--
ALTER TABLE `folder_table`
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `hardware_asset`
--
ALTER TABLE `hardware_asset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `ha_item_type_property`
--
ALTER TABLE `ha_item_type_property`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `ha_location_option`
--
ALTER TABLE `ha_location_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `lgu_saq_dept`
--
ALTER TABLE `lgu_saq_dept`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sa_type_property`
--
ALTER TABLE `sa_type_property`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `software_asset`
--
ALTER TABLE `software_asset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_account_table`
--
ALTER TABLE `user_account_table`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_role_options_table`
--
ALTER TABLE `user_role_options_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
