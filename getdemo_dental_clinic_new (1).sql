-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 07, 2025 at 06:32 PM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `getdemo_dental_clinic_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `clinic`
--

CREATE TABLE `clinic` (
  `clinic_id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `doctor` varchar(45) DEFAULT NULL,
  `mobile_no` bigint(20) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `iorder_id` int(11) DEFAULT '0',
  `iStatus` int(11) DEFAULT '1',
  `isDelete` int(11) DEFAULT '0',
  `strIP` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `email` varchar(100) DEFAULT NULL,
  `casePrefix` varchar(100) DEFAULT NULL,
  `casePostfix` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clinic`
--

INSERT INTO `clinic` (`clinic_id`, `name`, `doctor`, `mobile_no`, `password`, `state`, `city`, `address`, `start_date`, `end_date`, `iorder_id`, `iStatus`, `isDelete`, `strIP`, `created_at`, `updated_at`, `logo`, `user_id`, `email`, `casePrefix`, `casePostfix`) VALUES
(15, 'Krishana Clinic', 'Digen', 9898808754, '$2y$10$V50L/fICaS2Sk09qqgBkauA2gesQh4q5K.242rBdsoBjWsiB28wXS', 'Gujarat', 'Ahmedabad', NULL, NULL, NULL, 0, 1, 0, NULL, '2025-05-22 13:07:38', '2025-05-22 13:28:20', '1747920500_22052025185820.png', 33, NULL, 'KRISHANACLINIC-', '-2025'),
(16, 'Mihir', 'harsil', 7043447511, '$2y$10$R6scMFms88gVRjCTKqLo2ulSXnXyIxSwaXyNSUZjecJTGN2wbM2Ou', 'Gujarat', 'Ahmedabad', 'B/11 swastik hospital near  vatva canal road\r\nahmedabad', NULL, NULL, 0, 1, 0, NULL, '2025-05-27 13:11:54', '2025-05-27 13:12:25', '1748351514_27052025184154.png', 34, 'harshil11@gmail.com', NULL, NULL),
(17, 'kush clinic', 'vaibhav', 9880765432, '$2y$10$g5g.7MtK9GQoTsmCiC2EPeH4xh5erMEaNhmaRPc5v9XMDTIlTKAGW', 'Gujarat', 'Ahmedabad', 'Narol', NULL, NULL, 0, 1, 0, NULL, '2025-05-28 11:16:02', '2025-05-28 11:16:02', '1748430962_28052025164602.png', 36, 'vaibhav@gmail.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `clinic_case_counters`
--

CREATE TABLE `clinic_case_counters` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) DEFAULT NULL,
  `case_type` varchar(100) DEFAULT NULL,
  `last_number` int(11) DEFAULT '0',
  `iStatus` int(11) DEFAULT '1',
  `iSDelete` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `case_no` varchar(255) DEFAULT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `postfix` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clinic_case_counters`
--

INSERT INTO `clinic_case_counters` (`id`, `clinic_id`, `case_type`, `last_number`, `iStatus`, `iSDelete`, `created_at`, `updated_at`, `case_no`, `prefix`, `postfix`) VALUES
(4, 14, NULL, 1, 1, 0, '2025-05-22 13:01:48', '2025-05-22 13:01:48', NULL, NULL, NULL),
(5, 15, NULL, 2, 1, 0, '2025-05-22 13:07:38', '2025-05-23 12:42:46', 'Krishana-0001-2025', 'Krishana', '2025'),
(6, 16, NULL, 3, 1, 0, '2025-05-27 13:11:54', '2025-05-28 07:07:06', 'Mihir-0001-2025', 'Mihir', '2025'),
(7, 17, NULL, 1, 1, 0, '2025-05-28 11:16:02', '2025-05-28 11:16:02', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Clinic_order`
--

CREATE TABLE `Clinic_order` (
  `Clinic_order_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `amount` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Clinic_order`
--

INSERT INTO `Clinic_order` (`Clinic_order_id`, `start_date`, `end_date`, `amount`, `created_at`, `updated_at`) VALUES
(1, '2025-04-10', '2025-04-12', 1500, '2025-04-10 11:40:41', '2025-04-10 11:40:41'),
(2, '2025-04-10', '2025-04-11', 500, '2025-04-12 13:45:53', '2025-04-12 13:45:53'),
(3, '2025-04-01', '2025-04-12', 2000, '2025-04-14 04:58:53', '2025-04-14 04:58:53'),
(4, '2025-04-01', '2025-04-13', 2500, '2025-04-14 05:03:40', '2025-04-14 05:03:40');

-- --------------------------------------------------------

--
-- Table structure for table `concern_forms`
--

CREATE TABLE `concern_forms` (
  `iConcernFormId` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL DEFAULT '0',
  `strConcernFormTitle` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `strConcernFormText` text COLLATE utf8_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `strIP` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `concern_forms`
--

INSERT INTO `concern_forms` (`iConcernFormId`, `clinic_id`, `strConcernFormTitle`, `strConcernFormText`, `deleted_at`, `created_at`, `updated_at`, `strIP`) VALUES
(11, 6, 'Poor prognosis concern form', '\"We would like to inform you that the prognosis for the treating tooth is poor, significantly reducing its longevity. In the event of any further issues with this particular tooth, regrettably, we may be unable to preserve it.\"', NULL, '2025-04-18 06:32:16', '2025-04-18 06:32:16', '103.1.100.226'),
(27, 15, 'test', 'test', NULL, '2025-05-24 13:58:17', '2025-05-24 13:58:17', '103.1.100.226'),
(28, 15, 'demo', 'demo', NULL, '2025-05-24 13:59:40', '2025-05-24 13:59:40', '103.1.100.226'),
(29, 15, 'demo11', 'demo11', NULL, '2025-05-24 14:02:24', '2025-05-24 14:02:24', '103.1.100.226'),
(30, 16, 'Poor prognosis concern form', '\"We would like to inform you that the prognosis for the treating tooth is poor, significantly reducing its longevity. In the event of any further issues with this particular tooth, regrettably, we may be unable to preserve it.\"', NULL, '2025-05-28 05:43:15', '2025-05-28 05:43:15', '103.1.100.226'),
(31, 16, 'Crown or bridge without rct concern form', '\"I acknowledge that sensitivity and pain in teeth may occur following the placement of a prosthesis due to pressure on the pulp or gum tissues. I have been informed of this situation and hereby grant permission for the procedure. \"', NULL, '2025-05-28 05:43:53', '2025-05-28 05:43:53', '103.1.100.226'),
(32, 16, 'demo', 'demo', NULL, '2025-06-04 06:39:01', '2025-06-04 06:39:01', '103.1.100.226');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_name` varchar(100) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `address` text,
  `pincode` varchar(6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `doctor_name`, `mobile`, `address`, `pincode`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 'Dr. Mehul Prajapati', '8070605010', 'India', '382443', '2025-04-01 07:11:36', '2025-04-01 07:13:03', 0),
(2, 'Dr. Nilesh Patel', '7040501060', 'India', '382443', '2025-04-01 07:12:18', '2025-04-01 07:12:18', 0),
(3, 'Dr. Shailesh Verma', '6070904050', 'India', '382443', '2025-04-01 07:12:47', '2025-04-01 07:12:47', 0),
(7, 'Dr. Tarang Parmar', '7486984607', 'Ahmadabad  9874562100', '380060', '2025-04-14 07:05:34', '2025-04-15 12:19:06', 7),
(8, 'Dr. Dhruvi', '7990799215', 'Ahmadabad', '380008', '2025-04-14 07:06:37', '2025-04-15 12:18:19', 7),
(10, 'Dr. Rakesh Mehta', '9876543210', 'Narol', '789009', '2025-05-12 06:29:35', '2025-05-12 06:29:35', 6),
(11, 'Dr. Neha Sharma', '7689076655', 'Aashram Road', '382400', '2025-05-12 06:31:03', '2025-05-12 06:31:03', 6),
(12, 'Dr. mignesh chhatrala', '9904500062', 'Star bazar Ahmedabad', '454545', '2025-05-13 05:52:08', '2025-05-13 05:52:08', 6),
(14, 'Dr. Dhruvi', '9874125632', 'Bhairavnath', '380060', '2025-05-19 05:59:15', '2025-05-21 04:52:23', 6),
(16, 'Dr. Dhruvi', '9824773136', '1, anurag flat,\r\nBhairavnath Cross Road, Maninagar', '380060', '2025-05-21 05:03:22', '2025-05-21 05:03:22', 10),
(17, 'Mihir', '9725123569', 'Isanpur', '382443', '2025-05-22 13:18:22', '2025-05-22 13:18:22', 15),
(18, 'krupa', '4444444444', 'vastral', '382405', '2025-05-27 13:29:50', '2025-05-27 13:29:50', 16);

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `treatment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `patient_treatment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `document` varchar(255) NOT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `patient_id`, `treatment_id`, `patient_treatment_id`, `document`, `comment`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 1, 1, 2, '1743498497.png', 'Do like this....', '2025-04-01 09:08:17', '2025-04-01 09:08:17', 0),
(2, 1, 2, 1, '1743498564.png', 'Do like this...', '2025-04-01 09:09:24', '2025-04-01 09:09:24', 0),
(3, 2, 4, 3, '1743503532.png', 'Do like this!!!', '2025-04-01 10:32:12', '2025-04-01 10:32:12', 0),
(10, 5, 12, 7, '1744614534.png', NULL, '2025-04-14 07:08:54', '2025-04-14 07:08:54', 7),
(11, 5, NULL, NULL, '1744614561.pdf', NULL, '2025-04-14 07:09:21', '2025-04-14 07:09:21', 7),
(16, 9, 23, 10, '1747031695.pdf', 'this document is about tooth cleaning', '2025-05-12 06:34:55', '2025-05-12 06:34:55', 6),
(17, 12, 23, 11, '1747142022.pdf', 'ghghghgdg', '2025-05-13 13:13:42', '2025-05-13 13:13:42', 6),
(18, 13, 25, 12, '1747635418.png', NULL, '2025-05-19 06:16:58', '2025-05-19 06:16:58', 6),
(19, 16, NULL, NULL, '1753882789.jpeg', 'this is add for testing', '2025-07-30 13:39:49', '2025-07-30 13:39:49', 15);

