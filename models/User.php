<?php

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function getAll(?string $search = null, ?string $role = null): array
    {
        $sql = "SELECT u.id, u.name, u.email, u.role, u.current_streak, u.last_entry_date, u.created_at,
                       COUNT(j.id) as total_entries
                FROM users u
                LEFT JOIN journal_entries j ON u.id = j.user_id
                WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (u.name LIKE ? OR u.email LIKE ?)";
            $searchTerm = '%' . trim($search) . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($role) && $role !== 'all' && $role !== 'semua') {
            $sql .= " AND u.role = ?";
            $params[] = $role;
        }

        $sql .= " GROUP BY u.id ORDER BY u.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(
        string $name,
        string $email,
        string $password,
        string $pin,
        string $role = 'user'
    ): int {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $pinHash = password_hash($pin, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, pin_code, role, current_streak)
                VALUES (?, ?, ?, ?, ?, 0)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $name,
            $email,
            $passwordHash,
            $pinHash,
            $role
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function adminUpdate(
        int $id,
        string $name,
        string $email,
        string $role,
        ?string $newPassword = null,
        ?string $newPin = null
    ): bool {
        $sql = "UPDATE users SET name = ?, email = ?, role = ?";
        $params = [trim($name), trim($email), $role];

        if (!empty($newPassword)) {
            $sql .= ", password = ?";
            $params[] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        if (!empty($newPin)) {
            $sql .= ", pin_code = ?";
            $params[] = password_hash($newPin, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        // Hapus file gambar pengguna terlebih dahulu
        $imgStmt = $this->db->prepare("SELECT image_path FROM journal_entries WHERE user_id = ? AND image_path IS NOT NULL AND image_path != ''");
        $imgStmt->execute([$id]);
        $images = $imgStmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($images as $imgPath) {
            $fullPath = __DIR__ . '/../public/' . $imgPath;
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }

        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function verifyLogin(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);
        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            return null;
        }

        return $user;
    }

    public function verifyPin(int $userId, string $pin): bool
    {
        $user = $this->findById($userId);
        if (!$user || empty($user['pin_code'])) {
            return false;
        }

        return password_verify($pin, $user['pin_code']);
    }

    public function updatePin(int $userId, string $newPin): bool
    {
        $pinHash = password_hash($newPin, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET pin_code = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$pinHash, $userId]);
    }

    public function recalculateStreak(int $userId): int
    {
        $sql = "SELECT DISTINCT entry_date FROM journal_entries WHERE user_id = ? ORDER BY entry_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($dates)) {
            $this->db->prepare("UPDATE users SET current_streak = 0, last_entry_date = NULL WHERE id = ?")->execute([$userId]);
            return 0;
        }

        $lastDateStr = $dates[0];
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        if ($lastDateStr !== $today && $lastDateStr !== $yesterday) {
            $this->db->prepare("UPDATE users SET current_streak = 0, last_entry_date = ? WHERE id = ?")->execute([$lastDateStr, $userId]);
            return 0;
        }

        $streak = 1;
        $prevDate = new DateTime($lastDateStr);

        for ($i = 1; $i < count($dates); $i++) {
            $currDate = new DateTime($dates[$i]);
            $diff = $prevDate->diff($currDate)->days;
            if ($diff === 1) {
                $streak++;
                $prevDate = $currDate;
            } else {
                break;
            }
        }

        $this->db->prepare("UPDATE users SET current_streak = ?, last_entry_date = ? WHERE id = ?")
                 ->execute([$streak, $lastDateStr, $userId]);

        return $streak;
    }

    public function getAdminMetrics(): array
    {
        $totalUsers = (int) $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
        $totalEntries = (int) $this->db->query("SELECT COUNT(*) FROM journal_entries")->fetchColumn();
        $totalGratitudes = (int) $this->db->query("SELECT COUNT(*) FROM gratitudes")->fetchColumn();
        $totalPrompts = (int) $this->db->query("SELECT COUNT(*) FROM journal_prompts WHERE is_active = 1")->fetchColumn();

        $moodStmt = $this->db->query("SELECT mood, COUNT(*) as total FROM journal_entries GROUP BY mood");
        $moodDist = [
            'Sangat Senang' => 0,
            'Senang' => 0,
            'Biasa' => 0,
            'Sedih' => 0,
            'Stres' => 0
        ];
        $mapToId = [
            'Very Happy' => 'Sangat Senang',
            'Happy' => 'Senang',
            'Neutral' => 'Biasa',
            'Sad' => 'Sedih',
            'Stressed' => 'Stres',
            'Sangat Senang' => 'Sangat Senang',
            'Senang' => 'Senang',
            'Biasa' => 'Biasa',
            'Sedih' => 'Sedih',
            'Stres' => 'Stres'
        ];
        foreach ($moodStmt->fetchAll() as $row) {
            $m = $mapToId[$row['mood']] ?? 'Biasa';
            if (isset($moodDist[$m])) {
                $moodDist[$m] += (int) $row['total'];
            }
        }

        $sevenDaysAgo = date('Y-m-d', strtotime('-6 days'));
        $actStmt = $this->db->prepare("
            SELECT entry_date, COUNT(*) as count 
            FROM journal_entries 
            WHERE entry_date >= ? 
            GROUP BY entry_date 
            ORDER BY entry_date ASC
        ");
        $actStmt->execute([$sevenDaysAgo]);
        $activityData = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $activityData[$d] = 0;
        }
        foreach ($actStmt->fetchAll() as $row) {
            $activityData[$row['entry_date']] = (int) $row['count'];
        }

        $avgStreakStmt = $this->db->query("SELECT AVG(current_streak) FROM users WHERE role = 'user'");
        $avgStreak = round((float) $avgStreakStmt->fetchColumn(), 1);

        return [
            'total_users' => $totalUsers,
            'total_entries' => $totalEntries,
            'total_gratitudes' => $totalGratitudes,
            'total_prompts' => $totalPrompts,
            'mood_distribution' => $moodDist,
            'activity_data' => $activityData,
            'avg_streak' => $avgStreak
        ];
    }
}
