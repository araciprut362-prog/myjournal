<?php

require_once __DIR__ . '/../config/Database.php';

$database = new Database();
$db = $database->connect();

echo "Memulai migrasi database MyJournal...\n";

// Nonaktifkan pemeriksaan foreign key sementara
$db->exec("SET FOREIGN_KEY_CHECKS = 0");

$tablesToDrop = [
    'gratitudes',
    'journal_tags',
    'journals',
    'journal_entries',
    'prompts',
    'journal_prompts',
    'streaks',
    'tags',
    'users'
];

foreach ($tablesToDrop as $tbl) {
    $db->exec("DROP TABLE IF EXISTS `$tbl`");
    echo "Menghapus tabel `$tbl` (jika ada).\n";
}

// 1. Tabel users
$sqlUsers = "CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `pin_code` VARCHAR(255) NOT NULL,
    `role` ENUM('user', 'admin') DEFAULT 'user' NOT NULL,
    `current_streak` INT DEFAULT 0 NOT NULL,
    `last_entry_date` DATE NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
$db->exec($sqlUsers);
echo "Tabel `users` berhasil dibuat.\n";

// 2. Tabel journal_entries (Mendukung mood bahasa Indonesia dan Inggris)
$sqlEntries = "CREATE TABLE `journal_entries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `mood` ENUM('Sangat Senang', 'Senang', 'Biasa', 'Sedih', 'Stres', 'Very Happy', 'Happy', 'Neutral', 'Sad', 'Stressed') NOT NULL,
    `tags` JSON NULL,
    `image_path` VARCHAR(255) NULL,
    `entry_date` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_journal_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
$db->exec($sqlEntries);
echo "Tabel `journal_entries` berhasil dibuat.\n";

// 3. Tabel gratitudes
$sqlGratitudes = "CREATE TABLE `gratitudes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `journal_entry_id` INT NOT NULL,
    `content` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_gratitude_entry` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
$db->exec($sqlGratitudes);
echo "Tabel `gratitudes` berhasil dibuat.\n";

// 4. Tabel journal_prompts
$sqlPrompts = "CREATE TABLE `journal_prompts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `question_text` TEXT NOT NULL,
    `is_active` TINYINT(1) DEFAULT 1 NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
$db->exec($sqlPrompts);
echo "Tabel `journal_prompts` berhasil dibuat.\n";

// Aktifkan kembali pemeriksaan foreign key
$db->exec("SET FOREIGN_KEY_CHECKS = 1");

echo "Migrasi database selesai dengan sukses!\n";