-- --------------------------------------------------------

--
-- Table structure for table `dosages`
--

CREATE TABLE `dosages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dosage` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dosages`
--

INSERT INTO `dosages` (`id`, `dosage`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, '1-1-1', '2025-04-01 07:03:43', '2025-04-01 07:03:43', 0),
(2, '1-0-1', '2025-04-01 07:03:54', '2025-04-01 07:03:54', 0),
(3, '0-1-0', '2025-04-01 07:04:04', '2025-04-01 07:04:04', 0),
(4, '1-0-0', '2025-04-01 07:04:15', '2025-04-01 07:04:15', 0),
(5, '0-0-1', '2025-04-01 07:04:20', '2025-04-01 07:04:20', 0),
(7, '1-1-0', '2025-04-11 07:04:09', '2025-05-01 10:45:57', 6),
(10, '1-1-1', '2025-04-14 05:45:04', '2025-05-01 10:45:47', 6),
(11, '1-0-0', '2025-05-01 08:35:22', '2025-05-01 10:46:18', 6),
(13, '0-1-0', '2025-05-01 10:46:32', '2025-05-01 10:46:32', 6),
(14, '0-0-1', '2025-05-01 10:46:42', '2025-05-01 10:46:42', 6),
(15, '1-0-1', '2025-05-01 10:46:56', '2025-05-01 10:46:56', 6),
(17, '1-1-0', '2025-05-24 09:53:54', '2025-05-24 09:53:54', 15),
(18, '1-1-0', '2025-05-27 13:24:22', '2025-05-27 13:24:22', 16);

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `expense_id` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `amount` int(11) DEFAULT '0',
  `enter_by` varchar(255) DEFAULT NULL,
  `mode` int(11) DEFAULT '0' COMMENT '1= online 2= cash',
  `iStatus` int(11) DEFAULT '1',
  `IsDelete` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expense`
--

INSERT INTO `expense` (`expense_id`, `item_name`, `amount`, `enter_by`, `mode`, `iStatus`, `IsDelete`, `created_at`, `updated_at`, `clinic_id`) VALUES
(4, 'chair', 2000, 'Apollo', 2, 1, 0, '2025-05-28 07:09:58', '2025-05-28 07:09:58', 16);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labs`
--

CREATE TABLE `labs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lab_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `address` text,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `labs`
--

INSERT INTO `labs` (`id`, `lab_name`, `contact_person`, `mobile`, `address`, `email`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 'Evergreen Laboratories', 'Sahil Lodha', '9080706050', '1,Vishalnagar,Isanpur,Ahmedabad', 'evergreen@gmail.com', '2025-04-01 05:48:59', '2025-04-01 05:48:59', 0),
(2, 'Black Hole Labs', 'Priti Lodha', '8070605040', '2,Vishalnagar,Isanpur,Ahmedabad', 'blackhole@gmail.com', '2025-04-01 05:50:17', '2025-04-01 05:50:17', 0),
(3, 'Nano Innovation Labs', 'Jimish Lodha', '7060504030', '3,Vishalnagar,Isanpur,Ahmedabad', 'nano@gmail.com', '2025-04-01 05:51:12', '2025-04-01 05:51:12', 0),
(7, 'test', 'Nidhi', '9874563201', 'test', 'dev1.apolloinfotech@gmail.com', '2025-04-12 10:37:51', '2025-04-12 10:37:51', 7),
(8, 'test 123', 'Bansari Patel', '9874563201', 'Sola\r\nScience City', 'dev5.apolloinfotech@gmail.com', '2025-04-17 06:28:50', '2025-04-17 06:29:47', 7),
(11, 'Krishana', 'Gita', '9899999999', 'Narol', 'Krishana@gmail.com', '2025-05-12 06:20:12', '2025-05-12 06:20:12', 6),
(12, 'Dr. Lal PathLabs', 'Dr. Lal', '9723151289', 'Maninagar jawahar chok', 'dev3.apolloinfotech@gmail.com', '2025-05-12 06:21:42', '2025-05-12 06:21:42', 6),
(13, 'Thyrocare Technologies Ltd', 'Digen', '7689076655', 'Isanpur', 'Digen@gmail.com', '2025-05-12 06:22:31', '2025-05-12 06:22:31', 6),
(14, 'test', 'test', '9876541323', NULL, NULL, '2025-05-14 13:15:51', '2025-05-14 13:15:51', 10),
(16, 'smart', 'sandeep', '9999999999', 'Narol', 'sandeep@gmail.com', '2025-05-22 13:20:50', '2025-05-22 13:20:50', 15),
(17, 'Krishana', 'Kashyap', '9898808754', 'Isanpur Ahmedabad', 'krishana@gmail.com', '2025-05-27 13:24:12', '2025-05-27 13:24:12', 16);

-- --------------------------------------------------------

--
-- Table structure for table `labworks`
--

CREATE TABLE `labworks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `lab_id` bigint(20) UNSIGNED NOT NULL,
  `treatment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `patient_treatment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entry_date` date NOT NULL,
  `collection_date` datetime DEFAULT NULL,
  `received_date` datetime DEFAULT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `labworks`
--

