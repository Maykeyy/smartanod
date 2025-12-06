-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 10:19 AM
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
-- Database: `smartanod_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_generate_incident_number` (OUT `new_number` VARCHAR(20))   BEGIN
    DECLARE year_part VARCHAR(4);
    DECLARE seq_num INT;
    
    SET year_part = YEAR(CURDATE());
    
    SELECT COALESCE(MAX(CAST(SUBSTRING(incident_number, -3) AS UNSIGNED)), 0) + 1
    INTO seq_num
    FROM incidents
    WHERE incident_number LIKE CONCAT('INC-', year_part, '-%');
    
    SET new_number = CONCAT('INC-', year_part, '-', LPAD(seq_num, 3, '0'));
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_generate_patrol_number` (OUT `new_number` VARCHAR(20))   BEGIN
    DECLARE year_part VARCHAR(4);
    DECLARE seq_num INT;
    
    SET year_part = YEAR(CURDATE());
    
    SELECT COALESCE(MAX(CAST(SUBSTRING(patrol_number, -3) AS UNSIGNED)), 0) + 1
    INTO seq_num
    FROM patrols
    WHERE patrol_number LIKE CONCAT('PTL-', year_part, '-%');
    
    SET new_number = CONCAT('PTL-', year_part, '-', LPAD(seq_num, 3, '0'));
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_incident_statistics` (IN `start_date` DATE, IN `end_date` DATE)   BEGIN
    SELECT 
        COUNT(*) AS total_incidents,
        SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) AS resolved,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending,
        SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress,
        SUM(CASE WHEN priority = 'Urgent' THEN 1 ELSE 0 END) AS urgent_count,
        SUM(CASE WHEN priority = 'High' THEN 1 ELSE 0 END) AS high_count,
        ROUND(AVG(TIMESTAMPDIFF(HOUR, created_at, COALESCE(resolved_at, NOW()))), 2) AS avg_resolution_hours
    FROM incidents
    WHERE incident_date BETWEEN start_date AND end_date;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` enum('incident','patrol','evidence','user','settings','system') DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 2, 'Create Incident', 'incident', 1, 'Created new incident INC-2025-001', '192.168.1.100', NULL, '2025-12-02 06:26:00'),
(2, 2, 'Upload Evidence', 'evidence', 1, 'Uploaded evidence for INC-2025-001', '192.168.1.100', NULL, '2025-12-02 06:26:00'),
(3, 6, 'Assign Incident', 'incident', 1, 'Assigned incident INC-2025-001 to Tanod Juan', '192.168.1.105', NULL, '2025-12-02 06:26:00'),
(4, 3, 'Update Incident', 'incident', 2, 'Updated status to Resolved for INC-2025-002', '192.168.1.110', NULL, '2025-12-02 06:26:00'),
(5, 1, 'Login', 'system', NULL, 'Admin user logged in', '192.168.1.101', NULL, '2025-12-02 06:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `evidence`
--

CREATE TABLE `evidence` (
  `id` int(11) NOT NULL,
  `incident_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `id` int(11) NOT NULL,
  `incident_number` varchar(20) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category` varchar(50) NOT NULL,
  `priority` enum('Low','Medium','High','Urgent') NOT NULL,
  `status` enum('Pending','In Progress','Resolved','Closed') DEFAULT 'Pending',
  `zone_id` int(11) DEFAULT NULL,
  `location` text NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `reporter_type` enum('Walk-in','Phone Call','Online','Patrol') NOT NULL,
  `reporter_name` varchar(100) DEFAULT NULL,
  `reporter_contact` varchar(100) DEFAULT NULL,
  `involved_parties` text DEFAULT NULL,
  `narrative` text NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`id`, `incident_number`, `date_time`, `category`, `priority`, `status`, `zone_id`, `location`, `latitude`, `longitude`, `reporter_type`, `reporter_name`, `reporter_contact`, `involved_parties`, `narrative`, `assigned_to`, `created_by`, `created_at`, `updated_at`, `resolved_at`) VALUES
(1, 'INC-2025-001', '2025-11-28 06:30:00', 'Noise Complaint', 'High', 'Pending', 1, 'Purok 2, Malabanban Sur', 13.93150000, 121.42350000, 'Walk-in', 'Maria Santos', '09123456789', 'Neighbor playing loud music during rest hours', 'Multiple residents reported excessive noise coming from a residential property. The noise has been ongoing for several hours.', 3, 2, '2025-12-05 08:02:51', '2025-12-05 08:02:51', NULL),
(2, 'INC-2025-002', '2025-11-27 02:15:00', 'Theft', 'Urgent', 'Resolved', 2, 'Market Area, Zone B', 13.93450000, 121.42550000, 'Phone Call', 'Pedro Mercado', '09123456788', 'Unknown suspect', 'Vendor reported theft of merchandise from market stall. Estimated value: PHP 5,000. CCTV footage available.', 5, 2, '2025-12-05 08:02:51', '2025-12-05 08:02:51', NULL),
(3, 'INC-2025-003', '2025-11-26 10:45:00', 'Vandalism', 'Medium', 'In Progress', 3, 'Barangay Basketball Court', 13.92750000, 121.42650000, 'Patrol', NULL, NULL, 'Unknown vandals', 'Graffiti found on barangay basketball court walls. Clean-up scheduled.', 7, 7, '2025-12-05 08:02:51', '2025-12-05 08:02:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `incident_history`
--

CREATE TABLE `incident_history` (
  `id` int(11) NOT NULL,
  `incident_id` int(11) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `field_name` varchar(50) NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `action` enum('created','updated','assigned','status_changed','resolved','closed') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incident_history`
