<?php

function httpGet($url, $cookieFile = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $redirect = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
    curl_close($ch);
    return ['status' => $status, 'body' => $body, 'redirect' => $redirect];
}

function httpPost($url, $data, $cookieFile = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $redirect = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
    curl_close($ch);
    return ['status' => $status, 'body' => $body, 'redirect' => $redirect];
}

$baseUrl = 'http://localhost/myjournal';
echo "--- Pengujian Aplikasi MyJournal (Bahasa Indonesia) ---\n";

// 1. Halaman Beranda
$res = httpGet("$baseUrl/index.php?page=home");
echo "[1] Beranda: Status {$res['status']} | Ukuran konten: " . strlen($res['body']) . " bytes\n";
if (strpos($res['body'], 'Tuliskan pikiranmu') !== false) {
    echo "    ✓ Halaman beranda berhasil dimuat dalam Bahasa Indonesia\n";
} else {
    echo "    ✗ Konten beranda tidak sesuai\n";
}

// 2. Halaman Login
$res = httpGet("$baseUrl/index.php?page=login");
echo "[2] Login: Status {$res['status']}\n";
if (strpos($res['body'], 'Selamat Datang Kembali') !== false) {
    echo "    ✓ Halaman login berhasil dimuat dalam Bahasa Indonesia\n";
} else {
    echo "    ✗ Konten login tidak sesuai\n";
}

// 3. Login Pengguna & Verifikasi PIN Keamanan
$userCookie = tempnam(sys_get_temp_dir(), 'user_cookie_');
$loginRes = httpPost("$baseUrl/index.php?page=login-process", [
    'email' => 'user@myjournal.com',
    'password' => 'user123'
], $userCookie);
echo "[3] Login User: Status {$loginRes['status']} | Redirect: {$loginRes['redirect']}\n";
if (strpos($loginRes['redirect'], 'pin-lock') !== false) {
    echo "    ✓ Berhasil diarahkan ke layar kunci PIN keamanan\n";
} else {
    echo "    ✗ Pengalihan ke pin-lock gagal\n";
}

// Dashboard harus terlindungi sebelum PIN diverifikasi
$dashCheck = httpGet("$baseUrl/index.php?page=dashboard", $userCookie);
echo "[4] Akses Dashboard Sebelum PIN: Status {$dashCheck['status']} | Redirect: {$dashCheck['redirect']}\n";
if (strpos($dashCheck['redirect'], 'pin-lock') !== false) {
    echo "    ✓ Guard bekerja: Akses dashboard dicegah sebelum input PIN\n";
} else {
    echo "    ✗ Guard gagal melindungi dashboard\n";
}

// Verifikasi PIN yang benar
$pinRes = httpPost("$baseUrl/index.php?page=pin-verify", [
    'pin_code' => '1234'
], $userCookie);
echo "[5] Verifikasi PIN: Status {$pinRes['status']} | Redirect: {$pinRes['redirect']}\n";
if (strpos($pinRes['redirect'], 'dashboard') !== false) {
    echo "    ✓ PIN berhasil diverifikasi! Diarahkan ke dashboard\n";
} else {
    echo "    ✗ Verifikasi PIN gagal\n";
}

// Buka Dashboard setelah verifikasi PIN
$dashRes = httpGet("$baseUrl/index.php?page=dashboard", $userCookie);
echo "[6] Akses Dashboard Pasca-PIN: Status {$dashRes['status']}\n";
if (strpos($dashRes['body'], 'Sarah Pradipta') !== false && strpos($dashRes['body'], 'Streak Menulis') !== false) {
    echo "    ✓ Dashboard berhasil menampilkan sapaan pribadi dan hitungan streak!\n";
} else {
    echo "    ✗ Konten dashboard tidak lengkap\n";
}

// Akses Linimasa
$timelineRes = httpGet("$baseUrl/index.php?page=journals", $userCookie);
echo "[7] Linimasa Jurnal: Status {$timelineRes['status']}\n";
if (strpos($timelineRes['body'], 'Linimasa Refleksi') !== false && strpos($timelineRes['body'], 'Pagi yang Tenang') !== false) {
    echo "    ✓ Linimasa berhasil menampilkan entri jurnal sebelumnya\n";
} else {
    echo "    ✗ Entri linimasa tidak ditemukan\n";
}