INSERT INTO `labworks` (`id`, `patient_id`, `lab_id`, `treatment_id`, `patient_treatment_id`, `entry_date`, `collection_date`, `received_date`, `comment`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 1, 3, 1, 2, '2025-04-01', '2025-04-02 12:49:07', NULL, 'OK', '2025-04-01 09:12:57', '2025-04-02 07:19:07', 0),
(2, 1, 1, 2, 1, '2025-04-01', '2025-04-02 12:49:10', NULL, 'OK', '2025-04-01 09:13:47', '2025-04-02 07:19:10', 0),
(3, 2, 2, 4, 3, '2025-04-01', '2025-04-02 12:49:27', NULL, 'OK', '2025-04-01 10:31:27', '2025-04-02 07:19:27', 0),
(13, 9, 12, 23, 10, '2025-05-13', '2025-05-19 11:11:46', NULL, 'Tooth cleaning report give in lab', '2025-05-12 06:36:00', '2025-05-19 05:41:46', 6),
(14, 12, 12, 23, 11, '2025-05-14', NULL, NULL, 'hjhgjhgjghjghj', '2025-05-13 13:11:57', '2025-05-13 13:11:57', 6),
(18, 17, 17, 30, 14, '2025-05-29', '2025-05-28 14:24:04', NULL, NULL, '2025-05-28 07:11:19', '2025-05-28 08:54:04', 16);

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_registers`
--

CREATE TABLE `maintenance_registers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `complain_details` text NOT NULL,
  `repair_person_name` varchar(255) NOT NULL,
  `repair_given_date` date NOT NULL,
  `quotation_amount` decimal(10,2) NOT NULL,
  `payment_paid_amount` decimal(10,2) NOT NULL,
  `repair_received_date` date DEFAULT NULL,
  `received_comment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `maintenance_registers`
--

INSERT INTO `maintenance_registers` (`id`, `item_name`, `complain_details`, `repair_person_name`, `repair_given_date`, `quotation_amount`, `payment_paid_amount`, `repair_received_date`, `received_comment`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 'Fan', 'It\'s not work properly', 'Afzal Qureshi', '2025-04-01', 2000.00, 2000.00, '2025-04-01', 'Thank you!!!', '2025-04-01 08:39:02', '2025-04-01 08:39:47', 0),
(2, 'Washing Machine', 'It\'s not work properly', 'Afzal Qureshi', '2025-04-01', 2000.00, 2000.00, '2025-04-01', 'Thank you!!!', '2025-04-01 08:42:34', '2025-04-01 08:43:23', 0),
(4, 'test', 'rfgrgrfgdgfdd', 'test', '2025-04-11', 1500.00, 1500.00, '2025-04-11', 'hgffghgfhfgh', '2025-04-11 08:48:18', '2025-04-11 08:49:00', 6),
(5, 'Repair Machine', 'test', 'test', '2025-04-21', 1500.00, 1500.00, '2025-04-28', 'test', '2025-04-28 09:28:04', '2025-04-28 09:38:06', 7),
(7, 'test', 'test', 'test', '2025-04-25', 1500.00, 1500.00, '2025-04-29', NULL, '2025-04-29 07:11:06', '2025-04-29 07:11:36', 7),
(8, 'Repair Machine', 'test', 'test', '2025-05-05', 1500.00, 1500.00, '2025-05-19', NULL, '2025-05-19 05:42:36', '2025-05-19 05:42:44', 6),
(9, 'aaaa', 'aaaa', 'aaa', '2025-05-21', 10000.00, 8500.00, '2025-05-21', 'a', '2025-05-21 05:52:48', '2025-05-21 05:53:38', 6),
(10, 'Repair Machine', 'fdfhdf', 'test', '2025-05-14', 5000.00, 3000.00, '2025-05-21', 'test', '2025-05-21 05:56:28', '2025-05-21 05:56:49', 10),
(11, 'Chair', 'char wheel not working', 'Mehul Shah', '2025-05-14', 120.00, 100.00, '2025-05-21', 'received', '2025-05-21 06:03:13', '2025-05-21 06:03:31', 6),
(12, 'chair', 'testing', 'test', '2025-05-23', 1200.00, 1000.00, NULL, NULL, '2025-05-23 13:26:23', '2025-05-23 13:26:23', 15),
(13, 'chair', 'wheel add', 'paras', '2025-05-28', 500.00, 200.00, NULL, NULL, '2025-05-28 07:08:30', '2025-05-28 07:08:30', 16);

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0',
  `dosage_id` int(11) NOT NULL DEFAULT '0',
  `days` varchar(255) DEFAULT NULL,
  `comment` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `medicine_name`, `created_at`, `updated_at`, `clinic_id`, `dosage_id`, `days`, `comment`) VALUES
(1, 'Paracetamol', '2025-04-01 05:51:37', '2025-04-01 05:51:37', 0, 0, NULL, NULL),
(2, 'Crocin', '2025-04-01 05:51:43', '2025-04-01 05:51:43', 0, 0, NULL, NULL),
(3, 'Corex DX Syrup', '2025-04-01 05:51:57', '2025-04-01 05:51:57', 0, 0, NULL, NULL),
(4, 'Vicks Action 500', '2025-04-01 05:52:08', '2025-04-01 05:52:08', 0, 0, NULL, NULL),
(8, 'Paracetamol 123', '2025-04-12 10:37:07', '2025-04-12 10:37:07', 7, 0, NULL, NULL),
(15, 'Amoxicillin', '2025-05-12 06:23:36', '2025-05-12 06:23:36', 6, 1, NULL, 'Often prescribed after tooth extraction or for abscesses.'),
(16, 'Ibuprofen', '2025-05-12 06:24:01', '2025-05-12 06:24:01', 6, 2, NULL, 'Helps with swelling and pain after dental procedures.'),
(17, 'Metronidazole', '2025-05-12 06:24:31', '2025-05-12 06:24:31', 6, 3, NULL, 'Often combined with Amoxicillin for severe dental infections.'),
(18, 'Chlorhexidine Mouthwash', '2025-05-12 06:24:57', '2025-05-12 06:24:57', 6, 4, NULL, 'Used post-surgery or during gum treatment.'),
(20, 'dolo', '2025-05-24 09:54:10', '2025-07-30 11:45:05', 15, 17, '5', 'tytygfhfghgf'),
(21, 'dolo', '2025-05-27 13:24:35', '2025-05-27 13:24:35', 16, 18, NULL, 'testing');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_01_12_173356_create_permission_tables', 1),
(6, '2025_03_06_115140_create_patients_table', 2),
(7, '2025_03_06_163158_create_treatments_table', 3),
(8, '2025_03_06_175624_create_labs_table', 4),
(9, '2025_03_06_182805_create_medicines_table', 5),
(10, '2025_03_06_185539_create_dosages_table', 6),
(11, '2025_03_10_113847_create_maintenance_registers_table', 7),
(12, '2025_03_10_125608_create_vendors_table', 8),
(13, '2025_03_10_142120_create_products_table', 9),
(14, '2025_03_10_145744_create_product_purchases_table', 10),
(15, '2025_03_10_182352_add_received_comment_to_maintenance_register', 11),
(16, '2025_03_11_113644_update_patients_table', 12),
(17, '2025_03_11_121624_create_doctors_table', 13),
(18, '2025_03_11_151517_create_patient_appointments_table', 14),
(19, '2025_03_11_171226_update_patient_appointments_table', 15),
(20, '2025_03_12_143755_create_patient_treatments_table', 16),
(21, '2025_03_12_144516_create_patient_treatments_table', 17),
(22, '2025_03_12_152020_create_patient_treatments_table', 18),
(23, '2025_03_13_173237_create_patient_notes_table', 19),
(24, '2025_03_13_180822_add_is_disrupted_to_patient_appointments_table', 20),
(25, '2025_03_18_124259_create_documents_table', 21),
(26, '2025_03_18_172231_create_labworks_table', 22),
(27, '2025_03_19_114414_create_orders_table', 23),
(28, '2025_03_19_114549_create_order_details_table', 23),
(29, '2025_03_21_110041_add_date_to_orders_table', 24),
(30, '2025_03_21_173631_create_payments_table', 25),
(31, '2025_03_24_123906_create_prescriptions_table', 26),
(32, '2025_03_24_124041_create_prescription_details_table', 26);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 13),
(3, 'App\\Models\\User', 15),
(3, 'App\\Models\\User', 16),
(3, 'App\\Models\\User', 21),
(3, 'App\\Models\\User', 24),
(3, 'App\\Models\\User', 28),
(3, 'App\\Models\\User', 30),
(3, 'App\\Models\\User', 35),
(3, 'App\\Models\\User', 37);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `patient_id`, `invoice_no`, `date`, `amount`, `discount`, `net_amount`, `created_at`, `updated_at`, `clinic_id`) VALUES
(2, 2, '0002', '2025-04-01', 3200.00, 200.00, 3000.00, '2025-04-01 10:55:44', '2025-04-01 10:55:44', 0),
(9, 9, '0003', '2025-05-12', 1000.00, 5.00, 995.00, '2025-05-12 07:29:04', '2025-05-12 07:29:04', 6),
(11, 13, '0004', '2025-05-19', 1050.00, 50.00, 1000.00, '2025-05-19 07:26:24', '2025-05-19 07:26:24', 6),
(13, 17, '0005', '2025-05-28', 135.00, 20.00, 115.00, '2025-05-28 08:55:18', '2025-05-28 08:55:18', 16);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `treatment_id` bigint(20) UNSIGNED NOT NULL,
  `patient_treatment_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `patient_id`, `treatment_id`, `patient_treatment_id`, `qty`, `rate`, `amount`, `discount`, `net_amount`, `created_at`, `updated_at`, `clinic_id`) VALUES
