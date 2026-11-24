<?php

class JournalController
{
    private JournalEntry $journalModel;
    private Gratitude $gratitudeModel;
    private User $userModel;
    private JournalPrompt $promptModel;

    public function __construct(PDO $db)
    {
        $this->journalModel = new JournalEntry($db);
        $this->gratitudeModel = new Gratitude($db);
        $this->userModel = new User($db);
        $this->promptModel = new JournalPrompt($db);
    }

    public function index(): void
    {
        Auth::requirePinVerified();
        $userId = Auth::id();

        $search = trim($_GET['q'] ?? '');
        $mood = trim($_GET['mood'] ?? '');
        $tag = trim($_GET['tag'] ?? '');
        $startDate = trim($_GET['from'] ?? '');
        $endDate = trim($_GET['to'] ?? '');

        $journals = $this->journalModel->getAllByUser(
            $userId,
            $search ?: null,
            $mood ?: null,
            $tag ?: null,
            $startDate ?: null,
            $endDate ?: null
        );

        $availableTags = $this->journalModel->getAllDistinctTagsByUser($userId);

        require __DIR__ . '/../views/journals/index.php';
    }

    public function create(): void
    {
        Auth::requirePinVerified();
        $userId = Auth::id();

        $presetTags = ['Pribadi', 'Mindfulness', 'Target', 'Sekolah', 'Pekerjaan', 'Keluarga', 'Kesehatan', 'Syukur', 'Kreativitas'];
        $userTags = $this->journalModel->getAllDistinctTagsByUser($userId);
        $allTags = array_unique(array_merge($presetTags, $userTags));

        $promptText = '';
        if (!empty($_GET['prompt'])) {
            $promptText = trim($_GET['prompt']);
        }

        require __DIR__ . '/../views/journals/create.php';
    }

    public function store(): void
    {
        Auth::requirePinVerified();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=journal-create');
            exit;
        }

        $userId = Auth::id();
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $mood = trim($_POST['mood'] ?? '');
        $entryDate = trim($_POST['entry_date'] ?? '');
        $rawTags = $_POST['tags'] ?? [];
        $gratitudes = $_POST['gratitudes'] ?? [];

        $allowedMoods = [
            'Sangat Senang', 'Senang', 'Biasa', 'Sedih', 'Stres',
            'Very Happy', 'Happy', 'Neutral', 'Sad', 'Stressed'
        ];
        $errors = [];

        if (empty($title)) {
            $errors[] = 'Judul jurnal wajib diisi.';
        }

        if (empty($content)) {
            $errors[] = 'Isi jurnal tidak boleh kosong.';
        }

        if (!in_array($mood, $allowedMoods, true)) {
            $errors[] = 'Silakan pilih suasana hati (mood) yang valid.';
        }

        if (empty($entryDate) || !strtotime($entryDate)) {
            $entryDate = date('Y-m-d');
        }

        $tagsList = [];
        if (is_array($rawTags)) {
            foreach ($rawTags as $t) {
                $cleaned = trim(ltrim(trim($t), '#'));
                if ($cleaned !== '') {
                    $tagsList[] = $cleaned;
                }
            }
        } elseif (is_string($rawTags)) {
            $parts = explode(',', $rawTags);
            foreach ($parts as $p) {
                $cleaned = trim(ltrim(trim($p), '#'));
                if ($cleaned !== '') {
                    $tagsList[] = $cleaned;
                }
            }
        }
        $tagsList = array_values(array_unique($tagsList));