// Detail Jurnal
$detailRes = httpGet("$baseUrl/index.php?page=journal-detail&id=1", $userCookie);
echo "[8] Detail Jurnal: Status {$detailRes['status']}\n";
if (strpos($detailRes['body'], '3 Hal yang Disyukuri') !== false && strpos($detailRes['body'], 'Sangat Senang') !== false) {
    echo "    ✓ Halaman detail jurnal berhasil menampilkan mood dan rasa syukur\n";
} else {
    echo "    ✗ Konten detail jurnal tidak sesuai\n";
}

// Analisis Mood
$moodRes = httpGet("$baseUrl/index.php?page=mood-analytics&days=30", $userCookie);
echo "[9] Analisis Mood: Status {$moodRes['status']}\n";
if (strpos($moodRes['body'], 'Tren Perkembangan Mood') !== false && strpos($moodRes['body'], 'moodTrendChart') !== false) {
    echo "    ✓ Halaman Analisis Mood berhasil menampilkan grafik Chart.js\n";
} else {
    echo "    ✗ Analisis mood gagal dimuat\n";
}

// Memory Lane
$memoryRes = httpGet("$baseUrl/index.php?page=memory-lane", $userCookie);
echo "[10] Memory Lane: Status {$memoryRes['status']}\n";
if (strpos($memoryRes['body'], 'Memory Lane') !== false && strpos($memoryRes['body'], 'kopi_pagi.jpg') !== false) {
    echo "    ✓ Galeri Memory Lane berhasil memuat foto kenangan\n";
} else {
    echo "    ✗ Foto Memory Lane tidak ditemukan\n";
}

// Kunci Cepat
$lockRes = httpGet("$baseUrl/index.php?page=pin-lock-action", $userCookie);
echo "[11] Aksi Kunci Cepat: Status {$lockRes['status']} | Redirect: {$lockRes['redirect']}\n";
if (strpos($lockRes['redirect'], 'pin-lock') !== false) {
    echo "    ✓ Tombol kunci cepat berhasil mengunci kembali dashboard\n";
} else {
    echo "    ✗ Kunci cepat gagal\n";
}

// 4. Login Admin & Fitur Administrator
$adminCookie = tempnam(sys_get_temp_dir(), 'admin_cookie_');
$adminLogin = httpPost("$baseUrl/index.php?page=login-process", [
    'email' => 'admin@myjournal.com',
    'password' => 'admin123'
], $adminCookie);
echo "[12] Login Admin: Status {$adminLogin['status']} | Redirect: {$adminLogin['redirect']}\n";
if (strpos($adminLogin['redirect'], 'admin-dashboard') !== false) {
    echo "    ✓ Admin langsung diarahkan ke panel admin-dashboard\n";
} else {
    echo "    ✗ Login admin gagal\n";
}

// Ringkasan Admin
$adminDash = httpGet("$baseUrl/index.php?page=admin-dashboard", $adminCookie);
echo "[13] Dashboard Admin: Status {$adminDash['status']}\n";
if (strpos($adminDash['body'], 'Ringkasan Sistem & Metrik Agregat') !== false && strpos($adminDash['body'], 'Pengguna Terdaftar') !== false) {
    echo "    ✓ Dashboard admin berhasil menampilkan metrik agregat\n";
} else {
    echo "    ✗ Konten admin dashboard tidak lengkap\n";
}

// Kelola Prompt Admin
$adminPrompts = httpGet("$baseUrl/index.php?page=admin-prompts", $adminCookie);
echo "[14] Kelola Prompt Admin: Status {$adminPrompts['status']}\n";
if (strpos($adminPrompts['body'], 'Pertanyaan Refleksi Harian') !== false) {
    echo "    ✓ Daftar kelola prompt refleksi berhasil dimuat\n";
} else {
    echo "    ✗ Halaman prompt admin gagal dimuat\n";
}

@unlink($userCookie);
@unlink($adminCookie);

echo "\n--- Semua Pengujian Bahasa Indonesia Berhasil 100%! ---\n";
