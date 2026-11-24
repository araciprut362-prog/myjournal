<?php

class AdminController
{
    private User $userModel;
    private JournalPrompt $promptModel;

    public function __construct(PDO $db)
    {
        $this->userModel  = new User($db);
        $this->promptModel = new JournalPrompt($db);
    }

    // ==========================================
    // ADMIN DASHBOARD
    // ==========================================

    public function dashboard(): void
    {
        Auth::requireAdmin();

        $metrics      = $this->userModel->getAdminMetrics();
        $recentPrompts = array_slice($this->promptModel->getAll(), 0, 5);
        $recentUsers   = array_slice($this->userModel->getAll(), 0, 5);

        require __DIR__ . '/../views/admin/dashboard.php';
    }

    // ==========================================
    // CRUD DATA USER (PENGGUNA)
    // ==========================================

    public function users(): void
    {
        Auth::requireAdmin();

        $search = trim($_GET['q'] ?? '');
        $role   = trim($_GET['role'] ?? '');

        $users = $this->userModel->getAll($search ?: null, $role ?: null);

        require __DIR__ . '/../views/admin/users/index.php';
    }

    public function editUser(): void
    {
        Auth::requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            Session::setFlash('errors', ['ID pengguna tidak valid.']);
            header('Location: index.php?page=admin-users');
            exit;
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            Session::setFlash('errors', ['Pengguna tidak ditemukan.']);
            header('Location: index.php?page=admin-users');
            exit;
        }