(3, 2, 2, 4, 3, 4, 800.00, 3200.00, 200.00, 3000.00, '2025-04-01 10:55:44', '2025-04-01 10:55:44', 0),
(10, 9, 9, 23, 10, 2, 500.00, 1000.00, 5.00, 995.00, '2025-05-12 07:29:04', '2025-05-12 07:29:04', 6),
(12, 11, 13, 25, 12, 7, 150.00, 1050.00, 50.00, 1000.00, '2025-05-19 07:26:24', '2025-05-19 07:26:24', 6),
(14, 13, 17, 30, 14, 3, 45.00, 135.00, 20.00, 115.00, '2025-05-28 08:55:18', '2025-05-28 08:55:18', 16);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile1` varchar(10) NOT NULL,
  `mobile2` varchar(10) NOT NULL,
  `dob` date DEFAULT NULL,
  `address` text,
  `pincode` varchar(6) DEFAULT NULL,
  `reference_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0',
  `case_no` varchar(30) DEFAULT NULL,
  `gender` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `mobile1`, `mobile2`, `dob`, `address`, `pincode`, `reference_by`, `created_at`, `updated_at`, `clinic_id`, `case_no`, `gender`) VALUES
(1, 'Rahul Yadav', '9978635079', '', '1995-04-20', 'India', '382443', NULL, '2025-04-01 08:52:01', '2025-04-01 08:52:45', 0, NULL, 0),
(2, 'Jignesh Parmar', '9157426215', '', '2003-10-15', 'India', '382443', NULL, '2025-04-01 10:22:11', '2025-04-01 10:22:11', 0, NULL, 0),
(5, 'Bansari Patel', '9987654321', '', '2003-09-18', 'Sola\r\nScience City', '380060', 'test', '2025-04-12 12:59:44', '2025-04-12 12:59:44', 7, NULL, 0),
(9, 'Mr. Arjun Verma', '7486984601', '9876012178', '2002-02-02', 'Vastral', '382401', 'Apollo', '2025-05-12 06:33:05', '2025-05-12 06:33:05', 6, 'CAS2025001', 1),
(12, 'Mignesh', '9904500629', '1212121211', '2002-05-13', 'Star bazar jodhpur cross road ahmedabad', '384020', 'Apollo', '2025-05-13 12:30:21', '2025-05-13 12:30:21', 6, 'GJ12312', 1),
(13, 'Bansari Patel', '9874563210', '9875463210', '2003-09-18', NULL, '380060', NULL, '2025-05-19 06:08:23', '2025-05-19 07:23:41', 6, '1', 2),
(14, 'Karan', '1256564789', '1234567890', NULL, NULL, NULL, NULL, '2025-05-21 06:38:47', '2025-05-21 06:38:47', 6, '12', 1),
(16, 'Aakash', '7486984607', '9898808754', '2002-02-02', 'tyrtyrtyrty', '789009', 'Apollo', '2025-05-23 12:42:46', '2025-07-30 13:37:59', 15, 'Krishana-0001-2025', 1),
(17, 'Nisha', '7486984607', '4555555555', '2002-02-02', 'narol', '789009', 'Apollo', '2025-05-28 06:12:31', '2025-05-28 06:12:31', 16, 'Mihir-0001-2025', 2),
(18, 'Nitesh', '7486984607', '9999999999', '2002-02-02', 'narol', '789009', 'Apollo', '2025-05-28 07:07:06', '2025-05-28 07:07:06', 16, 'Mihir-0002-2025', 1);

-- --------------------------------------------------------

--
-- Table structure for table `patient_appointments`
--

CREATE TABLE `patient_appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` varchar(255) DEFAULT NULL,
  `rescheduled_date` date DEFAULT NULL,
  `rescheduled_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `is_disrupted` tinyint(1) NOT NULL DEFAULT '0',
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient_appointments`
--

