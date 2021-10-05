-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 05, 2021 at 02:30 AM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sportizza_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_user_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `administration_staff`
--

CREATE TABLE `administration_staff` (
  `user_id` int(9) NOT NULL,
  `sports_arena_id` int(9) NOT NULL,
  `manager_user_id` int(9) DEFAULT NULL,
  `manager_sports_arena_id` int(9) DEFAULT NULL,
  `profile_sports_arena_id` int(9) DEFAULT NULL,
  `s_a_profile_id` int(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `administration_staff_manages_facility`
--

CREATE TABLE `administration_staff_manages_facility` (
  `facility_id` int(9) NOT NULL,
  `administration_staff_user_id` int(9) NOT NULL,
  `administration_staff_sports_arena_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `administration_staff_manages_time_slot`
--

CREATE TABLE `administration_staff_manages_time_slot` (
  `time_slot_id` int(9) NOT NULL,
  `administration_staff_user_id` int(9) NOT NULL,
  `administration_staff_sports_arena_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admin_manages_feedback`
--

CREATE TABLE `admin_manages_feedback` (
  `admin_user_id` int(9) NOT NULL,
  `feedback_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admin_manages_sports_arena`
--

CREATE TABLE `admin_manages_sports_arena` (
  `sports_arena_id` int(9) NOT NULL,
  `admin_user_id` int(9) NOT NULL,
  `task` enum('verification','complaint_handling','blacklisting','blocking') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admin_manages_user`
--

CREATE TABLE `admin_manages_user` (
  `user_id` int(9) NOT NULL,
  `admin_user_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(9) NOT NULL,
  `customer_user_id` int(9) NOT NULL,
  `booking_date` date NOT NULL,
  `booked_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `payment_status` enum('paid','unpaid') NOT NULL DEFAULT 'unpaid',
  `payment_method` enum('cash','card','') NOT NULL,
  `price_per_booking` float NOT NULL,
  `facility_id` int(9) NOT NULL,
  `security_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `sports_arena_id` int(9) NOT NULL,
  `invoice_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `booking_cancellation`
--

CREATE TABLE `booking_cancellation` (
  `cancellation_id` int(9) NOT NULL,
  `reason` varchar(500) NOT NULL,
  `cancellation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `manager_sports_arena_id` int(9) DEFAULT NULL,
  `administration_staff_sports_arena_id` int(9) DEFAULT NULL,
  `manager_user_id` int(9) DEFAULT NULL,
  `administration_staff_user_id` int(9) DEFAULT NULL,
  `customer_user_id` int(9) NOT NULL,
  `booking_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `booking_handling_staff`
--

CREATE TABLE `booking_handling_staff` (
  `user_id` int(9) NOT NULL,
  `sports_arena_id` int(9) NOT NULL,
  `manager_user_id` int(9) DEFAULT NULL,
  `manager_sports_arena_id` int(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `booking_timeslot`
--

CREATE TABLE `booking_timeslot` (
  `timeslot_id` int(9) NOT NULL,
  `booking_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_user_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CUSTOMER table';

-- --------------------------------------------------------

--
-- Table structure for table `customer_profile`
--

CREATE TABLE `customer_profile` (
  `customer_user_id` int(9) NOT NULL,
  `customer_profile_id` int(9) NOT NULL,
  `security_status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CUSTOMER Profile table';

-- --------------------------------------------------------

--
-- Table structure for table `facility`
--

CREATE TABLE `facility` (
  `facility_id` int(9) NOT NULL,
  `facility_name` varchar(50) NOT NULL,
  `sports_arena_id` int(9) NOT NULL,
  `security_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `manager_user_id` int(9) NOT NULL,
  `manager_sports_arena_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `faq_id` int(9) NOT NULL,
  `question` varchar(300) NOT NULL,
  `answer` varchar(1500) NOT NULL,
  `security_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `admin_user_id` int(9) NOT NULL,
  `type` enum('customer','sports_arena') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `favourite_list`
--

CREATE TABLE `favourite_list` (
  `fav_list_id` int(9) NOT NULL,
  `customer_profile_id` int(9) NOT NULL,
  `security_status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Favourite list table';

-- --------------------------------------------------------

--
-- Table structure for table `favourite_list_sports_arena`
--

CREATE TABLE `favourite_list_sports_arena` (
  `fav_list_id` int(9) NOT NULL,
  `sports_arena_id` int(9) NOT NULL,
  `security_status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(9) NOT NULL,
  `booking_id` int(9) NOT NULL,
  `feedback_rating` enum('1','2','3','4','5') NOT NULL,
  `sports_arena_id` int(9) NOT NULL,
  `description` varchar(1500) NOT NULL,
  `security_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `customer_user_id` int(9) NOT NULL,
  `posted_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(9) NOT NULL,
  `payment_method` enum('cash','card','') NOT NULL,
  `net_amount` float NOT NULL DEFAULT '0',
  `security_status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `user_id` int(9) NOT NULL,
  `sports_arena_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `user_id` int(9) NOT NULL,
  `notification_id` int(9) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subject` varchar(200) NOT NULL,
  `security_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `priority` enum('low','high') NOT NULL DEFAULT 'low',
  `notification_status` enum('read','unread') NOT NULL DEFAULT 'unread',
  `description` varchar(1500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(9) NOT NULL,
  `invoice_id` int(9) NOT NULL,
  `booking_id` int(9) NOT NULL,
  `customer_user_id` int(9) NOT NULL,
  `net_amount` float NOT NULL,
  `security_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `payment_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `refund`
--

CREATE TABLE `refund` (
  `refund_id` int(9) NOT NULL,
  `payment_id` int(9) NOT NULL,
  `invoice_id` int(9) NOT NULL,
  `booking_id` int(9) NOT NULL,
  `customer_user_id` int(9) NOT NULL,
  `account_no` char(12) NOT NULL,
  `benficiary_name` varchar(100) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `bank_name` varchar(50) NOT NULL,
  `refund_status` enum('paid','unpaid') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sports_arena`
--

CREATE TABLE `sports_arena` (
  `sports_arena_id` int(9) NOT NULL,
  `sa_name` varchar(100) NOT NULL,
  `security_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `registered_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='sports arena table';

-- --------------------------------------------------------

--
-- Table structure for table `sports_arena_booking`
--

CREATE TABLE `sports_arena_booking` (
  `sports_arena_id` int(9) NOT NULL,
  `booking_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sports_arena_profile`
--

CREATE TABLE `sports_arena_profile` (
  `sports_arena_id` int(9) NOT NULL,
  `s_a_profile_id` int(9) NOT NULL,
  `sa_name` varchar(50) NOT NULL,
  `account_status` enum('active','inactive','blacklist','') NOT NULL DEFAULT 'active',
  `location` varchar(30) NOT NULL,
  `google_map_link` varchar(500) NOT NULL,
  `profile_photo` varchar(100) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `category` varchar(20) NOT NULL,
  `security_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `payment_method` enum('card','cash','both') DEFAULT NULL,
  `other_facilities` varchar(500) NOT NULL,
  `contact_no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sports_arena_profile_photo`
--

CREATE TABLE `sports_arena_profile_photo` (
  `sa_profile_id` int(9) NOT NULL,
  `photo1_name` varchar(500) NOT NULL,
  `photo2_name` varchar(500) NOT NULL,
  `photo3_name` varchar(500) NOT NULL,
  `photo4_name` varchar(500) NOT NULL,
  `photo5_name` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `system_report`
--

CREATE TABLE `system_report` (
  `s_report_id` int(9) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('user_data','customer_data','sports_arena_data','transaction_volume','booking_data') NOT NULL,
  `admin_user_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `s_arena_report`
--

CREATE TABLE `s_arena_report` (
  `s_a_report_id` int(9) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('customer_data','booking_data','revenue_data') NOT NULL,
  `manager_user_id` int(9) NOT NULL,
  `manager_sport_arena_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `time_slot`
--

CREATE TABLE `time_slot` (
  `time_slot_id` int(9) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `price` float NOT NULL,
  `facility_id` int(9) NOT NULL,
  `security_status` enum('active','inactive') NOT NULL,
  `manager_user_id` int(9) NOT NULL,
  `manager_sports_arena_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(9) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `registered_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `security_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `account_status` enum('active','inactive','blocked','') NOT NULL DEFAULT 'inactive',
  `primary_contact` char(10) NOT NULL,
  `secondary_contact` char(10) DEFAULT NULL,
  `type` enum('Admin','Customer','Manager','AdministrationStaff','BookingHandlingStaff') NOT NULL DEFAULT 'Customer',
  `profile_pic` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_user_id`);

--
-- Indexes for table `administration_staff`
--
ALTER TABLE `administration_staff`
  ADD PRIMARY KEY (`user_id`,`sports_arena_id`),
  ADD KEY `FK TO ADMINISTRATION_STAFF FROM MANAGER` (`manager_user_id`,`manager_sports_arena_id`),
  ADD KEY `FK TO ADMINISTRATION_STAFF FROM SPORTS_ARENA` (`sports_arena_id`),
  ADD KEY `FK TO ADMINISTRATION_STAFF FROM USER` (`user_id`),
  ADD KEY `FK TO ADMINISTRATION_STAFF FROM PROFILE` (`s_a_profile_id`,`profile_sports_arena_id`);

--
-- Indexes for table `administration_staff_manages_facility`
--
ALTER TABLE `administration_staff_manages_facility`
  ADD PRIMARY KEY (`facility_id`,`administration_staff_user_id`,`administration_staff_sports_arena_id`),
  ADD KEY `FK TO ADMINISTRATION_STAFF_MANAGES_FACILITY FROM FACILITY` (`facility_id`),
  ADD KEY `FK TO ADMINISTRATION_STAFF_MANAGES_FACILITY FROM ADMINISTR_STAFF` (`administration_staff_user_id`,`administration_staff_sports_arena_id`);

--
-- Indexes for table `administration_staff_manages_time_slot`
--
ALTER TABLE `administration_staff_manages_time_slot`
  ADD PRIMARY KEY (`time_slot_id`,`administration_staff_user_id`,`administration_staff_sports_arena_id`),
  ADD KEY `FK TO ADMINISTRATION_STAFF_MANGES_TIME_SLOT FROM ADMINISTR_STAFF` (`administration_staff_user_id`,`administration_staff_sports_arena_id`),
  ADD KEY `FK TO ADMINISTRATION_STAFF_MANGES_TIME_SLOT FROM TIMESLOT` (`time_slot_id`);

--
-- Indexes for table `admin_manages_feedback`
--
ALTER TABLE `admin_manages_feedback`
  ADD PRIMARY KEY (`admin_user_id`,`feedback_id`),
  ADD KEY `FK TO ADMIN_MANAGES_FEEDBACK FROM FEEDBACK` (`feedback_id`),
  ADD KEY `FK TO ADMIN_MANAGES_FEEDBACK FROM ADMIN` (`admin_user_id`);

--
-- Indexes for table `admin_manages_sports_arena`
--
ALTER TABLE `admin_manages_sports_arena`
  ADD PRIMARY KEY (`sports_arena_id`,`admin_user_id`),
  ADD KEY `FK TO ADMIN_MANGES_SPORTS_ARENA FROM ADMIN` (`admin_user_id`),
  ADD KEY `FK TO ADMIN_MANGES_SPORTS_ARENA FROM SP ARENA` (`sports_arena_id`),
  ADD KEY `FK TO ADMIN_MANGES_SPORTS_ARENA TO ADMIN` (`admin_user_id`),
  ADD KEY `FK TO ADMIN_MANAGES_SPORTS_ARENA TO ADMIN` (`admin_user_id`);

--
-- Indexes for table `admin_manages_user`
--
ALTER TABLE `admin_manages_user`
  ADD PRIMARY KEY (`user_id`,`admin_user_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `FK TO BOOKING FROM CUSTOMER` (`customer_user_id`),
  ADD KEY `FK TO BOOKING FROM FACILITY` (`facility_id`),
  ADD KEY `FK TO BOOKING FROM SPORTS_ARENA` (`sports_arena_id`);

--
-- Indexes for table `booking_cancellation`
--
ALTER TABLE `booking_cancellation`
  ADD PRIMARY KEY (`cancellation_id`);

--
-- Indexes for table `booking_handling_staff`
--
ALTER TABLE `booking_handling_staff`
  ADD PRIMARY KEY (`user_id`,`sports_arena_id`),
  ADD KEY `FK TO BOOKING_HANDLING_STAFF FROM USER` (`user_id`),
  ADD KEY `FK TO BOOKING_HANDLING_STAFF FROM SPORTS_ARENA` (`sports_arena_id`),
  ADD KEY `FK TO BOOKING_HANDLING_STAFF FROM MANAGER` (`manager_user_id`,`manager_sports_arena_id`);

--
-- Indexes for table `booking_timeslot`
--
ALTER TABLE `booking_timeslot`
  ADD PRIMARY KEY (`timeslot_id`,`booking_id`),
  ADD KEY `FK TO BOOKING_TIMESLOT FROM BOOKING` (`booking_id`),
  ADD KEY `FK TO BOOKING_TIMESLOT FROM TIMESLOT` (`timeslot_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_user_id`);

--
-- Indexes for table `customer_profile`
--
ALTER TABLE `customer_profile`
  ADD PRIMARY KEY (`customer_profile_id`),
  ADD KEY `FK TO FACILITY FROM CUSTOMER` (`customer_user_id`);

--
-- Indexes for table `facility`
--
ALTER TABLE `facility`
  ADD PRIMARY KEY (`facility_id`),
  ADD KEY `FK TO FACILITY FROM SPORTS_ARENA` (`sports_arena_id`),
  ADD KEY `FK TO FACILITY FROM MANAGER` (`manager_user_id`,`manager_sports_arena_id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`faq_id`),
  ADD KEY `FK TO FAQ FROM ADMIN` (`admin_user_id`);

--
-- Indexes for table `favourite_list`
--
ALTER TABLE `favourite_list`
  ADD PRIMARY KEY (`fav_list_id`),
  ADD KEY `FK TO FAVOURITE_LIST FROM CUSTOMER_PROFILE` (`customer_profile_id`);

--
-- Indexes for table `favourite_list_sports_arena`
--
ALTER TABLE `favourite_list_sports_arena`
  ADD PRIMARY KEY (`fav_list_id`,`sports_arena_id`),
  ADD KEY `FK TO FAV_LIST_SP_ARENA FROM FAVOURITE_LIST` (`fav_list_id`),
  ADD KEY `FK TO FAV_LIST_SP_ARENA FROM SPORTS_ARENA` (`sports_arena_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `FK TO FEEDBACK FROM BOOKING` (`booking_id`),
  ADD KEY `FK TO FEEDBACK FROM SPORTS_ARENA` (`sports_arena_id`),
  ADD KEY `FK TO FEEDBACK FROM CUSTOMER` (`customer_user_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`user_id`,`sports_arena_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `FK TO NOTIFICATION FROM USER` (`user_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `FK TO PAYMENT FROM INVOICE` (`invoice_id`),
  ADD KEY `FK TO PAYMENT FROM BOOKING` (`booking_id`),
  ADD KEY `FK TO PAYMENT FROM CUSTOMER` (`customer_user_id`);

--
-- Indexes for table `refund`
--
ALTER TABLE `refund`
  ADD PRIMARY KEY (`refund_id`),
  ADD KEY `FK TO REFUND FROM PAYMENT` (`payment_id`),
  ADD KEY `FK TO REFUND FROM INVOICE` (`invoice_id`),
  ADD KEY `FK TO REFUND FROM BOOKING` (`booking_id`),
  ADD KEY `FK TO REFUND FROM CUSTOMER` (`customer_user_id`);

--
-- Indexes for table `sports_arena`
--
ALTER TABLE `sports_arena`
  ADD PRIMARY KEY (`sports_arena_id`);

--
-- Indexes for table `sports_arena_booking`
--
ALTER TABLE `sports_arena_booking`
  ADD PRIMARY KEY (`sports_arena_id`,`booking_id`),
  ADD KEY `FK TO SPORTS_ARENA_BOOKING FROM BOOKING` (`booking_id`);

--
-- Indexes for table `sports_arena_profile`
--
ALTER TABLE `sports_arena_profile`
  ADD PRIMARY KEY (`s_a_profile_id`),
  ADD KEY `FK TO SPORTS_ARENA_PROFILE FROM SPORTS_ARENA` (`sports_arena_id`);

--
-- Indexes for table `sports_arena_profile_photo`
--
ALTER TABLE `sports_arena_profile_photo`
  ADD PRIMARY KEY (`sa_profile_id`),
  ADD KEY `FOREIGN KEY FROM SPORTS_ARENA_PROFILE_PHOTO TO SPORTS_ARENA_PROF` (`sa_profile_id`);

--
-- Indexes for table `system_report`
--
ALTER TABLE `system_report`
  ADD PRIMARY KEY (`s_report_id`),
  ADD KEY `FK TO SYSTEM_REPORT FROM ADMIN` (`admin_user_id`);

--
-- Indexes for table `s_arena_report`
--
ALTER TABLE `s_arena_report`
  ADD PRIMARY KEY (`s_a_report_id`),
  ADD KEY `FK TO S_ARENA_REPORT FROM MANAGER` (`manager_user_id`,`manager_sport_arena_id`);

--
-- Indexes for table `time_slot`
--
ALTER TABLE `time_slot`
  ADD PRIMARY KEY (`time_slot_id`),
  ADD KEY `FK TO TIME_SLOT FROM FACILITY` (`facility_id`),
  ADD KEY `FK TO TIME_SLOT FROM MANAGER` (`manager_user_id`,`manager_sports_arena_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000000;

--
-- AUTO_INCREMENT for table `booking_cancellation`
--
ALTER TABLE `booking_cancellation`
  MODIFY `cancellation_id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_user_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000000;

--
-- AUTO_INCREMENT for table `customer_profile`
--
ALTER TABLE `customer_profile`
  MODIFY `customer_profile_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000000;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `faq_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000000;

--
-- AUTO_INCREMENT for table `favourite_list`
--
ALTER TABLE `favourite_list`
  MODIFY `fav_list_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000000;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000000;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000000;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000000;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refund`
--
ALTER TABLE `refund`
  MODIFY `refund_id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sports_arena`
--
ALTER TABLE `sports_arena`
  MODIFY `sports_arena_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000000;

--
-- AUTO_INCREMENT for table `sports_arena_profile`
--
ALTER TABLE `sports_arena_profile`
  MODIFY `s_a_profile_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000000;

--
-- AUTO_INCREMENT for table `system_report`
--
ALTER TABLE `system_report`
  MODIFY `s_report_id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `s_arena_report`
--
ALTER TABLE `s_arena_report`
  MODIFY `s_a_report_id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_slot`
--
ALTER TABLE `time_slot`
  MODIFY `time_slot_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000000;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000000;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `FK TO ADMIN FROM USER` FOREIGN KEY (`admin_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `manager`
--
ALTER TABLE `manager`
  ADD CONSTRAINT `FK TO MANAGER FROM USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