        require __DIR__ . '/../views/admin/users/edit.php';
    }

    public function storeUser(): void
    {
        Auth::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin-users');
            exit;
        }

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $pin      = trim($_POST['pin_code'] ?? '');
        $role     = $_POST['role'] ?? 'user';

        $errors = [];

        if (mb_strlen($name) < 2) {
            $errors[] = 'Nama pengguna minimal 2 karakter.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Alamat email tidak valid.';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Kata sandi minimal 6 karakter.';
        }
        if (!preg_match('/^[0-9]{4,6}$/', $pin)) {
            $errors[] = 'PIN Keamanan harus 4 sampai 6 angka numerik.';
        }
        if (!in_array($role, ['user', 'admin'], true)) {
            $role = 'user';
        }
        if ($this->userModel->findByEmail($email)) {
            $errors[] = 'Email ini sudah digunakan oleh akun lain.';
        }

        if (!empty($errors)) {
            Session::setFlash('errors', $errors);
            header('Location: index.php?page=admin-users');
            exit;
        }

        try {
            $this->userModel->create($name, $email, $password, $pin, $role);
            Session::setFlash('success', 'Pengguna baru berhasil ditambahkan.');
        } catch (Exception $e) {
            Session::setFlash('errors', ['Gagal menambahkan pengguna. Silakan coba lagi.']);
        }

        header('Location: index.php?page=admin-users');
        exit;
    }

    public function updateUser(): void
    {
        Auth::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin-users');
            exit;
        }

        $id          = (int) ($_POST['id'] ?? 0);
        $name        = trim($_POST['name'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $role        = $_POST['role'] ?? 'user';
        $newPassword = $_POST['password'] ?? '';
        $newPin      = trim($_POST['pin_code'] ?? '');

        $user = $this->userModel->findById($id);
        if (!$user) {
            Session::setFlash('errors', ['Pengguna tidak ditemukan.']);
            header('Location: index.php?page=admin-users');
            exit;
        }

        $errors = [];

        if (mb_strlen($name) < 2) {
            $errors[] = 'Nama pengguna minimal 2 karakter.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Alamat email tidak valid.';
        }
        if ($email !== $user['email']) {
            $existing = $this->userModel->findByEmail($email);
            if ($existing) {
                $errors[] = 'Alamat email sudah dipakai oleh pengguna lain.';
            }
        }
        if (!empty($newPassword) && strlen($newPassword) < 6) {
            $errors[] = 'Kata sandi baru minimal 6 karakter.';
        }
        if (!empty($newPin) && !preg_match('/^[0-9]{4,6}$/', $newPin)) {
            $errors[] = 'PIN Keamanan baru harus 4 sampai 6 angka numerik.';
        }
        if (!in_array($role, ['user', 'admin'], true)) {
            $role = 'user';
        }
        if ($id === Auth::id() && $role !== 'admin') {
            $errors[] = 'Anda tidak dapat mencabut hak akses admin dari akun Anda sendiri yang sedang aktif.';
        }

        if (!empty($errors)) {
            Session::setFlash('errors', $errors);
            header('Location: index.php?page=admin-users');
            exit;
        }

        try {
            $this->userModel->adminUpdate(
                $id,
                $name,
                $email,
                $role,
                !empty($newPassword) ? $newPassword : null,
                !empty($newPin)      ? $newPin      : null
            );

            if ($id === Auth::id()) {
                $currentSession          = Session::get('user');
                $currentSession['name']  = $name;
                $currentSession['email'] = $email;
                $currentSession['role']  = $role;
                Session::set('user', $currentSession);
            }

            Session::setFlash('success', 'Data pengguna berhasil diperbarui.');
        } catch (Exception $e) {
            Session::setFlash('errors', ['Gagal memperbarui data pengguna.']);
        }

        header('Location: index.php?page=admin-users');
        exit;
    }

    public function deleteUser(): void
    {
        Auth::requireAdmin();

        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($id <= 0) {
            Session::setFlash('errors', ['ID pengguna tidak valid.']);
            header('Location: index.php?page=admin-users');
            exit;
        }

        if ($id === Auth::id()) {
            Session::setFlash('errors', ['Anda tidak dapat menghapus akun Anda sendiri saat sedang masuk.']);
            header('Location: index.php?page=admin-users');
            exit;
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            Session::setFlash('errors', ['Pengguna tidak ditemukan.']);
            header('Location: index.php?page=admin-users');
            exit;
        }

        try {
            $this->userModel->delete($id);
            Session::setFlash('success', "Akun pengguna '{$user['name']}' berhasil dihapus secara permanen.");
        } catch (Exception $e) {
            Session::setFlash('errors', ['Gagal menghapus pengguna.']);
        }

        header('Location: index.php?page=admin-users');
        exit;
    }

    // ==========================================
    // CRUD DATA PROMPT REFLEKSI
    // ==========================================

    public function prompts(): void
    {
        Auth::requireAdmin();

        $prompts = $this->promptModel->getAll();

        require __DIR__ . '/../views/admin/prompts/index.php';
    }

    public function storePrompt(): void
    {
        Auth::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin-prompts');
            exit;
        }

        $text     = trim($_POST['question_text'] ?? '');
        $isActive = !empty($_POST['is_active']);

        if (empty($text)) {
            Session::setFlash('errors', ['Teks pertanyaan prompt tidak boleh kosong.']);
        } else {
            $this->promptModel->create($text, $isActive);
            Session::setFlash('success', 'Pertanyaan refleksi baru berhasil ditambahkan.');
        }

        header('Location: index.php?page=admin-prompts');
        exit;
    }

    public function updatePrompt(): void
    {
        Auth::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin-prompts');
            exit;
        }

        $id       = (int) ($_POST['id'] ?? 0);
        $text     = trim($_POST['question_text'] ?? '');
        $isActive = !empty($_POST['is_active']);

        if (empty($text)) {
            Session::setFlash('errors', ['Teks pertanyaan prompt tidak boleh kosong.']);
        } else {
            $this->promptModel->update($id, $text, $isActive);
            Session::setFlash('success', 'Pertanyaan refleksi berhasil diperbarui.');
        }

        header('Location: index.php?page=admin-prompts');
        exit;
    }

    public function togglePrompt(): void
    {
        Auth::requireAdmin();

        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id > 0) {
            $this->promptModel->toggleActive($id);
            Session::setFlash('success', 'Status keaktifan prompt berhasil diubah.');
        }

        header('Location: index.php?page=admin-prompts');
        exit;
    }

    public function deletePrompt(): void
    {
        Auth::requireAdmin();

        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id > 0) {
            $this->promptModel->delete($id);
            Session::setFlash('success', 'Prompt refleksi berhasil dihapus.');
        }

        header('Location: index.php?page=admin-prompts');
        exit;
    }
}