INSERT INTO `patient_appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `rescheduled_date`, `rescheduled_time`, `created_at`, `updated_at`, `status`, `is_disrupted`, `clinic_id`) VALUES
(1, 1, 1, '2025-04-01', '19:00:00', NULL, NULL, '2025-04-01 09:16:23', '2025-04-02 07:14:49', 1, 0, 0),
(2, 2, 2, '2025-04-02', '20:00:00', NULL, NULL, '2025-04-01 10:22:42', '2025-04-02 09:07:19', 1, 0, 0),
(8, 5, 8, '2025-04-30', '12:00:00', NULL, NULL, '2025-04-29 06:31:34', '2025-04-29 06:31:34', 0, 0, 7),
(9, 5, 7, '2025-05-22', '13:00:00', NULL, NULL, '2025-04-29 06:58:51', '2025-04-29 06:58:51', 0, 0, 7),
(10, 9, 11, '2025-05-23', '13:00:00', NULL, NULL, '2025-05-12 07:30:45', '2025-05-22 07:24:34', 1, 0, 6),
(11, 13, 11, '2025-05-20', '12:00:00', NULL, NULL, '2025-05-19 06:45:44', '2025-05-19 06:45:44', 0, 0, 6),
(13, 17, 18, '2025-05-29', '10:24:00', NULL, NULL, '2025-05-28 08:54:34', '2025-05-28 08:54:34', 0, 0, 16),
(14, 12, 17, '2025-07-31', '9:00 AM', NULL, NULL, '2025-07-30 13:34:59', '2025-07-30 13:34:59', 0, 0, 15);

-- --------------------------------------------------------

--
-- Table structure for table `patient_concern_form`
--

CREATE TABLE `patient_concern_form` (
  `patient_concern_form_id` int(11) NOT NULL,
  `concern_form_id` int(11) DEFAULT '0',
  `patient_id` int(11) DEFAULT '0',
  `clinic_id` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `iStatus` int(11) DEFAULT '1',
  `isDelete` int(11) DEFAULT '0',
  `strFileName` varchar(100) DEFAULT NULL,
  `isSubmit` int(11) DEFAULT '0' COMMENT '0=pending,1=submitted	',
  `submitedDateTime` timestamp NULL DEFAULT NULL,
  `gu_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient_concern_form`
--

INSERT INTO `patient_concern_form` (`patient_concern_form_id`, `concern_form_id`, `patient_id`, `clinic_id`, `created_at`, `updated_at`, `iStatus`, `isDelete`, `strFileName`, `isSubmit`, `submitedDateTime`, `gu_id`) VALUES
(4, 12, 6, 6, '2025-04-22 08:35:18', '2025-04-22 08:36:12', 1, 0, '4__Krunal_Shah.pdf', 1, '2025-04-22 08:36:12', NULL),
(5, 11, 6, 6, '2025-04-23 05:53:25', '2025-04-23 05:53:49', 1, 0, '5__Krunal_Shah.pdf', 1, '2025-04-23 05:53:49', NULL),
(6, 12, 5, 7, '2025-04-25 10:54:04', '2025-04-25 10:54:23', 1, 0, '6__Bansari_Patel.pdf', 1, '2025-04-25 10:54:23', NULL),
(7, 11, 9, 6, '2025-05-12 06:56:32', '2025-05-12 06:57:37', 1, 0, '7_CAS2025001_Mr._Arjun_Verma.pdf', 1, '2025-05-12 06:57:37', NULL),
(8, 11, 13, 6, '2025-05-19 06:36:57', '2025-05-19 06:38:29', 1, 0, '8_1_Bansari_Patel.pdf', 1, '2025-05-19 06:38:29', NULL),
(9, 11, 15, 15, '2025-05-23 07:02:08', '2025-05-23 07:02:08', 1, 0, NULL, 0, NULL, NULL),
(20, 27, 16, 15, '2025-05-24 13:58:28', '2025-05-24 13:59:02', 1, 0, '20_Krishana-0001-2025_Aakash.pdf', 1, '2025-05-24 13:59:02', 'f0b7f459-87b9-46e1-8a4a-3c17b314f84b'),
(21, 29, 16, 15, '2025-05-24 14:02:39', '2025-05-24 14:03:26', 1, 0, '21_Krishana-0001-2025_Aakash.pdf', 1, '2025-05-24 14:03:26', '4b092a6b-9a7a-435a-869a-b9d809689fef'),
(22, 28, 16, 15, '2025-05-24 14:06:22', '2025-05-24 14:06:44', 1, 0, '22_Krishana-0001-2025_Aakash.pdf', 1, '2025-05-24 14:06:44', 'a4685635-800d-4bf0-915a-f5ef9e0a6be5'),
(23, 31, 17, 16, '2025-05-28 07:25:07', '2025-05-28 08:38:21', 1, 0, '23_Mihir-0001-2025_Nisha.pdf', 1, '2025-05-28 08:38:21', '0382ef29-522a-484b-81dd-70a55a096236'),
(24, 30, 17, 16, '2025-05-28 08:45:11', '2025-05-28 08:46:08', 1, 0, '24_Mihir-0001-2025_Nisha.pdf', 1, '2025-05-28 08:46:08', 'd1c08bc2-67d4-426f-a9a2-7e4ea9fd22d5'),
(25, 32, 17, 16, '2025-06-04 06:39:48', '2025-06-04 06:39:48', 1, 0, NULL, 0, NULL, '3dc08382-4019-42f3-ab9a-73f9a2eac546');

-- --------------------------------------------------------

--
-- Table structure for table `patient_notes`
--

CREATE TABLE `patient_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `notes` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient_notes`
--

INSERT INTO `patient_notes` (`id`, `patient_id`, `notes`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 1, 'Brush your teeth 4 to 5 times in a day.', '2025-04-01 09:25:59', '2025-04-02 07:09:48', 0),
(5, 5, 'test', '2025-04-17 12:03:50', '2025-04-17 12:03:50', 7),
(7, 9, 'Please follow all post-treatment instructions carefully. Avoid eating or drinking for at least 30 minutes after any dental procedure. If you experience severe pain, swelling, or bleeding, contact the clinic immediately. Take prescribed medications as directed and maintain good oral hygiene.', '2025-05-12 06:54:18', '2025-05-12 06:54:18', 6);

-- --------------------------------------------------------

--
-- Table structure for table `patient_treatments`
--

CREATE TABLE `patient_treatments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `treatment_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` int(11) NOT NULL DEFAULT '0',
  `tooth_selection` varchar(255) DEFAULT NULL,
  `is_billed` tinyint(1) NOT NULL DEFAULT '0',
  `is_quotation_billed` tinyint(1) NOT NULL DEFAULT '0',
  `quotation_give` int(11) NOT NULL DEFAULT '0',
  `rate` decimal(8,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient_treatments`
--

INSERT INTO `patient_treatments` (`id`, `patient_id`, `treatment_id`, `doctor_id`, `tooth_selection`, `is_billed`, `is_quotation_billed`, `quotation_give`, `rate`, `qty`, `amount`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 1, 2, 0, '1, 2, 3, 29, 30, 31, 32, 4, 13, 14, 15, 16, 17, 18, 19, 20', 0, 0, 0, 500.00, 16, 8000.00, '2025-04-01 09:03:00', '2025-04-16 05:25:55', 0),
(2, 1, 1, 0, '11, 12, 23, 24', 1, 0, 0, 600.00, 4, 2400.00, '2025-04-01 09:04:42', '2025-04-16 05:27:46', 0),
(3, 2, 4, 0, '17, 21, 32, 28', 1, 0, 0, 800.00, 4, 3200.00, '2025-04-01 10:31:08', '2025-04-01 10:55:44', 0),
(7, 5, 12, 0, '1, 3, 27', 1, 0, 0, 150.00, 3, 450.00, '2025-04-14 07:07:06', '2025-04-25 10:52:46', 7),
(10, 9, 23, 11, '11, 12', 1, 1, 0, 500.00, 2, 1000.00, '2025-05-12 06:33:46', '2025-05-12 07:29:04', 6),
(11, 12, 23, 11, '10, 11, 12', 0, 0, 0, 1500.00, 3, 4500.00, '2025-05-13 13:10:43', '2025-05-13 13:10:43', 6),
(12, 13, 25, 14, '2, 4, 15, 18, 26, 31, 23', 1, 1, 0, 150.00, 7, 1050.00, '2025-05-19 06:16:32', '2025-05-19 07:26:24', 6),
(14, 17, 30, 18, '10, 11, 12', 1, 0, 0, 45.00, 3, 135.00, '2025-05-28 07:11:08', '2025-05-28 08:55:18', 16),
(16, 16, 29, 0, '13, 12', 0, 0, 0, 10.00, 2, 20.00, '2025-07-30 12:19:56', '2025-07-30 12:19:56', 15);

-- --------------------------------------------------------

--
-- Table structure for table `patient_treatment_document`
--

CREATE TABLE `patient_treatment_document` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT '0',
  `treatment_id` bigint(20) DEFAULT NULL,
  `patient_treatment_id` bigint(20) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` date DEFAULT NULL,
  `clinic_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient_treatment_document`
--

INSERT INTO `patient_treatment_document` (`id`, `patient_id`, `treatment_id`, `patient_treatment_id`, `document`, `comment`, `created_at`, `updated_at`, `date`, `clinic_id`) VALUES
(36, 16, 29, 16, '1753878064_688a0e303c5a5.jpg', 'this is add for testing', '2025-07-30 12:21:04', '2025-07-30 12:21:04', '2025-07-30', '15'),
(37, 16, 29, 16, '1753878064_688a0e303c913.jpg', 'this is add for testing', '2025-07-30 12:21:04', '2025-07-30 12:21:04', '2025-07-30', '15');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_date` date NOT NULL DEFAULT '2025-03-21',
  `amount` decimal(10,2) NOT NULL,
  `mode` enum('Cash','Online') NOT NULL,
  `comments` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `patient_id`, `order_id`, `payment_date`, `amount`, `mode`, `comments`, `created_at`, `updated_at`, `clinic_id`) VALUES
(3, 9, 9, '2025-05-12', 995.00, 'Cash', '900', '2025-05-12 07:29:20', '2025-05-12 07:29:20', 6),
(5, 13, 11, '2025-05-19', 1000.00, 'Online', NULL, '2025-05-19 07:26:31', '2025-05-19 07:26:31', 6),
(8, 17, 13, '2025-05-28', 110.00, 'Cash', NULL, '2025-05-28 08:55:38', '2025-05-28 08:55:38', 16);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'user-list', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(2, 'user-create', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(3, 'user-edit', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(4, 'user-delete', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(5, 'role-create', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(6, 'role-edit', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(7, 'role-list', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(8, 'role-delete', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(9, 'permission-list', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(10, 'permission-create', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(11, 'permission-edit', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(12, 'permission-delete', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL DEFAULT '2025-03-24',
  `gu_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `patient_id`, `date`, `gu_id`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 1, '2025-04-01', 'edc36e95-0708-48bb-8270-ab7fe128e0d4', '2025-04-01 12:11:48', '2025-04-01 12:11:48', 0),
