<?php

require_once __DIR__ . '/../config/Database.php';

$database = new Database();
$db = $database->connect();

echo "Memulai seeding database MyJournal...\n";

// Pastikan folder uploads tersedia
$uploadDir = __DIR__ . '/../public/uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Buat gambar sampel untuk Memory Lane jika belum ada
$sampleImages = ['kopi_pagi.jpg', 'senja_sore.jpg', 'sudut_baca.jpg'];
foreach ($sampleImages as $img) {
    $imgPath = $uploadDir . '/' . $img;
    if (!file_exists($imgPath)) {
        $im = imagecreatetruecolor(600, 400);
        if ($img === 'kopi_pagi.jpg') {
            $bg = imagecolorallocate($im, 245, 235, 224); // latte hangat
            $accent = imagecolorallocate($im, 179, 139, 109);
            $text = "Kopi Pagi & Kedamaian Hati";
        } elseif ($img === 'senja_sore.jpg') {
            $bg = imagecolorallocate($im, 254, 237, 222); // warna peach senja
            $accent = imagecolorallocate($im, 234, 138, 83);
            $text = "Jalan Santai di Bawah Senja";
        } else {
            $bg = imagecolorallocate($im, 232, 243, 238); // hijau sage
            $accent = imagecolorallocate($im, 94, 153, 131);
            $text = "Sudut Baca yang Tenang";
        }
        imagefilledrectangle($im, 0, 0, 600, 400, $bg);
        imagefilledellipse($im, 300, 200, 260, 260, $accent);
        imagefilledellipse($im, 300, 200, 220, 220, $bg);
        $textColor = imagecolorallocate($im, 60, 60, 60);
        imagestring($im, 5, 175, 190, $text, $textColor);
        imagejpeg($im, $imgPath, 85);
        imagedestroy($im);
        echo "Gambar sampel dibuat: $img\n";
    }
}

// 1. Seed Pengguna (Users)
$stmtUser = $db->prepare("INSERT INTO `users` (`name`, `email`, `password`, `pin_code`, `role`, `current_streak`, `last_entry_date`) VALUES (?, ?, ?, ?, ?, ?, ?)");

// Akun Admin
$adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
$adminPin = password_hash('1234', PASSWORD_DEFAULT);
$stmtUser->execute([
    'Admin MyJournal',
    'admin@myjournal.com',
    $adminPassword,
    $adminPin,
    'admin',
    0,
    null
]);
echo "Seeder Admin: admin@myjournal.com (Password: admin123, PIN: 1234)\n";

// Akun Penulis (User)
$userPassword = password_hash('user123', PASSWORD_DEFAULT);
$userPin = password_hash('1234', PASSWORD_DEFAULT);
$today = date('Y-m-d');
$stmtUser->execute([
    'Sarah Pradipta',
    'user@myjournal.com',
    $userPassword,
    $userPin,
    'user',
    4,
    $today
]);
$userId = $db->lastInsertId();
echo "Seeder User: user@myjournal.com (Password: user123, PIN: 1234)\n";

// 2. Seed Prompt Refleksi Harian (Bahasa Indonesia)
$prompts = [
    "Apa hal kecil yang membuatmu berhenti sejenak, bernapas lega, dan merasa bersyukur hari ini?",
    "Sebutkan satu keberhasilan kecil yang kamu raih minggu ini yang membuatmu bangga pada dirimu.",
    "Pikiran atau perasaan apa yang paling banyak hadir dalam benakmu hari ini? Bagaimana kamu merawatnya?",
    "Jika kamu bisa mengirimkan satu pesan hangat untuk dirimu 5 tahun yang lalu, apa yang ingin kamu sampaikan?",
    "Bentuk kepedulian atau batasan sehat apa yang berhasil kamu terapkan untuk dirimu sendiri belakangan ini?",
    "Ceritakan satu aroma, pemandangan, atau rasa makanan hari ini yang menghangatkan suasana hatimu."
];

$stmtPrompt = $db->prepare("INSERT INTO `journal_prompts` (`question_text`, `is_active`) VALUES (?, 1)");
foreach ($prompts as $p) {
    $stmtPrompt->execute([$p]);
}
echo "Seeded " . count($prompts) . " pertanyaan refleksi harian.\n";

