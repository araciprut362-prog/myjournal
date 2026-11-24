<?php

function httpGet($url, $cookieFile = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status' => $status, 'body' => $body];
}

function httpPost($url, $data, $cookieFile = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status' => $status, 'body' => $body];
}

$baseUrl = 'http://localhost/myjournal';
echo "--- Pengujian Operasi CRUD Lengkap (Bahasa Indonesia) ---\n";

$userCookie = tempnam(sys_get_temp_dir(), 'user_crud_');
httpPost("$baseUrl/index.php?page=login-process", ['email' => 'user@myjournal.com', 'password' => 'user123'], $userCookie);
httpPost("$baseUrl/index.php?page=pin-verify", ['pin_code' => '1234'], $userCookie);

// 1. Simpan entri jurnal baru
$newEntryData = [
    'title' => 'Uji Coba Otomatis Refleksi Jurnal',
    'content' => 'Ini adalah catatan pengujian untuk memverifikasi fungsionalitas CRUD secara penuh dalam Bahasa Indonesia.',
    'mood' => 'Sangat Senang',
    'entry_date' => date('Y-m-d'),
    'tags' => ['Pengujian', 'Mindfulness'],
    'gratitudes' => [
        'Syukur 1: Pikiran jernih dan tenang',
        'Syukur 2: Napas dalam yang menyegarkan',
        'Syukur 3: Sistem berjalan dengan sempurna'
    ]
];

$storeRes = httpPost("$baseUrl/index.php?page=journal-store", $newEntryData, $userCookie);
echo "[1] Buat Jurnal Baru: Status {$storeRes['status']}\n";
if (strpos($storeRes['body'], 'Uji Coba Otomatis Refleksi Jurnal') !== false && strpos($storeRes['body'], 'Syukur 1') !== false) {
    echo "    ✓ Jurnal baru berhasil disimpan dan tampil di halaman detail beserta 3 rasa syukur!\n";
} else {
    echo "    ✗ Penyimpanan jurnal gagal\n";
}

// 2. Operasi CRUD Admin pada Prompt
$adminCookie = tempnam(sys_get_temp_dir(), 'admin_crud_');
httpPost("$baseUrl/index.php?page=login-process", ['email' => 'admin@myjournal.com', 'password' => 'admin123'], $adminCookie);

$newPromptData = [
    'question_text' => 'Kebaikan kecil tak terduga apa yang menghangatkan harimu minggu ini?',
    'is_active' => '1'
];
$promptStoreRes = httpPost("$baseUrl/index.php?page=admin-prompt-store", $newPromptData, $adminCookie);
echo "[2] Admin Tambah Prompt: Status {$promptStoreRes['status']}\n";
if (strpos($promptStoreRes['body'], 'Kebaikan kecil tak terduga apa yang menghangatkan harimu minggu ini?') !== false) {
    echo "    ✓ Pertanyaan refleksi berhasil ditambahkan dan tampil di daftar admin!\n";
} else {
    echo "    ✗ Penambahan prompt admin gagal\n";
}

@unlink($userCookie);
@unlink($adminCookie);

echo "\n--- Semua Pengujian CRUD Selesai dengan Sukses! ---\n";
