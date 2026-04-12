-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2026 at 12:22 PM
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
-- Database: `phishing_simulator`
--

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `template` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `status` enum('active','completed') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `name`, `template`, `subject`, `duration`, `status`, `created_at`, `completed_at`) VALUES
(1, 'test1', 'it', 'URGENT: Kemas Kini Data Peribadi Sistem Anda', 7, 'active', '2026-04-12 09:29:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `campaign_files`
--

CREATE TABLE `campaign_files` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_targets`
--

CREATE TABLE `campaign_targets` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaign_targets`
--

INSERT INTO `campaign_targets` (`id`, `campaign_id`, `email`) VALUES
(1, 1, 'syedkhaidir20@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `option_text` text DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `question_id`, `option_text`, `is_correct`) VALUES
(9, 3, 'Klik link dan reset segera', 0),
(10, 3, 'Abaikan sahaja', 0),
(11, 3, 'Hubungi IT melalui saluran rasmi untuk sahkan', 1),
(12, 3, 'Forward kepada rakan', 0),
(13, 4, 'Klik link dan masukkan maklumat', 0),
(14, 4, 'Panik dan ikut arahan', 0),
(15, 4, 'Hubungi bank melalui nombor rasmi', 1),
(16, 4, 'Balas email tersebut', 0),
(17, 5, 'Buka attachment', 0),
(18, 5, 'Delete terus', 0),
(19, 5, 'Tanya rakan tersebut untuk pengesahan', 1),
(20, 5, 'Forward kepada orang lain', 0),
(21, 6, 'Terima sahaja', 0),
(22, 6, 'Balas email tersebut', 0),
(23, 6, 'Semak domain dan sahkan dengan syarikat rasmi', 1),
(24, 6, 'Abaikan terus', 0),
(25, 7, 'Klik link', 0),
(26, 7, 'Isi maklumat', 0),
(27, 7, 'Abaikan kerana mencurigakan', 1),
(28, 7, 'Share dengan kawan', 0),
(29, 8, 'Klik link terus', 0),
(30, 8, 'Login tanpa fikir', 0),
(31, 8, 'Semak URL dan akses melalui laman rasmi sendiri', 1),
(32, 8, 'Forward email', 0),
(33, 9, 'Beri maklumat', 0),
(34, 9, 'Abaikan sahaja', 0),
(35, 9, 'Sahkan dengan HR melalui saluran rasmi', 1),
(36, 9, 'Balas email', 0),
(37, 10, 'Transfer segera', 0),
(38, 10, 'Balas email', 0),
(39, 10, 'Hubungi boss untuk sahkan', 1),
(40, 10, 'Abaikan', 0),
(41, 11, 'Login juga', 0),
(42, 11, 'Refresh page', 0),
(43, 11, 'Tutup laman dan laporkan kepada IT', 1),
(44, 11, 'Cuba lagi', 0),
(45, 12, 'Klik dan bayar', 0),
(46, 12, 'Masukkan maklumat', 0),
(47, 12, 'Semak status melalui website rasmi courier', 1),
(48, 12, 'Balas email', 0);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`) VALUES
(3, 'Anda menerima email dari “IT Support” yang meminta anda reset password melalui link yang diberi. Apa tindakan anda?'),
(4, 'Email dari bank anda memaklumkan akaun akan disekat jika tidak verify dalam 24 jam. Apa yang patut anda lakukan?'),
(5, 'Anda dapat email dari rakan sekerja dengan attachment “invoice.pdf” tetapi ayat email pelik. Apa langkah anda?'),
(6, 'Email tawaran kerja dari syarikat terkenal tetapi menggunakan email domain pelik (contoh: @gmail.com). Apa tindakan anda?'),
(7, 'Anda menerima email mengatakan anda menang hadiah dan perlu klik link untuk claim. Apa reaksi anda?'),
(8, 'Email rasmi universiti meminta anda login melalui portal yang diberi link. Bagaimana anda sahkan kesahihan email tersebut?'),
(9, 'Anda dapat email dari HR meminta maklumat peribadi (IC, akaun bank). Apa yang perlu anda buat?'),
(10, 'Email nampak seperti dari boss anda minta transfer duit segera. Apa tindakan terbaik?'),
(11, 'Anda klik satu link dalam email dan dibawa ke laman login yang nampak pelik. Apa yang anda lakukan seterusnya?'),
(12, 'Email dari courier (PosLaju/DHL) mengatakan parcel gagal dihantar dan perlu bayar caj melalui link. Apa tindakan anda?');

-- --------------------------------------------------------

--
-- Table structure for table `simulation_results`
--

CREATE TABLE `simulation_results` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `email_opened` tinyint(1) DEFAULT 0,
  `file_opened` tinyint(1) DEFAULT 0,
  `link_clicked` tinyint(1) DEFAULT 0,
  `data_submitted` tinyint(1) DEFAULT 0,
  `quiz_score` int(11) DEFAULT 0,
  `risk_level` enum('low','medium','high') DEFAULT 'low',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `simulation_results`
--

INSERT INTO `simulation_results` (`id`, `campaign_id`, `user_email`, `email_opened`, `file_opened`, `link_clicked`, `data_submitted`, `quiz_score`, `risk_level`, `created_at`) VALUES
(1, 1, 'syedkhaidir20@gmail.com', 1, 0, 1, 1, 10, 'low', '2026-04-12 09:29:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-04-07 07:21:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaign_files`
--
ALTER TABLE `campaign_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_id` (`campaign_id`);

--
-- Indexes for table `campaign_targets`
--
ALTER TABLE `campaign_targets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_id` (`campaign_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `simulation_results`
--
ALTER TABLE `simulation_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_id` (`campaign_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `campaign_files`
--
ALTER TABLE `campaign_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaign_targets`
--
ALTER TABLE `campaign_targets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `simulation_results`
--
ALTER TABLE `simulation_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `campaign_files`
--
ALTER TABLE `campaign_files`
  ADD CONSTRAINT `campaign_files_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `campaign_targets`
--
ALTER TABLE `campaign_targets`
  ADD CONSTRAINT `campaign_targets_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `simulation_results`
--
ALTER TABLE `simulation_results`
  ADD CONSTRAINT `simulation_results_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
