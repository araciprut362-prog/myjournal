<?php

class AnalyticsController
{
    private JournalEntry $journalModel;
    private User $userModel;

    public function __construct(PDO $db)
    {
        $this->journalModel = new JournalEntry($db);
        $this->userModel = new User($db);
    }

    public function mood(): void
    {
        Auth::requirePinVerified();
        $userId = Auth::id();

        $days = (int) ($_GET['days'] ?? 30);
        if ($days !== 7 && $days !== 30 && $days !== 90) {
            $days = 30;
        }

        $stats = $this->journalModel->getMoodStats($userId, $days);
        $totalEntries = $this->journalModel->countByUser($userId);
        $streak = $this->userModel->recalculateStreak($userId);

        require __DIR__ . '/../views/analytics/mood.php';
    }

    public function memoryLane(): void
    {
        Auth::requirePinVerified();
        $userId = Auth::id();

        $photos = $this->journalModel->getMemoryLanePhotos($userId);

        require __DIR__ . '/../views/analytics/memory_lane.php';
    }
}
