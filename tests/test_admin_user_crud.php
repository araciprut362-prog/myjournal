<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/JournalPrompt.php';
require_once __DIR__ . '/../controllers/AdminController.php';

echo "=== MEMULAI PENGUJIAN CRUD USER OLEH ADMIN ===\n";

$db = (new Database())->connect();
$userModel = new User($db);

// 1. Uji Ambil Semua Pengguna
$users = $userModel->getAll();
echo "Total pengguna ditemukan: " . count($users) . "\n";
assert(count($users) >= 2, "Harus ada minimal 2 pengguna bawaan (admin & user)");

// 2. Uji Tambah Pengguna Baru oleh Admin
$testEmail = 'ujicoba_' . time() . '@example.com';
$newUserId = $userModel->create(
    'Budi Santoso',
    $testEmail,
    'rahasia123',
    '9876',
    'user'
);
$createdUser = $userModel->findById($newUserId);
echo "Pengguna baru berhasil dibuat dengan ID: " . $createdUser['id'] . "\n";
assert($createdUser['name'] === 'Budi Santoso');
assert($createdUser['email'] === $testEmail);
assert($userModel->verifyPin($createdUser['id'], '9876') === true, "Verifikasi PIN baru gagal");

// 3. Uji Admin Update (Ubah Nama, Email, Role, Reset Password & PIN)
$updateSuccess = $userModel->adminUpdate(
    $createdUser['id'],
    'Budi Santoso Edited',
    'edited_' . $testEmail,
    'admin',
    'newpassword456',
    '4321'
);
assert($updateSuccess === true, "Update data pengguna oleh admin gagal");

$updated = $userModel->findById($createdUser['id']);
assert($updated['name'] === 'Budi Santoso Edited', "Nama tidak terupdate");
assert($updated['role'] === 'admin', "Role tidak terupdate");
assert(password_verify('newpassword456', $updated['password']), "Password baru tidak terupdate");
assert($userModel->verifyPin($createdUser['id'], '4321') === true, "PIN baru tidak terupdate");
echo "Update pengguna oleh admin berhasil: " . $updated['name'] . " (Role: " . $updated['role'] . ")\n";

// 4. Uji Hapus Pengguna oleh Admin
$deleteSuccess = $userModel->delete($createdUser['id']);
assert($deleteSuccess === true, "Hapus data pengguna oleh admin gagal");
$deletedCheck = $userModel->findById($createdUser['id']);
assert($deletedCheck === null, "Pengguna yang dihapus masih ditemukan di database");
echo "Pengguna ID " . $createdUser['id'] . " berhasil dihapus dari database.\n";

// 5. Uji Proteksi Admin Menghapus Dirinya Sendiri
$admin = $userModel->findByEmail('admin@myjournal.com');
assert($admin !== null, "Akun admin bawaan tidak ditemukan");

// Set session seolah-olah admin sedang login
$_SESSION['user_id'] = $admin['id'];
$_SESSION['user_role'] = 'admin';
$_SESSION['pin_verified'] = true;

// Kita coba panggil AdminController deleteUser dengan id admin sendiri
ob_start();
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['id'] = $admin['id'];

// Jalankan controller
$adminController = new AdminController($db);
$adminController->deleteUser();
$output = ob_get_clean();

// Cek apakah admin masih tetap ada di DB
$adminCheck = $userModel->findById($admin['id']);
assert($adminCheck !== null, "ERROR KRITIS: Admin berhasil menghapus dirinya sendiri!");
echo "Proteksi admin diri sendiri terverifikasi: Admin tidak dapat menghapus akunnya sendiri yang sedang aktif.\n";

echo "=== SEMUA PENGUJIAN CRUD USER ADMIN BERHASIL 100% ===\n";