--

INSERT INTO `incident_history` (`id`, `incident_id`, `changed_by`, `field_name`, `old_value`, `new_value`, `action`, `notes`, `created_at`) VALUES
(1, 1, 2, 'status', NULL, 'Pending', 'created', 'Incident created', '2025-12-02 06:26:00'),
(2, 1, 6, 'assigned_to', NULL, '3', 'assigned', 'Assigned to Tanod Juan', '2025-12-02 06:26:00'),
(3, 2, 2, 'status', NULL, 'Pending', 'created', 'Incident created', '2025-12-02 06:26:00'),
(4, 2, 3, 'status', 'Pending', 'In Progress', 'status_changed', 'Started investigation', '2025-12-02 06:26:00'),
(5, 2, 3, 'status', 'In Progress', 'Resolved', 'resolved', 'Theft case resolved, suspect identified', '2025-12-02 06:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `incident_updates`
--

CREATE TABLE `incident_updates` (
  `id` int(11) NOT NULL,
  `incident_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `update_type` enum('status_change','assignment','note','evidence_added') NOT NULL,
  `old_value` varchar(100) DEFAULT NULL,
  `new_value` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incident_updates`
--

INSERT INTO `incident_updates` (`id`, `incident_id`, `user_id`, `update_type`, `old_value`, `new_value`, `notes`, `created_at`) VALUES
(1, 1, 2, 'status_change', NULL, 'Pending', 'Initial report filed', '2025-12-05 08:02:52'),
(2, 1, 2, 'assignment', NULL, '3', 'Assigned to Juan Dela Cruz', '2025-12-05 08:02:52'),
(3, 2, 2, 'status_change', NULL, 'In Progress', 'Investigation started', '2025-12-05 08:02:52'),
(4, 2, 5, 'status_change', 'In Progress', 'Resolved', 'Suspect identified and merchandise recovered', '2025-12-05 08:02:52');

-- --------------------------------------------------------

--
-- Table structure for table `patrols`
--

CREATE TABLE `patrols` (
  `id` int(11) NOT NULL,
  `patrol_number` varchar(20) NOT NULL,
  `tanod_id` int(11) NOT NULL,
  `zone` enum('Zone A','Zone B','Zone C','Zone D') NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('Scheduled','Ongoing','Completed','Missed','Cancelled') NOT NULL DEFAULT 'Scheduled',
  `notes` text DEFAULT NULL,
  `actual_start_time` datetime DEFAULT NULL,
  `actual_end_time` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patrols`
--

INSERT INTO `patrols` (`id`, `patrol_number`, `tanod_id`, `zone`, `start_time`, `end_time`, `status`, `notes`, `actual_start_time`, `actual_end_time`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'PTL-2025-001', 3, 'Zone A', '2025-11-28 08:00:00', '2025-11-28 16:00:00', 'Completed', 'Regular morning patrol', NULL, NULL, 6, '2025-12-02 06:26:00', '2025-12-02 06:26:00'),
(2, 'PTL-2025-002', 4, 'Zone B', '2025-11-28 20:00:00', '2025-11-29 04:00:00', 'Scheduled', 'Night shift patrol', NULL, NULL, 6, '2025-12-02 06:26:00', '2025-12-02 06:26:00'),
(3, 'PTL-2025-003', 5, 'Zone C', '2025-11-29 14:00:00', '2025-11-29 22:00:00', 'Scheduled', 'Afternoon to evening patrol', NULL, NULL, 6, '2025-12-02 06:26:00', '2025-12-02 06:26:00'),
(4, 'PTL-2025-004', 3, 'Zone A', '2025-11-27 08:00:00', '2025-11-27 16:00:00', 'Completed', 'Regular patrol', NULL, NULL, 6, '2025-12-02 06:26:00', '2025-12-02 06:26:00'),
(5, 'PTL-2025-005', 4, 'Zone B', '2025-11-27 20:00:00', '2025-11-28 04:00:00', 'Completed', 'Night patrol completed without incidents', NULL, NULL, 6, '2025-12-02 06:26:00', '2025-12-02 06:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `description` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_by`, `updated_at`) VALUES
(1, 'barangay_name', 'Barangay SmartTanod', 'Official barangay name', 1, '2025-12-02 06:26:00'),
(2, 'contact_number', '(02) 1234-5678', 'Main contact number', 1, '2025-12-02 06:26:00'),
(3, 'email', 'info@smartanod.com', 'Official email address', 1, '2025-12-02 06:26:00'),
(4, 'address', '123 Main Street, City, Province', 'Barangay hall address', 1, '2025-12-02 06:26:00'),
(5, 'max_file_size', '5242880', 'Maximum file upload size in bytes (5MB)', 1, '2025-12-02 06:26:00'),
(6, 'session_timeout', '1800', 'Session timeout in seconds (30 minutes)', 1, '2025-12-02 06:26:00'),
(7, 'evidence_retention_years', '5', 'Evidence retention period in years', 1, '2025-12-02 06:26:00'),
(8, 'theme_color', 'orange', 'Theme accent color', 1, '2025-12-02 06:26:00'),
(9, 'timezone', 'Asia/Manila', 'System timezone', 1, '2025-12-02 06:26:00'),
(10, 'incident_id_prefix', 'INC', 'Incident ID prefix', 1, '2025-12-02 06:26:00'),
(11, 'patrol_id_prefix', 'PTL', 'Patrol ID prefix', 1, '2025-12-02 06:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','captain','clerk','tanod','viewer') NOT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `locked_until` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `email`, `password`, `phone`, `address`, `role`, `zone_id`, `status`, `locked_until`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin', 'admin@smartanod.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '09123456789', 'Barangay Hall, Malabanban Sur', 'admin', NULL, 'active', NULL, NULL, '2025-12-05 08:02:51', '2025-12-05 08:02:51'),
(2, 'Clerk User', 'clerk', 'clerk@smartanod.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '09123456790', 'Barangay Hall, Malabanban Sur', 'clerk', NULL, 'active', NULL, NULL, '2025-12-05 08:02:51', '2025-12-05 08:02:51'),
(3, 'Juan Dela Cruz', 'tanod_juan', 'juan@smartanod.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '09123456791', 'Purok 1, Malabanban Sur', 'tanod', 1, 'active', NULL, NULL, '2025-12-05 08:02:51', '2025-12-05 08:02:51'),
(4, 'Maria Santos', 'tanod_maria', 'maria@smartanod.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '09123456792', 'Purok 2, Malabanban Sur', 'tanod', 1, 'active', NULL, NULL, '2025-12-05 08:02:51', '2025-12-05 08:02:51'),
(5, 'Pedro Garcia', 'tanod_pedro', 'pedro@smartanod.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '09123456793', 'Purok 4, Malabanban Sur', 'tanod', 2, 'active', NULL, NULL, '2025-12-05 08:02:51', '2025-12-05 08:02:51'),
(6, 'Ana Rodriguez', 'tanod_ana', 'ana@smartanod.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '09123456794', 'Purok 5, Malabanban Sur', 'tanod', 2, 'active', NULL, NULL, '2025-12-05 08:02:51', '2025-12-05 08:02:51'),
(7, 'Carlos Martinez', 'tanod_carlos', 'carlos@smartanod.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '09123456795', 'Purok 7, Malabanban Sur', 'tanod', 3, 'active', NULL, NULL, '2025-12-05 08:02:51', '2025-12-05 08:02:51'),
(8, 'Lisa Wong', 'tanod_lisa', 'lisa@smartanod.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '09123456796', 'Purok 8, Malabanban Sur', 'tanod', 3, 'active', NULL, NULL, '2025-12-05 08:02:51', '2025-12-05 08:02:51');

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(7) DEFAULT '#f59e0b',
  `coverage_area` varchar(50) DEFAULT NULL,
  `boundary_coordinates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Array of lat/lng coordinates for zone polygon' CHECK (json_valid(`boundary_coordinates`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`id`, `name`, `description`, `color`, `coverage_area`, `boundary_coordinates`, `created_at`, `updated_at`) VALUES
(1, 'Zone A', 'Northern residential area including Purok 1-3', '#f59e0b', '2.5 km²', '[[13.9320, 121.4210], [13.9340, 121.4240], [13.9310, 121.4260], [13.9290, 121.4230]]', '2025-12-05 08:02:51', '2025-12-05 08:02:51'),
(2, 'Zone B', 'Eastern commercial district including market area', '#10b981', '2.8 km²', '[[13.9340, 121.4240], [13.9360, 121.4270], [13.9330, 121.4290], [13.9310, 121.4260]]', '2025-12-05 08:02:51', '2025-12-05 08:02:51'),
(3, 'Zone C', 'Southern residential area including Purok 7-9', '#3b82f6', '2.2 km²', '[[13.9290, 121.4230], [13.9310, 121.4260], [13.9280, 121.4280], [13.9260, 121.4250]]', '2025-12-05 08:02:51', '2025-12-05 08:02:51'),
(4, 'Zone D', 'Western mixed-use area including school zone', '#a855f7', '2.6 km²', '[[13.9310, 121.4260], [13.9330, 121.4290], [13.9300, 121.4310], [13.9280, 121.4280]]', '2025-12-05 08:02:51', '2025-12-05 08:02:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_entity_type` (`entity_type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `evidence`
--
ALTER TABLE `evidence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `idx_incident` (`incident_id`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `incident_number` (`incident_number`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_incident_number` (`incident_number`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_zone` (`zone_id`),
  ADD KEY `idx_date` (`date_time`);

--
-- Indexes for table `incident_history`
--
ALTER TABLE `incident_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `changed_by` (`changed_by`),
  ADD KEY `idx_incident_id` (`incident_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `incident_updates`
--
ALTER TABLE `incident_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incident_id` (`incident_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `patrols`
--
ALTER TABLE `patrols`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patrol_number` (`patrol_number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_patrol_number` (`patrol_number`),
  ADD KEY `idx_tanod_id` (`tanod_id`),
  ADD KEY `idx_zone` (`zone`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_start_time` (`start_time`),
  ADD KEY `idx_patrols_tanod_date` (`tanod_id`,`start_time`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_setting_key` (`setting_key`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_setting` (`category`,`setting_key`),
  ADD KEY `idx_category` (`category`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `zone_id` (`zone_id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `evidence`
--
ALTER TABLE `evidence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `incident_updates`
--
ALTER TABLE `incident_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `evidence`
--
ALTER TABLE `evidence`
  ADD CONSTRAINT `evidence_ibfk_1` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evidence_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `incidents_ibfk_1` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incidents_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incidents_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `incident_updates`
--
ALTER TABLE `incident_updates`
  ADD CONSTRAINT `incident_updates_ibfk_1` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `incident_updates_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