(2, 2, '2025-04-01', 'a9534828-e982-48d3-bb04-768a3171288c', '2025-04-01 12:12:48', '2025-04-01 12:12:48', 0),
(5, 5, '2025-04-15', '9dde0bc9-54b5-4532-8aaa-a3d81355d5c5', '2025-04-15 12:15:21', '2025-04-15 12:15:40', 7),
(6, 9, '2025-05-12', 'c4a09cec-7752-460d-9360-66631c49c4e6', '2025-05-12 06:37:22', '2025-05-12 06:53:06', 6),
(7, 13, '2025-05-19', '5e4c9208-f115-485a-808b-ae23f0d0e301', '2025-05-19 06:39:38', '2025-05-19 06:39:48', 6),
(15, 16, '2025-05-24', 'eb464d00-49fd-4967-b1e7-8fc1f21f4ead', '2025-05-24 10:53:59', '2025-05-24 10:53:59', 15),
(17, 17, '2025-05-28', '44a0166d-03fa-42d5-929b-7b77242507c7', '2025-05-28 07:23:53', '2025-05-28 07:23:53', 16);

-- --------------------------------------------------------

--
-- Table structure for table `prescription_details`
--

CREATE TABLE `prescription_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `prescription_id` bigint(20) UNSIGNED NOT NULL,
  `medicine_id` bigint(20) UNSIGNED NOT NULL,
  `dosage_id` bigint(20) UNSIGNED NOT NULL,
  `clinic_id` varchar(255) DEFAULT NULL,
  `comments` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prescription_details`
--

INSERT INTO `prescription_details` (`id`, `patient_id`, `prescription_id`, `medicine_id`, `dosage_id`, `clinic_id`, `comments`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, NULL, 'Takes after eating.', '2025-04-01 12:11:48', '2025-04-01 12:11:48'),
(2, 1, 1, 2, 2, NULL, 'Takes after eating.', '2025-04-01 12:11:48', '2025-04-01 12:11:48'),
(3, 2, 2, 3, 1, NULL, 'Takes 5ml Syrup.', '2025-04-01 12:12:48', '2025-04-01 12:12:48'),
(4, 2, 2, 4, 5, NULL, 'Takes after eating.', '2025-04-01 12:12:48', '2025-04-01 12:12:48'),
(7, 5, 5, 8, 1, NULL, NULL, '2025-04-15 12:15:21', '2025-04-15 12:15:40'),
(8, 9, 6, 18, 10, NULL, 'after lunch get medicine new', '2025-05-12 06:37:22', '2025-05-12 06:53:06'),
(10, 13, 7, 15, 10, NULL, NULL, '2025-05-19 06:39:38', '2025-05-19 06:39:48'),
(18, 16, 15, 20, 17, NULL, 'fgddfgdgdf', '2025-05-24 10:53:59', '2025-05-24 10:53:59'),
(20, 17, 17, 21, 18, NULL, 'testing', '2025-05-28 07:23:53', '2025-05-28 07:23:53');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `product_location` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `quantity`, `product_location`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 'Wiper', 50, 'Rack no. 1', '2025-04-01 11:55:43', '2025-04-01 11:56:47', 0),
(2, 'Battery', 50, 'Rack no. 2', '2025-04-01 11:56:06', '2025-05-12 06:06:59', 0),
(6, 'test', 110, 'test', '2025-04-28 09:21:35', '2025-04-29 05:35:52', 7),
(8, 'test 1', 10, 'test 123', '2025-04-28 09:22:11', '2025-04-29 05:36:07', 7),
(9, 'MediGlow', 15, 'Maninagar', '2025-05-13 05:11:10', '2025-05-21 05:40:26', 6),
(10, 'test', 110, 'test', '2025-05-19 06:52:45', '2025-05-21 05:21:56', 6),
(12, 'chair', 5, 'Narol', '2025-05-27 13:30:16', '2025-05-27 13:31:29', 16);

-- --------------------------------------------------------

--
-- Table structure for table `product_purchases`
--

CREATE TABLE `product_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `received_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_purchases`
--

INSERT INTO `product_purchases` (`id`, `product_id`, `vendor_id`, `quantity`, `rate`, `amount`, `received_date`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 2, 2, 40, 100.00, 4000.00, '2025-04-01', '2025-04-01 11:56:30', '2025-04-01 11:56:30', 0),
(2, 1, 1, 40, 200.00, 8000.00, '2025-04-01', '2025-04-01 11:56:47', '2025-04-01 11:56:47', 0),
(7, 6, 8, 10, 10.00, 100.00, '2025-04-28', '2025-04-29 05:35:52', '2025-04-29 05:35:52', 7),
(9, 9, 12, 3, 23.00, 69.00, '2025-05-13', '2025-05-13 05:11:39', '2025-05-13 05:11:39', 6),
(10, 10, 14, 10, 10.00, 100.00, '2025-05-15', '2025-05-19 06:53:53', '2025-05-19 06:53:53', 6),
(12, 9, 14, 10, 15.00, 150.00, '2025-05-21', '2025-05-21 05:40:26', '2025-05-21 05:40:26', 6),
(13, 12, 17, 4, 45.00, 180.00, '2025-05-27', '2025-05-27 13:31:29', '2025-05-27 13:31:49', 16);

-- --------------------------------------------------------

--
-- Table structure for table `quotation`
--

CREATE TABLE `quotation` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `quotation_no` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(10,2) NOT NULL,
  `clinic_id` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quotation`
--

INSERT INTO `quotation` (`id`, `patient_id`, `quotation_no`, `date`, `amount`, `discount`, `net_amount`, `clinic_id`, `created_at`, `updated_at`) VALUES
(4, 4, 'quota_0001', '2025-04-21', 4500.00, 0.00, 4500.00, 6, '2025-04-21 08:37:53', '2025-04-21 08:37:53'),
(5, 6, 'quota_0002', '2025-04-22', 3000.00, 250.00, 2750.00, 6, '2025-04-22 08:37:09', '2025-04-22 08:37:09'),
(6, 5, 'quota_0003', '2025-04-25', 450.00, 0.00, 450.00, 7, '2025-04-25 10:52:46', '2025-04-25 10:52:46'),
(8, 9, 'quota_0004', '2025-05-12', 1000.00, 10.00, 990.00, 6, '2025-05-12 07:27:59', '2025-05-12 07:27:59'),
(9, 13, 'quota_0005', '2025-05-19', 1050.00, 0.00, 1050.00, 6, '2025-05-19 06:31:39', '2025-05-19 06:31:39');

