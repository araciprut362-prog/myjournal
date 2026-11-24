<?php

class Gratitude
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(int $journalEntryId, string $content): int
    {
        $sql = "INSERT INTO gratitudes (journal_entry_id, content) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$journalEntryId, trim($content)]);
        return (int) $this->db->lastInsertId();
    }

    public function getByJournalEntry(int $journalEntryId): array
    {
        $sql = "SELECT * FROM gratitudes WHERE journal_entry_id = ? ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$journalEntryId]);
        return $stmt->fetchAll();
    }

    public function deleteByJournalEntry(int $journalEntryId): bool
    {
        $sql = "DELETE FROM gratitudes WHERE journal_entry_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$journalEntryId]);
    }

    public function sync(int $journalEntryId, array $items): void
    {
        $this->deleteByJournalEntry($journalEntryId);
        foreach ($items as $item) {
            $trimmed = trim($item);
            if ($trimmed !== '') {
                $this->create($journalEntryId, $trimmed);
            }
        }
    }
}
