<?php
$pageTitle = 'Dashboard — MyJournal';
require __DIR__ . '/../layout/header.php';

// Mood styling helpers
$moodConfig = [
    'Sangat Senang' => ['icon' => 'smile', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/40', 'text' => 'text-emerald-700 dark:text-emerald-300', 'border' => 'border-emerald-200 dark:border-emerald-800'],
    'Senang' => ['icon' => 'sun', 'bg' => 'bg-sky-50 dark:bg-sky-950/40', 'text' => 'text-sky-700 dark:text-sky-300', 'border' => 'border-sky-200 dark:border-sky-800'],
    'Biasa' => ['icon' => 'meh', 'bg' => 'bg-amber-50 dark:bg-amber-950/40', 'text' => 'text-amber-700 dark:text-amber-300', 'border' => 'border-amber-200 dark:border-amber-800'],
    'Sedih' => ['icon' => 'cloud-rain', 'bg' => 'bg-indigo-50 dark:bg-indigo-950/40', 'text' => 'text-indigo-700 dark:text-indigo-300', 'border' => 'border-indigo-200 dark:border-indigo-800'],
    'Stres' => ['icon' => 'flame', 'bg' => 'bg-rose-50 dark:bg-rose-950/40', 'text' => 'text-rose-700 dark:text-rose-300', 'border' => 'border-rose-200 dark:border-rose-800'],
    // Alias cadangan bahasa Inggris
    'Very Happy' => ['icon' => 'smile', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/40', 'text' => 'text-emerald-700 dark:text-emerald-300', 'border' => 'border-emerald-200 dark:border-emerald-800'],
    'Happy' => ['icon' => 'sun', 'bg' => 'bg-sky-50 dark:bg-sky-950/40', 'text' => 'text-sky-700 dark:text-sky-300', 'border' => 'border-sky-200 dark:border-sky-800'],
    'Neutral' => ['icon' => 'meh', 'bg' => 'bg-amber-50 dark:bg-amber-950/40', 'text' => 'text-amber-700 dark:text-amber-300', 'border' => 'border-amber-200 dark:border-amber-800'],
    'Sad' => ['icon' => 'cloud-rain', 'bg' => 'bg-indigo-50 dark:bg-indigo-950/40', 'text' => 'text-indigo-700 dark:text-indigo-300', 'border' => 'border-indigo-200 dark:border-indigo-800'],
    'Stressed' => ['icon' => 'flame', 'bg' => 'bg-rose-50 dark:bg-rose-950/40', 'text' => 'text-rose-700 dark:text-rose-300', 'border' => 'border-rose-200 dark:border-rose-800'],
];
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8">
    
    <!-- Hero Greeting & Date -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-stone-200/80 dark:border-stone-800/80">
        <div>
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold tracking-wider uppercase text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/50 px-2.5 py-1 rounded-full border border-amber-200/80 dark:border-amber-900/60 mb-2">
                <i data-lucide="sparkle" class="w-3.5 h-3.5"></i>
                <?= $hariIni ?>
            </span>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 dark:text-stone-100">
                <?= $greetingTime ?>, <?= htmlspecialchars(Auth::name()) ?>
            </h1>
            <p class="text-sm text-stone-500 dark:text-stone-400 mt-1 max-w-xl">
                Tarik napas dalam-dalam. Ini adalah ruang aman pribadimu untuk menuangkan pikiran, mensyukuri hal-hal kecil, dan merawat diri.
            </p>
        </div>

        <!-- Tombol Aksi Cepat -->
        <div class="flex items-center gap-3">
            <a href="index.php?page=journal-create"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-semibold text-sm shadow-md shadow-amber-700/20 hover:shadow-lg transition">
                <i data-lucide="pen-tool" class="w-4 h-4"></i>
                <span>+ Tulis Jurnal Hari Ini</span>
            </a>
            <a href="index.php?page=pin-lock-action" title="Kunci Cepat Jurnal"
               class="p-3 rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 hover:bg-stone-100 dark:hover:bg-stone-850 text-stone-600 dark:text-stone-300 transition">
                <i data-lucide="shield" class="w-4 h-4"></i>
            </a>
        </div>
    </div>

    <!-- Banner Prompt Refleksi Harian -->
    <?php if ($dailyPrompt): ?>
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-50 via-stone-50 to-orange-50 dark:from-stone-900 dark:via-stone-900 dark:to-stone-850 border border-amber-200/90 dark:border-amber-900/40 p-6 sm:p-7 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 relative z-10">
                <div class="space-y-1.5 flex-1">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">
                        <i data-lucide="lightbulb" class="w-4 h-4"></i>
                        <span>Pertanyaan Refleksi Hari Ini</span>
                    </div>
                    <p class="font-serif text-lg sm:text-xl font-medium text-stone-800 dark:text-stone-100 italic">
                        "<?= htmlspecialchars($dailyPrompt['question_text']) ?>"
                    </p>
                </div>
                <a href="index.php?page=journal-create&prompt=<?= urlencode($dailyPrompt['question_text']) ?>"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-stone-800 hover:bg-amber-50 dark:hover:bg-stone-700 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800 text-xs font-semibold shadow-sm transition shrink-0">
                    <i data-lucide="message-square-plus" class="w-3.5 h-3.5"></i>
                    <span>Jawab di jurnal hari ini</span>
                </a>
            </div>
            <div class="absolute -right-8 -bottom-8 w-36 h-36 bg-amber-200/40 dark:bg-amber-800/10 rounded-full blur-2xl pointer-events-none"></div>
        </div>
    <?php endif; ?>

    <!-- Kartu Metrik Ringkasan -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        <!-- Streak Counter -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Streak Menulis</span>
                <div class="w-9 h-9 rounded-xl bg-orange-100 dark:bg-orange-950/60 text-orange-600 dark:text-orange-400 flex items-center justify-center">
                    <i data-lucide="flame" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-baseline gap-2">
                    <span class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 dark:text-stone-100">
                        <?= $streak ?>
                    </span>
                    <span class="text-xs font-medium text-stone-500 dark:text-stone-400">
                        hari berturut-turut
                    </span>
                </div>
                <p class="text-xs text-orange-600 dark:text-orange-400 font-medium mt-1">
                    <?= $streak > 0 ? '🔥 Hebat! Pertahankan kebiasaan baik ini!' : 'Mulai streak menulismu hari ini!' ?>
                </p>
            </div>
        </div>

        <!-- Total Entri -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Total Jurnal</span>
                <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center">
                    <i data-lucide="book" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-baseline gap-2">
                    <span class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 dark:text-stone-100">
                        <?= $totalEntries ?>
                    </span>
                    <span class="text-xs font-medium text-stone-500 dark:text-stone-400">catatan</span>
                </div>
                <p class="text-xs text-stone-400 dark:text-stone-500 mt-1">
                    Tersimpan rapi di ruang pribadimu
                </p>
            </div>
        </div>

        <!-- Mood Hari Ini -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Mood Hari Ini</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center">
                    <i data-lucide="smile" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-4">
                <?php if ($todayEntry): ?>
                    <?php $mStyle = $moodConfig[$todayEntry['mood']] ?? $moodConfig['Biasa']; ?>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold <?= $mStyle['bg'] ?> <?= $mStyle['text'] ?> border <?= $mStyle['border'] ?>">
                            <i data-lucide="<?= $mStyle['icon'] ?>" class="w-3.5 h-3.5"></i>
                            <?= htmlspecialchars($todayEntry['mood']) ?>
                        </span>
                    </div>
                    <p class="text-xs text-stone-400 dark:text-stone-500 mt-2 truncate">
                        Sudah dicatat hari ini
                    </p>
                <?php else: ?>
                    <div class="text-stone-400 dark:text-stone-500 text-sm italic">
                        Belum dicatat
                    </div>
                    <a href="index.php?page=journal-create" class="text-xs text-amber-600 dark:text-amber-400 hover:underline font-medium mt-1 block">
                        Catat perasaanmu sekarang →
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Memory Lane -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Memory Lane</span>
                <div class="w-9 h-9 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-400 flex items-center justify-center">
                    <i data-lucide="image" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-baseline gap-2">
                    <span class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 dark:text-stone-100">
                        <?= $totalPhotos ?>
                    </span>
                    <span class="text-xs font-medium text-stone-500 dark:text-stone-400">foto tersimpan</span>
                </div>
                <a href="index.php?page=memory-lane" class="text-xs text-purple-600 dark:text-purple-400 hover:underline font-medium mt-1 block">
                    Buka galeri kenangan →
                </a>
            </div>
        </div>

    </div>

    <!-- Menu Akses Cepat -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <a href="index.php?page=journal-create"
           class="group p-5 bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl hover:border-amber-400 dark:hover:border-amber-600 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <i data-lucide="feather" class="w-5 h-5"></i>
            </div>
            <h3 class="font-semibold text-stone-900 dark:text-stone-100 text-sm group-hover:text-amber-600 transition-colors">
                Tulis Jurnal Hari Ini
            </h3>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">
                Catat mood, 3 rasa syukur, dan ceritamu hari ini.
            </p>
        </a>

        <a href="index.php?page=journals"
           class="group p-5 bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl hover:border-amber-400 dark:hover:border-amber-600 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-2xl bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <i data-lucide="calendar" class="w-5 h-5"></i>
            </div>
            <h3 class="font-semibold text-stone-900 dark:text-stone-100 text-sm group-hover:text-sky-600 transition-colors">
                Linimasa Jurnal
            </h3>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">
                Cari, filter berdasarkan mood atau tag, dan buka lembaran lalu.
            </p>
        </a>

        <a href="index.php?page=mood-analytics"
           class="group p-5 bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl hover:border-amber-400 dark:hover:border-amber-600 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <i data-lucide="sparkles" class="w-5 h-5"></i>
            </div>
            <h3 class="font-semibold text-stone-900 dark:text-stone-100 text-sm group-hover:text-emerald-600 transition-colors">
                Analisis Mood
            </h3>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">
                Ketahui pola emosi dan tren suasana hatimu seiring waktu.
            </p>
        </a>

        <a href="index.php?page=memory-lane"
           class="group p-5 bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl hover:border-amber-400 dark:hover:border-amber-600 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <i data-lucide="camera" class="w-5 h-5"></i>
            </div>
            <h3 class="font-semibold text-stone-900 dark:text-stone-100 text-sm group-hover:text-purple-600 transition-colors">
                Memory Lane
            </h3>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">
                Lihat kembali foto dan momen berharga secara kronologis.
            </p>
        </a>

    </div>

    <!-- Bagian Entri Terbaru -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif text-xl font-bold text-stone-900 dark:text-stone-100">
                    Refleksi Terbaru
                </h2>
                <p class="text-xs text-stone-500 dark:text-stone-400">
                    Momen dan rasa syukur yang baru-baru ini kamu catat.
                </p>
            </div>
            <a href="index.php?page=journals" class="text-xs font-semibold text-amber-700 dark:text-amber-400 hover:underline flex items-center gap-1">
                <span>Lihat semua di Linimasa</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <?php if (empty($recentEntries)): ?>
            <div class="bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-3xl p-10 text-center space-y-3">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 flex items-center justify-center">
                    <i data-lucide="book-open" class="w-6 h-6"></i>
                </div>
                <h3 class="font-semibold text-stone-800 dark:text-stone-200">Jurnalmu masih menunggu</h3>
                <p class="text-xs text-stone-500 dark:text-stone-400 max-w-sm mx-auto">
                    Kamu belum menulis entri apapun. Klik tombol di bawah untuk menulis refleksi pertamamu!
                </p>
                <a href="index.php?page=journal-create" class="inline-flex items-center gap-2 px-4 py-2 bg-stone-900 hover:bg-stone-800 dark:bg-amber-600 dark:hover:bg-amber-700 text-white rounded-xl text-xs font-semibold transition">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    Tulis Entri Pertama
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <?php foreach ($recentEntries as $entry): ?>
                    <?php $mStyle = $moodConfig[$entry['mood']] ?? $moodConfig['Biasa']; ?>
                    <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 shadow-sm hover:border-amber-300 dark:hover:border-stone-700 transition flex flex-col justify-between">
                        <div>
                            <!-- Header dengan tanggal & mood -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-xs font-medium text-stone-400 dark:text-stone-500">
                                    <?= date('d M Y', strtotime($entry['entry_date'])) ?>
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold <?= $mStyle['bg'] ?> <?= $mStyle['text'] ?> border <?= $mStyle['border'] ?>">
                                    <i data-lucide="<?= $mStyle['icon'] ?>" class="w-3 h-3"></i>
                                    <?= htmlspecialchars($entry['mood']) ?>
                                </span>
                            </div>

                            <!-- Judul -->
                            <h3 class="font-serif font-bold text-stone-900 dark:text-stone-100 text-base line-clamp-1 mb-2">
                                <a href="index.php?page=journal-detail&id=<?= $entry['id'] ?>" class="hover:text-amber-600 transition">
                                    <?= htmlspecialchars($entry['title']) ?>
                                </a>
                            </h3>

                            <!-- Cuplikan Isi -->
                            <p class="text-xs text-stone-600 dark:text-stone-400 line-clamp-3 mb-4 leading-relaxed">
                                <?= nl2br(htmlspecialchars($entry['content'])) ?>
                            </p>

                            <!-- Cuplikan Syukur -->
                            <?php if (!empty($entry['gratitudes'])): ?>
                                <div class="bg-amber-50/70 dark:bg-stone-850 rounded-2xl p-3 mb-4 border border-amber-100 dark:border-stone-800/80">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400 flex items-center gap-1 mb-1">
                                        <i data-lucide="heart" class="w-3 h-3"></i> Disyukuri Hari Ini:
                                    </span>
                                    <ul class="text-xs text-stone-700 dark:text-stone-300 space-y-1">
                                        <?php foreach (array_slice($entry['gratitudes'], 0, 2) as $g): ?>
                                            <li class="line-clamp-1 flex items-start gap-1.5">
                                                <span class="text-amber-500">•</span>
                                                <span><?= htmlspecialchars($g) ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Footer Kartu -->
                        <div class="pt-3 border-t border-stone-100 dark:border-stone-800 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-1 overflow-hidden">
                                <?php if (!empty($entry['tags_array'])): ?>
                                    <span class="px-2 py-0.5 rounded-lg bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400 text-[10px] font-medium truncate">
                                        #<?= htmlspecialchars($entry['tags_array'][0]) ?>
                                    </span>
                                    <?php if (count($entry['tags_array']) > 1): ?>
                                        <span class="text-[10px] text-stone-400">+<?= count($entry['tags_array']) - 1 ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <a href="index.php?page=journal-detail&id=<?= $entry['id'] ?>" class="text-amber-700 dark:text-amber-400 font-semibold hover:underline">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
