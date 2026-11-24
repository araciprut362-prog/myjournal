<?php

class DashboardController
{
    private User $userModel;
    private JournalEntry $journalModel;
    private JournalPrompt $promptModel;

    public function __construct(PDO $db)
    {
        $this->userModel = new User($db);
        $this->journalModel = new JournalEntry($db);
        $this->promptModel = new JournalPrompt($db);
    }

    public function index(): void
    {
        Auth::requirePinVerified();

        $userId = Auth::id();

        $streak = $this->userModel->recalculateStreak($userId);
        $userData = $this->userModel->findById($userId);

        $dailyPrompt = $this->promptModel->getRandomActivePrompt();
        $todayEntry = $this->journalModel->getTodayEntry($userId);
        $totalEntries = $this->journalModel->countByUser($userId);
        $recentEntries = $this->journalModel->getRecentByUser($userId, 3);
        $memoryPhotos = $this->journalModel->getMemoryLanePhotos($userId);
        $totalPhotos = count($memoryPhotos);

        // Salam dinamis waktu Indonesia
        $hour = (int) date('H');
        if ($hour >= 4 && $hour < 11) {
            $greetingTime = 'Selamat pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            $greetingTime = 'Selamat siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $greetingTime = 'Selamat sore';
        } else {
            $greetingTime = 'Selamat malam';
        }

        // Format hari dan bulan Indonesia
        $namaHari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $namaBulan = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $hariIni = $namaHari[date('l')] . ', ' . date('j') . ' ' . $namaBulan[date('F')] . ' ' . date('Y');

        require __DIR__ . '/../views/dashboard/index.php';
    }
}
