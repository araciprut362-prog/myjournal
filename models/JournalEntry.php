<?php

class JournalEntry
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(
        int $userId,
        string $title,
        string $content,
        string $mood,
        array $tags,
        ?string $imagePath,
        string $entryDate
    ): int {
        $tagsJson = !empty($tags) ? json_encode(array_values($tags)) : null;

        $sql = "INSERT INTO journal_entries (user_id, title, content, mood, tags, image_path, entry_date)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $userId,
            $title,
            $content,
            $mood,
            $tagsJson,
            $imagePath,
            $entryDate
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(
        int $id,
        int $userId,
        string $title,
        string $content,
        string $mood,
        array $tags,
        ?string $imagePath,
        string $entryDate,
        bool $removeImage = false
    ): bool {
        $tagsJson = !empty($tags) ? json_encode(array_values($tags)) : null;

        if ($removeImage) {
            $current = $this->getById($id, $userId);
            if ($current && !empty($current['image_path'])) {
                $filePath = __DIR__ . '/../public/' . $current['image_path'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
            $sql = "UPDATE journal_entries
                    SET title = ?, content = ?, mood = ?, tags = ?, image_path = NULL, entry_date = ?
                    WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$title, $content, $mood, $tagsJson, $entryDate, $id, $userId]);
        }

        if ($imagePath !== null) {
            $current = $this->getById($id, $userId);
            if ($current && !empty($current['image_path']) && $current['image_path'] !== $imagePath) {
                $filePath = __DIR__ . '/../public/' . $current['image_path'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
            $sql = "UPDATE journal_entries
                    SET title = ?, content = ?, mood = ?, tags = ?, image_path = ?, entry_date = ?
                    WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$title, $content, $mood, $tagsJson, $imagePath, $entryDate, $id, $userId]);
        }

        $sql = "UPDATE journal_entries
                SET title = ?, content = ?, mood = ?, tags = ?, entry_date = ?
                WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$title, $content, $mood, $tagsJson, $entryDate, $id, $userId]);
    }

    public function delete(int $id, int $userId): bool
    {
        $current = $this->getById($id, $userId);
        if (!$current) {
            return false;
        }

        if (!empty($current['image_path'])) {
            $filePath = __DIR__ . '/../public/' . $current['image_path'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $stmt = $this->db->prepare("DELETE FROM journal_entries WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }

    public function getById(int $id, int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM journal_entries WHERE id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([$id, $userId]);
        $entry = $stmt->fetch();
        if (!$entry) {
            return null;
        }

        $entry['tags_array'] = !empty($entry['tags']) ? (json_decode($entry['tags'], true) ?: []) : [];

        $gratStmt = $this->db->prepare("SELECT content FROM gratitudes WHERE journal_entry_id = ? ORDER BY id ASC");
        $gratStmt->execute([$id]);
        $entry['gratitudes'] = $gratStmt->fetchAll(PDO::FETCH_COLUMN);

        return $entry;
    }

    public function getAllByUser(
        int $userId,
        ?string $search = null,
        ?string $mood = null,
        ?string $tag = null,
        ?string $startDate = null,
        ?string $endDate = null
    ): array {
        $sql = "SELECT e.* FROM journal_entries e WHERE e.user_id = ?";
        $params = [$userId];

        if (!empty($search)) {
            $sql .= " AND (e.title LIKE ? OR e.content LIKE ?)";
            $searchTerm = '%' . trim($search) . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($mood) && $mood !== 'all' && $mood !== 'semua') {
            $sql .= " AND e.mood = ?";
            $params[] = $mood;
        }

        if (!empty($startDate)) {
            $sql .= " AND e.entry_date >= ?";
            $params[] = $startDate;
        }

        if (!empty($endDate)) {
            $sql .= " AND e.entry_date <= ?";
            $params[] = $endDate;
        }

        $sql .= " ORDER BY e.entry_date DESC, e.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $entries = $stmt->fetchAll();

        $filtered = [];
        foreach ($entries as $row) {
            $tagsArray = !empty($row['tags']) ? (json_decode($row['tags'], true) ?: []) : [];
            $row['tags_array'] = $tagsArray;

            $gratStmt = $this->db->prepare("SELECT content FROM gratitudes WHERE journal_entry_id = ? ORDER BY id ASC");
            $gratStmt->execute([$row['id']]);
            $row['gratitudes'] = $gratStmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($tag) && $tag !== 'all' && $tag !== 'semua') {
                $normalizedTags = array_map('strtolower', array_map('trim', $tagsArray));
                if (!in_array(strtolower(trim($tag)), $normalizedTags, true)) {
                    continue;
                }
            }

            $filtered[] = $row;
        }

        return $filtered;
    }

    public function getRecentByUser(int $userId, int $limit = 5): array
    {
        $stmt = $this->db->prepare("SELECT * FROM journal_entries WHERE user_id = ? ORDER BY entry_date DESC, created_at DESC LIMIT ?");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $entries = $stmt->fetchAll();

        foreach ($entries as &$entry) {
            $entry['tags_array'] = !empty($entry['tags']) ? (json_decode($entry['tags'], true) ?: []) : [];
            $gratStmt = $this->db->prepare("SELECT content FROM gratitudes WHERE journal_entry_id = ? ORDER BY id ASC");
            $gratStmt->execute([$entry['id']]);
            $entry['gratitudes'] = $gratStmt->fetchAll(PDO::FETCH_COLUMN);
        }

        return $entries;
    }

    public function getTodayEntry(int $userId): ?array
    {
        $today = date('Y-m-d');
        $stmt = $this->db->prepare("SELECT * FROM journal_entries WHERE user_id = ? AND entry_date = ? LIMIT 1");
        $stmt->execute([$userId, $today]);
        $entry = $stmt->fetch();
        if (!$entry) {
            return null;
        }

        $entry['tags_array'] = !empty($entry['tags']) ? (json_decode($entry['tags'], true) ?: []) : [];
        $gratStmt = $this->db->prepare("SELECT content FROM gratitudes WHERE journal_entry_id = ? ORDER BY id ASC");
        $gratStmt->execute([$entry['id']]);
        $entry['gratitudes'] = $gratStmt->fetchAll(PDO::FETCH_COLUMN);

        return $entry;
    }

    public function getMemoryLanePhotos(int $userId): array
    {
        $sql = "SELECT id, title, mood, image_path, entry_date, created_at
                FROM journal_entries
                WHERE user_id = ? AND image_path IS NOT NULL AND image_path != ''
                ORDER BY entry_date DESC, created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getMoodStats(int $userId, int $days = 30): array
    {
        $since = date('Y-m-d', strtotime("-$days days"));
        $sql = "SELECT entry_date, mood
                FROM journal_entries
                WHERE user_id = ? AND entry_date >= ?
                ORDER BY entry_date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $since]);
        $rows = $stmt->fetchAll();

        $moodWeights = [
            'Sangat Senang' => 5,
            'Very Happy' => 5,
            'Senang' => 4,
            'Happy' => 4,
            'Biasa' => 3,
            'Neutral' => 3,
            'Sedih' => 2,
            'Sad' => 2,
            'Stres' => 1,
            'Stressed' => 1
        ];

        $labels = [];
        $scores = [];
        $moodCounts = [
            'Sangat Senang' => 0,
            'Senang' => 0,
            'Biasa' => 0,
            'Sedih' => 0,
            'Stres' => 0
        ];

        // Mapping helper to Indonesian
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

        $dateMap = [];
        foreach ($rows as $row) {
            $m = $mapToId[$row['mood']] ?? 'Biasa';
            if (isset($moodCounts[$m])) {
                $moodCounts[$m]++;
            }
            $score = $moodWeights[$row['mood']] ?? 3;
            $date = $row['entry_date'];
            if (!isset($dateMap[$date])) {
                $dateMap[$date] = [];
            }
            $dateMap[$date][] = $score;
        }

        foreach ($dateMap as $date => $scoreList) {
            $avgScore = round(array_sum($scoreList) / count($scoreList), 1);
            $labels[] = date('d M', strtotime($date));
            $scores[] = $avgScore;
        }

        $mostFrequentMood = 'Biasa';
        $maxCount = -1;
        foreach ($moodCounts as $m => $count) {
            if ($count > $maxCount && $count > 0) {
                $maxCount = $count;
                $mostFrequentMood = $m;
            }
        }
        if ($maxCount <= 0) {
            $mostFrequentMood = 'Belum ada catatan';
        }

        return [
            'labels' => $labels,
            'scores' => $scores,
            'counts' => $moodCounts,
            'total_in_period' => count($rows),
            'most_frequent_mood' => $mostFrequentMood
        ];
    }

    public function getAllDistinctTagsByUser(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT tags FROM journal_entries WHERE user_id = ? AND tags IS NOT NULL");
        $stmt->execute([$userId]);
        $allTags = [];
        while ($row = $stmt->fetch()) {
            $decoded = json_decode($row['tags'], true);
            if (is_array($decoded)) {
                foreach ($decoded as $t) {
                    $trimmed = trim($t);
                    if ($trimmed !== '') {
                        $allTags[$trimmed] = true;
                    }
                }
            }
        }
        return array_keys($allTags);
    }

    public function countByUser(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM journal_entries WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }
}
