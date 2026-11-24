<?php

class AuthController
{
    private User $userModel;

    public function __construct(PDO $db)
    {
        $this->userModel = new User($db);
    }

    public function showRegister(): void
    {
        if (Auth::check()) {
            if (Auth::isAdmin()) {
                header('Location: index.php?page=admin-dashboard');
                exit;
            }
            header('Location: index.php?page=dashboard');
            exit;
        }

        require __DIR__ . '/../views/auth/register.php';
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=register');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['password_confirmation'] ?? '';
        $pin = trim($_POST['pin_code'] ?? '');
        $confirmPin = trim($_POST['pin_code_confirmation'] ?? '');

        $errors = [];

        if (mb_strlen($name) < 2) {
            $errors[] = 'Nama lengkap minimal 2 karakter.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid.';
        }

        if (strlen($password) < 6) {
            $errors[] = 'Password minimal 6 karakter.';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Konfirmasi password tidak cocok.';
        }

        if (!preg_match('/^[0-9]{4,6}$/', $pin)) {
            $errors[] = 'PIN Keamanan harus terdiri dari 4 sampai 6 angka numerik.';
        }

        if ($pin !== $confirmPin) {
            $errors[] = 'Konfirmasi PIN tidak cocok.';
        }

        if ($this->userModel->findByEmail($email)) {
            $errors[] = 'Email ini sudah terdaftar. Silakan login.';
        }

        if (!empty($errors)) {
            Session::setFlash('errors', $errors);
            Session::set('old_register', [
                'name' => $name,
                'email' => $email
            ]);
            header('Location: index.php?page=register');
            exit;
        }

        try {
            $userId = $this->userModel->create(
                $name,
                $email,
                $password,
                $pin,
                'user'
            );

            Session::set('user', [
                'id' => $userId,
                'name' => $name,
                'email' => $email,
                'role' => 'user'
            ]);
            Session::set('pin_verified', true);
            Session::setFlash('success', 'Selamat datang di MyJournal! Ruang jurnal pribadimu berhasil dibuat.');

            header('Location: index.php?page=dashboard');
            exit;
        } catch (Exception $e) {
            Session::setFlash('errors', ['Pendaftaran gagal. Silakan coba lagi.']);
            header('Location: index.php?page=register');
            exit;
        }
    }

    public function showLogin(): void
    {
        if (Auth::check()) {
            if (Auth::isAdmin()) {
                header('Location: index.php?page=admin-dashboard');
                exit;
            }
            if (Auth::isPinVerified()) {
                header('Location: index.php?page=dashboard');
            } else {
                header('Location: index.php?page=pin-lock');
            }
            exit;
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Masukkan alamat email yang valid.';
        }

        if (empty($password)) {
            $errors[] = 'Password wajib diisi.';
        }

        if (!empty($errors)) {
            Session::setFlash('errors', $errors);
            header('Location: index.php?page=login');
            exit;
        }

        $user = $this->userModel->verifyLogin($email, $password);

        if (!$user) {
            Session::setFlash('errors', ['Email atau password salah.']);
            header('Location: index.php?page=login');
            exit;
        }

        Session::set('user', [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ]);

        if ($user['role'] === 'admin') {
            Session::set('pin_verified', true);
            Session::setFlash('success', 'Berhasil login sebagai Administrator.');
            header('Location: index.php?page=admin-dashboard');
            exit;
        }

        // Pengguna diarahkan ke verifikasi PIN keamanan
        Session::set('pin_verified', false);
        header('Location: index.php?page=pin-lock');
        exit;
    }

    public function showPinLock(): void
    {
        Auth::requireLogin();
        $user = Auth::user();
        require __DIR__ . '/../views/auth/pin_lock.php';
    }

    public function verifyPin(): void
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=pin-lock');
            exit;
        }

        $userId = Auth::id();
        $enteredPin = trim($_POST['pin_code'] ?? '');

        if (empty($enteredPin)) {
            Session::setFlash('errors', ['Masukkan PIN Keamanan pribadimu.']);
            header('Location: index.php?page=pin-lock');
            exit;
        }

        if ($this->userModel->verifyPin($userId, $enteredPin)) {
            Session::set('pin_verified', true);
            Session::setFlash('success', 'Kunci jurnal terbuka. Selamat datang kembali!');
            header('Location: index.php?page=dashboard');
            exit;
        }

        Session::setFlash('errors', ['PIN Keamanan salah. Silakan coba kembali.']);
        header('Location: index.php?page=pin-lock');
        exit;
    }

    public function lock(): void
    {
        Auth::requireLogin();
        Session::set('pin_verified', false);
        Session::setFlash('info', 'Jurnalmu telah dikunci untuk menjaga privasi.');
        header('Location: index.php?page=pin-lock');
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
