<?php

class JournalPrompt
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getRandomActivePrompt(): ?array
    {
        $sql = "SELECT * FROM journal_prompts WHERE is_active = 1 ORDER BY RAND() LIMIT 1";
        $stmt = $this->db->query($sql);
        $prompt = $stmt->fetch();
        return $prompt ?: null;
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM journal_prompts ORDER BY is_active DESC, id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM journal_prompts WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $prompt = $stmt->fetch();
        return $prompt ?: null;
    }

    public function create(string $questionText, bool $isActive = true): int
    {
        $sql = "INSERT INTO journal_prompts (question_text, is_active) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            trim($questionText),
            $isActive ? 1 : 0
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $questionText, bool $isActive): bool
    {
        $sql = "UPDATE journal_prompts SET question_text = ?, is_active = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            trim($questionText),
            $isActive ? 1 : 0,
            $id
        ]);
    }

    public function toggleActive(int $id): bool
    {
        $sql = "UPDATE journal_prompts SET is_active = IF(is_active = 1, 0, 1) WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM journal_prompts WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