-- --------------------------------------------------------

--
-- Table structure for table `quotation_details`
--

CREATE TABLE `quotation_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `treatment_id` bigint(20) UNSIGNED NOT NULL,
  `patient_treatment_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quotation_details`
--

INSERT INTO `quotation_details` (`id`, `quotation_id`, `patient_id`, `treatment_id`, `patient_treatment_id`, `qty`, `rate`, `amount`, `discount`, `net_amount`, `created_at`, `updated_at`, `clinic_id`) VALUES
(4, 4, 4, 5, 5, 3, 1500.00, 4500.00, 0.00, 4500.00, '2025-04-21 08:37:53', '2025-04-21 08:37:53', 6),
(5, 5, 6, 9, 8, 2, 1500.00, 3000.00, 250.00, 2750.00, '2025-04-22 08:37:10', '2025-04-22 08:37:10', 6),
(6, 6, 5, 12, 7, 3, 150.00, 450.00, 0.00, 450.00, '2025-04-25 10:52:46', '2025-04-25 10:52:46', 7),
(8, 8, 9, 23, 10, 2, 500.00, 1000.00, 10.00, 990.00, '2025-05-12 07:27:59', '2025-05-12 07:27:59', 6),
(9, 9, 13, 25, 12, 7, 150.00, 1050.00, 0.00, 1050.00, '2025-05-19 06:31:39', '2025-05-19 06:31:39', 6);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(2, 'Clinic User', 'web', '2025-04-09 04:33:06', '2025-04-09 04:33:06'),
(3, 'Clinic', 'web', '2025-04-09 04:33:06', '2025-04-09 04:33:06');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sendemaildetails`
--