// 3. Seed Entri Jurnal Realistis untuk Sarah
$entries = [
    [
        'title' => 'Pagi yang Tenang dan Sudut Pandang Baru',
        'content' => "Memulai pagi ini tanpa langsung membuka media sosial. Membuat seduhan kopi hangat dan duduk dekat jendela sambil mengamati sinar matahari menyelinap di antara dedaunan. Rasanya begitu damai bisa bernapas tenang sebelum mulai bekerja.\n\nHari ini pekerjaan berjalan lancar. Laporan penting berhasil selesai sebelum tenggat waktu, membuat pikiran terasa sangat lega.",
        'mood' => 'Sangat Senang',
        'tags' => json_encode(['Pribadi', 'Mindfulness', 'Pagi']),
        'image_path' => 'uploads/kopi_pagi.jpg',
        'entry_date' => $today,
        'gratitudes' => [
            'Aroma seduhan kopi hitam yang hangat di pagi hari',
            'Menyelesaikan tugas penting lebih cepat dari target',
            'Telepon singkat dan hangat bersama Ibu saat makan siang'
        ]
    ],
    [
        'title' => 'Jalan Santai di Waktu Senja & Refleksi Diri',
        'content' => "Sore tadi menyempatkan jalan santai di taman sekitar perumahan tepat saat senja keemasan. Angin sejuk sangat menyegarkan pikiran setelah seharian duduk di depan laptop.\n\nMengingat prompt refleksi hari ini: terkadang kita terlalu fokus pada target besar hingga lupa betapa banyak kebiasaan baik kecil yang sudah berhasil kita bangun secara konsisten.",
        'mood' => 'Senang',
        'tags' => json_encode(['Kesehatan', 'Alam', 'Kebiasaan']),
        'image_path' => 'uploads/senja_sore.jpg',
        'entry_date' => date('Y-m-d', strtotime('-1 day')),
        'gratitudes' => [
            'Warna langit senja yang begitu memukau',
            'Kesehatan tubuh untuk bisa berjalan santai',
            'Sup hangat buatan sendiri untuk makan malam'
        ]
    ],
    [
        'title' => 'Hujan Sore Hari dan Membaca Buku',
        'content' => "Sore hari diwarnai hujan yang cukup deras. Suara rintik air hujan di jendela menjadi musik pengiring yang sangat cocok untuk membaca buku dan merenung.\n\nSempat merasa lelah di siang hari, namun saya memilih beristirahat sejenak 20 menit daripada memaksakan diri meminum terlalu banyak kafein. Tubuh terasa jauh lebih bugar.",
        'mood' => 'Biasa',
        'tags' => json_encode(['Membaca', 'SelfCare', 'Hujan']),
        'image_path' => 'uploads/sudut_baca.jpg',
        'entry_date' => date('Y-m-d', strtotime('-2 days')),
        'gratitudes' => [
            'Suara hujan yang menenangkan suasana hati',
            'Satu bab buku yang sangat membuka wawasan',
            'Selimut hangat dan secangkir teh chamomile'
        ]
    ],
    [
        'title' => 'Menghadapi Hari Penuh Tantangan dengan Tenang',
        'content' => "Hari ini cukup padat dengan rapat berturut-turut dan kendala teknis yang tak terduga. Rasa cemas dan stres sempat muncul di siang hari. Namun saya ingat untuk berhenti sejenak, menarik napas panjang, dan menyelesaikan masalah satu per satu.\n\nBangga pada diri sendiri karena tidak membiarkan kepanikan menguasai emosi seharian penuh.",
        'mood' => 'Stres',
        'tags' => json_encode(['Pekerjaan', 'Tantangan', 'Mindfulness']),
        'image_path' => null,
        'entry_date' => date('Y-m-d', strtotime('-3 days')),
        'gratitudes' => [
            'Rekan kerja yang saling mendukung dan membantu',
            'Kemampuan diri untuk tetap tenang saat tertekan',
            'Mandi air hangat sebelum istirahat malam'
        ]
    ]
];

$stmtEntry = $db->prepare("INSERT INTO `journal_entries` (`user_id`, `title`, `content`, `mood`, `tags`, `image_path`, `entry_date`) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmtGrat = $db->prepare("INSERT INTO `gratitudes` (`journal_entry_id`, `content`) VALUES (?, ?)");

foreach ($entries as $e) {
    $stmtEntry->execute([
        $userId,
        $e['title'],
        $e['content'],
        $e['mood'],
        $e['tags'],
        $e['image_path'],
        $e['entry_date']
    ]);
    $entryId = $db->lastInsertId();

    foreach ($e['gratitudes'] as $g) {
        $stmtGrat->execute([$entryId, $g]);
    }
}

echo "Seeded " . count($entries) . " entri jurnal dengan rasa syukur dan foto kenangan.\n";
echo "Seeding database selesai dengan sukses!\n";
