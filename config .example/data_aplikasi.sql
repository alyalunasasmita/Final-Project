-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Jan 22, 2026 at 04:37 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `data_aplikasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `catatan`
--

CREATE TABLE `catatan` (
  `id_catatan` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `catatan` longtext,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `users_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logactivity`
--

CREATE TABLE `logactivity` (
  `id_logactivity` int NOT NULL,
  `tanggal` date DEFAULT NULL,
  `aktivitas` varchar(45) DEFAULT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `users_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_belajar`
--

CREATE TABLE `log_belajar` (
  `id_logbelajar` int NOT NULL,
  `users_id` int NOT NULL,
  `materi_id` int DEFAULT NULL,
  `submateri_id` int DEFAULT NULL,
  `waktu_mulai` datetime NOT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `durasi` decimal(5,2) GENERATED ALWAYS AS (coalesce((timestampdiff(MINUTE,`waktu_mulai`,`waktu_selesai`) / 60),0)) STORED,
  `tanggal` date GENERATED ALWAYS AS (cast(`waktu_mulai` as date)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materi`
--

CREATE TABLE `materi` (
  `id_materi` int NOT NULL,
  `nama_materi` varchar(45) DEFAULT NULL,
  `deskripsi_materi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `materi`
--
DELIMITER $$
CREATE TRIGGER `trg_restore_materi` AFTER UPDATE ON `materi` FOR EACH ROW BEGIN
  IF OLD.deleted_at IS NOT NULL AND NEW.deleted_at IS NULL THEN
    UPDATE submateri
    SET deleted_at = NULL
    WHERE materi_id_materi = NEW.id_materi;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id_notif` int NOT NULL,
  `users_id` int NOT NULL,
  `channel` enum('email','webpush') NOT NULL DEFAULT 'email',
  `tujuan` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `status` enum('sent','failed') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opsi_jawaban`
--

CREATE TABLE `opsi_jawaban` (
  `id` int NOT NULL,
  `soal_id` int NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` char(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `percobaan_kuis`
--

CREATE TABLE `percobaan_kuis` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `submateri_id` int NOT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `total_questions` int DEFAULT '0',
  `correct_count` int DEFAULT '0',
  `score_percent` decimal(5,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `progres_submateri`
--

CREATE TABLE `progres_submateri` (
  `id_progresSub` int NOT NULL,
  `users_id` int NOT NULL,
  `submateri_id_subMateri` int NOT NULL,
  `status` enum('belum','selesai') DEFAULT 'belum',
  `waktu_selesai` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id_schedule` int NOT NULL,
  `nama_schedule` varchar(45) DEFAULT NULL,
  `deskripsi` text,
  `tanggal` date DEFAULT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `remind_before_minutes` int DEFAULT NULL,
  `reminder_sent_at` datetime DEFAULT NULL,
  `durasi` time DEFAULT NULL,
  `users_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `soal_kuis`
--

CREATE TABLE `soal_kuis` (
  `id` int NOT NULL,
  `submateri_id` int NOT NULL,
  `question` text NOT NULL,
  `explanation` text,
  `is_active` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submateri`
--

CREATE TABLE `submateri` (
  `id_subMateri` int NOT NULL,
  `urutan` int NOT NULL,
  `nama_subMateri` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `isi_materi` longtext,
  `materi_id_materi` int DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id_catatan` bigint UNSIGNED NOT NULL,
  `id_user` int DEFAULT NULL,
  `title` varchar(180) NOT NULL,
  `description` text,
  `status` enum('belum progres','dalam progres','selesai') NOT NULL DEFAULT 'belum progres',
  `deadline` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(16) DEFAULT NULL,
  `password` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `catatan`
--
ALTER TABLE `catatan`
  ADD PRIMARY KEY (`id_catatan`),
  ADD KEY `users_id` (`users_id`);

--
-- Indexes for table `logactivity`
--
ALTER TABLE `logactivity`
  ADD PRIMARY KEY (`id_logactivity`),
  ADD KEY `users_id` (`users_id`);

--
-- Indexes for table `log_belajar`
--
ALTER TABLE `log_belajar`
  ADD PRIMARY KEY (`id_logbelajar`),
  ADD KEY `idx_lb_user_youtube_open` (`users_id`,`waktu_selesai`),
  ADD KEY `fk_lb_materi` (`materi_id`),
  ADD KEY `fk_lb_submateri` (`submateri_id`),
  ADD KEY `waktu_selesai` (`waktu_selesai`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id_materi`),
  ADD KEY `idx_materi_deleted_at` (`deleted_at`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id_notif`),
  ADD KEY `fk_notifications_users` (`users_id`);

--
-- Indexes for table `opsi_jawaban`
--
ALTER TABLE `opsi_jawaban`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `percobaan_kuis`
--
ALTER TABLE `percobaan_kuis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progres_submateri`
--
ALTER TABLE `progres_submateri`
  ADD PRIMARY KEY (`id_progresSub`),
  ADD KEY `fk_prog_user` (`users_id`),
  ADD KEY `fk_prog_sub` (`submateri_id_subMateri`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id_schedule`),
  ADD KEY `users_id` (`users_id`);

--
-- Indexes for table `soal_kuis`
--
ALTER TABLE `soal_kuis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submateri`
--
ALTER TABLE `submateri`
  ADD PRIMARY KEY (`id_subMateri`),
  ADD KEY `idx_submateri_deleted_at` (`deleted_at`),
  ADD KEY `submateri_ibfk_1` (`materi_id_materi`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id_catatan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `catatan`
--
ALTER TABLE `catatan`
  MODIFY `id_catatan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logactivity`
--
ALTER TABLE `logactivity`
  MODIFY `id_logactivity` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_belajar`
--
ALTER TABLE `log_belajar`
  MODIFY `id_logbelajar` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materi`
--
ALTER TABLE `materi`
  MODIFY `id_materi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notif` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opsi_jawaban`
--
ALTER TABLE `opsi_jawaban`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `percobaan_kuis`
--
ALTER TABLE `percobaan_kuis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progres_submateri`
--
ALTER TABLE `progres_submateri`
  MODIFY `id_progresSub` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id_schedule` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `soal_kuis`
--
ALTER TABLE `soal_kuis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submateri`
--
ALTER TABLE `submateri`
  MODIFY `id_subMateri` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id_catatan` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `catatan`
--
ALTER TABLE `catatan`
  ADD CONSTRAINT `catatan_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `logactivity`
--
ALTER TABLE `logactivity`
  ADD CONSTRAINT `logactivity_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `log_belajar`
--
ALTER TABLE `log_belajar`
  ADD CONSTRAINT `fk_lb_materi` FOREIGN KEY (`materi_id`) REFERENCES `materi` (`id_materi`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lb_submateri` FOREIGN KEY (`submateri_id`) REFERENCES `submateri` (`id_subMateri`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lb_user` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `progres_submateri`
--
ALTER TABLE `progres_submateri`
  ADD CONSTRAINT `fk_prog_sub` FOREIGN KEY (`submateri_id_subMateri`) REFERENCES `submateri` (`id_subMateri`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_prog_user` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `submateri`
--
ALTER TABLE `submateri`
  ADD CONSTRAINT `submateri_ibfk_1` FOREIGN KEY (`materi_id_materi`) REFERENCES `materi` (`id_materi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