        $imagePath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->handleUpload($_FILES['photo']);
            if (isset($uploadResult['error'])) {
                $errors[] = $uploadResult['error'];
            } else {
                $imagePath = $uploadResult['path'];
            }
        }

        if (!empty($errors)) {
            Session::setFlash('errors', $errors);
            Session::set('old_journal', [
                'title' => $title,
                'content' => $content,
                'mood' => $mood,
                'entry_date' => $entryDate,
                'tags' => $tagsList,
                'gratitudes' => $gratitudes
            ]);
            header('Location: index.php?page=journal-create');
            exit;
        }

        try {
            $entryId = $this->journalModel->create(
                $userId,
                $title,
                $content,
                $mood,
                $tagsList,
                $imagePath,
                $entryDate
            );

            if (is_array($gratitudes)) {
                $this->gratitudeModel->sync($entryId, $gratitudes);
            }

            $this->userModel->recalculateStreak($userId);

            Session::setFlash('success', 'Catatan jurnal berhasil disimpan dengan aman.');
            header('Location: index.php?page=journal-detail&id=' . $entryId);
            exit;
        } catch (Exception $e) {
            Session::setFlash('errors', ['Gagal menyimpan jurnal. Silakan coba kembali.']);
            header('Location: index.php?page=journal-create');
            exit;
        }
    }

    public function detail(): void
    {
        Auth::requirePinVerified();
        $userId = Auth::id();
        $id = (int) ($_GET['id'] ?? 0);

        $entry = $this->journalModel->getById($id, $userId);

        if (!$entry) {
            Session::setFlash('errors', ['Catatan jurnal tidak ditemukan.']);
            header('Location: index.php?page=journals');
            exit;
        }

        require __DIR__ . '/../views/journals/detail.php';
    }

    public function edit(): void
    {
        Auth::requirePinVerified();
        $userId = Auth::id();
        $id = (int) ($_GET['id'] ?? 0);

        $entry = $this->journalModel->getById($id, $userId);

        if (!$entry) {
            Session::setFlash('errors', ['Catatan jurnal tidak ditemukan.']);
            header('Location: index.php?page=journals');
            exit;
        }

        $presetTags = ['Pribadi', 'Mindfulness', 'Target', 'Sekolah', 'Pekerjaan', 'Keluarga', 'Kesehatan', 'Syukur', 'Kreativitas'];
        $userTags = $this->journalModel->getAllDistinctTagsByUser($userId);
        $allTags = array_unique(array_merge($presetTags, $userTags));

        require __DIR__ . '/../views/journals/edit.php';
    }

    public function update(): void
    {
        Auth::requirePinVerified();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=journals');
            exit;
        }

        $userId = Auth::id();
        $id = (int) ($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $mood = trim($_POST['mood'] ?? '');
        $entryDate = trim($_POST['entry_date'] ?? '');
        $rawTags = $_POST['tags'] ?? [];
        $gratitudes = $_POST['gratitudes'] ?? [];
        $removePhoto = !empty($_POST['remove_photo']);

        $entry = $this->journalModel->getById($id, $userId);
        if (!$entry) {
            Session::setFlash('errors', ['Catatan jurnal tidak ditemukan.']);
            header('Location: index.php?page=journals');
            exit;
        }

        $allowedMoods = [
            'Sangat Senang', 'Senang', 'Biasa', 'Sedih', 'Stres',
            'Very Happy', 'Happy', 'Neutral', 'Sad', 'Stressed'
        ];
        $errors = [];

        if (empty($title)) {
            $errors[] = 'Judul jurnal wajib diisi.';
        }

        if (empty($content)) {
            $errors[] = 'Isi jurnal tidak boleh kosong.';
        }

        if (!in_array($mood, $allowedMoods, true)) {
            $errors[] = 'Silakan pilih suasana hati (mood) yang valid.';
        }

        if (empty($entryDate) || !strtotime($entryDate)) {
            $entryDate = $entry['entry_date'];
        }

        $tagsList = [];
        if (is_array($rawTags)) {
            foreach ($rawTags as $t) {
                $cleaned = trim(ltrim(trim($t), '#'));
                if ($cleaned !== '') {
                    $tagsList[] = $cleaned;
                }
            }
        } elseif (is_string($rawTags)) {
            $parts = explode(',', $rawTags);
            foreach ($parts as $p) {
                $cleaned = trim(ltrim(trim($p), '#'));
                if ($cleaned !== '') {
                    $tagsList[] = $cleaned;
                }
            }
        }
        $tagsList = array_values(array_unique($tagsList));

        $imagePath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->handleUpload($_FILES['photo']);
            if (isset($uploadResult['error'])) {
                $errors[] = $uploadResult['error'];
            } else {
                $imagePath = $uploadResult['path'];
            }
        }

        if (!empty($errors)) {
            Session::setFlash('errors', $errors);
            header('Location: index.php?page=journal-edit&id=' . $id);
            exit;
        }

        try {
            $this->journalModel->update(
                $id,
                $userId,
                $title,
                $content,
                $mood,
                $tagsList,
                $imagePath,
                $entryDate,
                $removePhoto
            );

            if (is_array($gratitudes)) {
                $this->gratitudeModel->sync($id, $gratitudes);
            }

            $this->userModel->recalculateStreak($userId);

            Session::setFlash('success', 'Catatan jurnal berhasil diperbarui.');
            header('Location: index.php?page=journal-detail&id=' . $id);
            exit;
        } catch (Exception $e) {
            Session::setFlash('errors', ['Gagal memperbarui jurnal.']);
            header('Location: index.php?page=journal-edit&id=' . $id);
            exit;
        }
    }

    public function delete(): void
    {
        Auth::requirePinVerified();
        $userId = Auth::id();
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($this->journalModel->delete($id, $userId)) {
            $this->userModel->recalculateStreak($userId);
            Session::setFlash('success', 'Catatan jurnal berhasil dihapus.');
        } else {
            Session::setFlash('errors', ['Gagal menghapus entri. Jurnal tidak ditemukan.']);
        }

        header('Location: index.php?page=journals');
        exit;
    }

    private function handleUpload(array $file): array
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Gagal mengunggah berkas gambar (kode: ' . $file['error'] . ').'];
        }

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            return ['error' => 'Ukuran foto melebihi batas maksimal 5MB.'];
        }

        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return ['error' => 'Berkas yang diunggah bukan format gambar yang valid.'];
        }

        $mime = $imageInfo['mime'];
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif'
        ];

        if (!isset($allowed[$mime])) {
            return ['error' => 'Hanya format gambar JPG, PNG, WEBP, dan GIF yang didukung.'];
        }

        $ext = $allowed[$mime];
        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        $uploadDir = __DIR__ . '/../public/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $dest = $uploadDir . $filename;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return ['error' => 'Gagal menyimpan berkas di server.'];
        }

        return ['path' => 'uploads/' . $filename];
    }
}