CREATE TABLE `sendemaildetails` (
  `id` int(11) NOT NULL,
  `strSubject` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `strTitle` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `strFromMail` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ToMail` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `strCC` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `strBCC` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sendemaildetails`
--

INSERT INTO `sendemaildetails` (`id`, `strSubject`, `strTitle`, `strFromMail`, `ToMail`, `strCC`, `strBCC`) VALUES
(4, 'Contact Inquiry', 'Navdeep Product', 'no-reply@navdeepproducts.com', NULL, '', ''),
(8, 'Product Inquiry', 'Navdeep Product', 'no-reply@navdeepproducts.com', NULL, NULL, NULL),
(9, 'Order', 'MB Herbal', 'info@getdemo.in', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `treatments`
--

CREATE TABLE `treatments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `treatment_name` varchar(255) NOT NULL,
  `type` enum('general','toothwise') NOT NULL,
  `lab_work` enum('yes','no') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0',
  `amount` int(100) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `treatments`
--

INSERT INTO `treatments` (`id`, `treatment_name`, `type`, `lab_work`, `created_at`, `updated_at`, `clinic_id`, `amount`) VALUES
(1, 'Teeth whitening', 'toothwise', 'yes', '2025-04-01 05:43:37', '2025-04-01 05:43:37', 0, 0),
(2, 'Teeth cleaning', 'toothwise', 'yes', '2025-04-01 05:45:21', '2025-04-01 05:45:21', 0, 0),
(3, 'Tooth extractions', 'toothwise', 'yes', '2025-04-01 05:45:34', '2025-04-01 05:45:34', 0, 0),
(4, 'Orthodontics', 'toothwise', 'yes', '2025-04-01 05:46:26', '2025-04-01 05:46:26', 0, 0),
(5, 'Sealants', 'toothwise', 'yes', '2025-04-01 05:46:56', '2025-04-01 05:46:56', 0, 0),
(11, 'Teeth Extraction', 'general', 'yes', '2025-04-12 10:32:39', '2025-04-12 10:32:39', 7, 0),
(12, 'Dental Crowns', 'toothwise', 'yes', '2025-04-12 10:32:50', '2025-04-12 10:32:50', 7, 0),
(13, 'demo1', 'toothwise', 'yes', '2025-04-12 11:12:50', '2025-04-12 11:40:03', 7, 0),
(15, 'test', 'toothwise', 'yes', '2025-04-12 11:15:28', '2025-04-12 11:15:28', 7, 0),
(23, 'Dental Cleaning', 'toothwise', 'yes', '2025-05-12 06:17:57', '2025-05-12 06:17:57', 6, 800),
(24, 'Tooth Filling', 'general', 'yes', '2025-05-12 06:18:31', '2025-05-12 06:18:31', 6, 2000),
(25, 'Root Canal Treatment', 'toothwise', 'yes', '2025-05-12 06:18:53', '2025-05-12 06:18:53', 6, 2000),
(26, 'Tooth Extraction', 'toothwise', 'yes', '2025-05-12 06:19:20', '2025-05-21 05:13:48', 6, 500),
(27, 'Tooth Whitening', 'general', 'yes', '2025-05-14 07:24:20', '2025-05-14 07:24:20', 10, 500),
(29, 'Tooth Clinic', 'general', 'yes', '2025-05-22 13:17:15', '2025-05-22 13:17:15', 15, 2000),
(30, 'Tooth clening', 'toothwise', 'yes', '2025-05-27 13:18:04', '2025-05-27 13:18:04', 16, 1200);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '2' COMMENT '1=Admin, 2=TA/TP',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0',
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `address`, `mobile_number`, `email_verified_at`, `password`, `role_id`, `status`, `created_at`, `updated_at`, `clinic_id`, `dob`) VALUES
(1, 'Super', 'admin@admin.com', NULL, '9898808751', NULL, '$2y$12$G4FMXATQod29.VCYMfdi3OlJBlXuK561PUcaE771dvaHWN5OAuxP2', 1, 1, '2022-09-12 04:33:06', '2025-04-11 11:02:55', 0, NULL),
(33, 'Krishana Clinic', NULL, NULL, '9898808754', NULL, '$2y$10$odslfD3qhCKlA8ptgMN6VeVyKPgkExZippQdQoT4V04L6HToPZ8ri', 2, 1, '2025-05-22 13:07:38', '2025-05-22 13:07:38', 15, NULL),
(34, 'Mihir', 'harshil11@gmail.com', 'B/11 swastik hospital near  vatva canal road\r\nahmedabad', '7043447511', NULL, '$2y$10$t3mJCQnfZVOBFyb3shsM7O9e0PYb3kkJO7oxwlbXRFwtl/rfJmQ7a', 2, 1, '2025-05-27 13:11:54', '2025-05-27 13:12:25', 16, NULL),
(35, 'kunal', 'kunal@gmail.com', 'Narol', '9999999999', NULL, '$2y$10$LWYuTKlyFY9OrYPYMomGYOs29O/j1LJPWzcg/HvjYL06spvGHLVAK', 3, 1, '2025-05-27 13:28:23', '2025-05-27 13:28:42', 16, '2002-02-02'),
(36, 'kush clinic', 'vaibhav@gmail.com', 'Narol', '9880765432', NULL, '$2y$10$FU7aI6bSe8fzhp0DA.poZOfoZwGZlx3Ypd0.4hDC4XTd5JtBAfeU6', 2, 1, '2025-05-28 11:16:02', '2025-05-28 11:16:02', 17, NULL),
(37, 'Nisha', 'nisha@gmail.com', 'Isanpur', '7486984607', NULL, '$2y$10$xBlF2LmdPXT3emApbMi3YOcFY/JMGjgeB4Q5d3X5eSZm6q9.XdJGe', 3, 1, '2025-05-28 11:18:23', '2025-05-28 11:18:23', 17, '2002-02-02');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `contact_person_name` varchar(100) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `company_name`, `contact_person_name`, `mobile`, `email`, `address`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 'Universal Pipes', 'Rakesh Sharma', '6352417895', 'rakesh@gmail.com', 'India', '2025-04-01 06:47:58', '2025-04-01 06:53:33', 0),
(2, 'Polytech LLP', 'Mohit Sharma', '8050604070', 'mohit@gmail.com', 'India', '2025-04-01 06:52:59', '2025-04-01 06:52:59', 0),
(8, 'Apollo', 'Bansari Patel', '9987654321', 'dev1.apolloinfotech@gmail.com', 'Sola\r\nScience City', '2025-04-28 09:12:57', '2025-04-28 09:12:57', 7),
(9, 'Apollo infotech', 'Krunal Shah', '9824773136', 'shahkrunal83@gmail.com', '1, anurag flat,\r\nBhairavnath Cross Road, Maninagar', '2025-04-28 09:21:20', '2025-04-28 09:21:20', 7),
(12, 'Apollo Infotech', 'Krunal', '9898807678', 'apollo@gmail.com', 'Anurag Flat bhairavnath road', '2025-05-13 05:09:47', '2025-05-13 05:09:47', 6),
(13, 'test', 'test', '9874456321', NULL, NULL, '2025-05-19 05:29:19', '2025-05-19 05:29:19', 10),
(14, 'Apollo', 'Krunal Shah', '9824773136', 'shahkrunal83@gmail.com', '1, Anurag Flat,\r\nBhairavnath Cross Road, Maninagar', '2025-05-19 06:46:35', '2025-05-19 09:16:22', 6),
(16, 'Apollo11', 'test', '7689076655', 'nisha@gmail.com', 'Isanpur', '2025-05-22 13:35:39', '2025-05-22 13:35:45', 15),
(17, 'Apollo infotech', 'kunal', '5555555555', 'kunal@gmail.com', 'isanpur ahmedabad', '2025-05-27 13:31:01', '2025-05-27 13:31:01', 16);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clinic`
--
ALTER TABLE `clinic`
  ADD PRIMARY KEY (`clinic_id`),
  ADD UNIQUE KEY `mobile_no_UNIQUE` (`mobile_no`);

--
-- Indexes for table `clinic_case_counters`
--
ALTER TABLE `clinic_case_counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clinic_id_UNIQUE` (`clinic_id`);

--
-- Indexes for table `Clinic_order`
--
ALTER TABLE `Clinic_order`
  ADD PRIMARY KEY (`Clinic_order_id`);

--
-- Indexes for table `concern_forms`
--
ALTER TABLE `concern_forms`
  ADD PRIMARY KEY (`iConcernFormId`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_patient_id_foreign` (`patient_id`),
  ADD KEY `documents_treatment_id_foreign` (`treatment_id`),
  ADD KEY `documents_patient_treatment_id_foreign` (`patient_treatment_id`);

--
-- Indexes for table `dosages`
--
ALTER TABLE `dosages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `labs`
--
ALTER TABLE `labs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labworks`
--
ALTER TABLE `labworks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `labworks_patient_id_foreign` (`patient_id`),
  ADD KEY `labworks_lab_id_foreign` (`lab_id`),
  ADD KEY `labworks_treatment_id_foreign` (`treatment_id`),
  ADD KEY `labworks_patient_treatment_id_foreign` (`patient_treatment_id`);

--
-- Indexes for table `maintenance_registers`
--
ALTER TABLE `maintenance_registers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_invoice_no_unique` (`invoice_no`),
  ADD KEY `orders_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_patient_id_foreign` (`patient_id`),
  ADD KEY `order_details_treatment_id_foreign` (`treatment_id`),
  ADD KEY `order_details_patient_treatment_id_foreign` (`patient_treatment_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_appointments`
--
ALTER TABLE `patient_appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_appointments_patient_id_foreign` (`patient_id`),
  ADD KEY `patient_appointments_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `patient_concern_form`
--
ALTER TABLE `patient_concern_form`
  ADD PRIMARY KEY (`patient_concern_form_id`);

--
-- Indexes for table `patient_notes`
--
ALTER TABLE `patient_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_notes_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `patient_treatments`
--
ALTER TABLE `patient_treatments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_treatments_patient_id_foreign` (`patient_id`),
  ADD KEY `patient_treatments_treatment_id_foreign` (`treatment_id`);

--
-- Indexes for table `patient_treatment_document`
--
ALTER TABLE `patient_treatment_document`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_patient_id_foreign` (`patient_id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prescriptions_gu_id_unique` (`gu_id`),
  ADD KEY `prescriptions_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `prescription_details`
--
ALTER TABLE `prescription_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescription_details_patient_id_foreign` (`patient_id`),
  ADD KEY `prescription_details_prescription_id_foreign` (`prescription_id`),
  ADD KEY `prescription_details_medicine_id_foreign` (`medicine_id`),
  ADD KEY `prescription_details_dosage_id_foreign` (`dosage_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_purchases`
--
ALTER TABLE `product_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_purchases_product_id_foreign` (`product_id`),
  ADD KEY `product_purchases_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotation_details`
--
ALTER TABLE `quotation_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sendemaildetails`
--
ALTER TABLE `sendemaildetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `treatments`
--
ALTER TABLE `treatments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `mobile_number` (`mobile_number`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clinic`
--
ALTER TABLE `clinic`
  MODIFY `clinic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `clinic_case_counters`
--
ALTER TABLE `clinic_case_counters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Clinic_order`
--
ALTER TABLE `Clinic_order`
  MODIFY `Clinic_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `concern_forms`
--
ALTER TABLE `concern_forms`
  MODIFY `iConcernFormId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `dosages`
--
ALTER TABLE `dosages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `labs`
--
ALTER TABLE `labs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `labworks`
--
ALTER TABLE `labworks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `maintenance_registers`
--
ALTER TABLE `maintenance_registers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `patient_appointments`
--
ALTER TABLE `patient_appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `patient_concern_form`
--
ALTER TABLE `patient_concern_form`
  MODIFY `patient_concern_form_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `patient_notes`
--
ALTER TABLE `patient_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `patient_treatments`
--
ALTER TABLE `patient_treatments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `patient_treatment_document`
--
ALTER TABLE `patient_treatment_document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `prescription_details`
--
ALTER TABLE `prescription_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_purchases`
--
ALTER TABLE `product_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `quotation_details`
--
ALTER TABLE `quotation_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sendemaildetails`
--
ALTER TABLE `sendemaildetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `treatments`
--
ALTER TABLE `treatments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_patient_treatment_id_foreign` FOREIGN KEY (`patient_treatment_id`) REFERENCES `patient_treatments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `labworks`
--
ALTER TABLE `labworks`
  ADD CONSTRAINT `labworks_lab_id_foreign` FOREIGN KEY (`lab_id`) REFERENCES `labs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `labworks_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `labworks_patient_treatment_id_foreign` FOREIGN KEY (`patient_treatment_id`) REFERENCES `patient_treatments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `labworks_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_patient_treatment_id_foreign` FOREIGN KEY (`patient_treatment_id`) REFERENCES `patient_treatments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_appointments`
--
ALTER TABLE `patient_appointments`
  ADD CONSTRAINT `patient_appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_notes`
--
ALTER TABLE `patient_notes`
  ADD CONSTRAINT `patient_notes_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_treatments`
--
ALTER TABLE `patient_treatments`
  ADD CONSTRAINT `patient_treatments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_treatments_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescription_details`
--
ALTER TABLE `prescription_details`
  ADD CONSTRAINT `prescription_details_dosage_id_foreign` FOREIGN KEY (`dosage_id`) REFERENCES `dosages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_details_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_details_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_details_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_purchases`
--
ALTER TABLE `product_purchases`
  ADD CONSTRAINT `product_purchases_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_purchases_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
