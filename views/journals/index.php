<?php
$pageTitle = 'Linimasa Jurnal — MyJournal';
require __DIR__ . '/../layout/header.php';

$moodConfig = [
    'Sangat Senang' => ['icon' => 'smile', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/40', 'text' => 'text-emerald-700 dark:text-emerald-300', 'border' => 'border-emerald-200 dark:border-emerald-800'],
    'Senang' => ['icon' => 'sun', 'bg' => 'bg-sky-50 dark:bg-sky-950/40', 'text' => 'text-sky-700 dark:text-sky-300', 'border' => 'border-sky-200 dark:border-sky-800'],
    'Biasa' => ['icon' => 'meh', 'bg' => 'bg-amber-50 dark:bg-amber-950/40', 'text' => 'text-amber-700 dark:text-amber-300', 'border' => 'border-amber-200 dark:border-amber-800'],
    'Sedih' => ['icon' => 'cloud-rain', 'bg' => 'bg-indigo-50 dark:bg-indigo-950/40', 'text' => 'text-indigo-700 dark:text-indigo-300', 'border' => 'border-indigo-200 dark:border-indigo-800'],
    'Stres' => ['icon' => 'flame', 'bg' => 'bg-rose-50 dark:bg-rose-950/40', 'text' => 'text-rose-700 dark:text-rose-300', 'border' => 'border-rose-200 dark:border-rose-800'],
    'Very Happy' => ['icon' => 'smile', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/40', 'text' => 'text-emerald-700 dark:text-emerald-300', 'border' => 'border-emerald-200 dark:border-emerald-800'],
    'Happy' => ['icon' => 'sun', 'bg' => 'bg-sky-50 dark:bg-sky-950/40', 'text' => 'text-sky-700 dark:text-sky-300', 'border' => 'border-sky-200 dark:border-sky-800'],
    'Neutral' => ['icon' => 'meh', 'bg' => 'bg-amber-50 dark:bg-amber-950/40', 'text' => 'text-amber-700 dark:text-amber-300', 'border' => 'border-amber-200 dark:border-amber-800'],
    'Sad' => ['icon' => 'cloud-rain', 'bg' => 'bg-indigo-50 dark:bg-indigo-950/40', 'text' => 'text-indigo-700 dark:text-indigo-300', 'border' => 'border-indigo-200 dark:border-indigo-800'],
    'Stressed' => ['icon' => 'flame', 'bg' => 'bg-rose-50 dark:bg-rose-950/40', 'text' => 'text-rose-700 dark:text-rose-300', 'border' => 'border-rose-200 dark:border-rose-800'],
];

$activeSearch = $_GET['q'] ?? '';
$activeMood = $_GET['mood'] ?? '';
$activeTag = $_GET['tag'] ?? '';
$activeFrom = $_GET['from'] ?? '';
$activeTo = $_GET['to'] ?? '';

$hasActiveFilters = !empty($activeSearch) || (!empty($activeMood) && $activeMood !== 'all' && $activeMood !== 'semua') || (!empty($activeTag) && $activeTag !== 'all' && $activeTag !== 'semua') || !empty($activeFrom) || !empty($activeTo);
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-stone-200/80 dark:border-stone-800/80">
        <div>
            <h1 class="font-serif text-3xl font-bold text-stone-900 dark:text-stone-100">
                Linimasa Refleksi
            </h1>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">
                Catatan kronologis perjalanan pikiran, rasa syukur, dan perkembangan emosimu.
            </p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="index.php?page=journal-create"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-semibold text-xs shadow-sm hover:shadow transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>+ Tulis Jurnal Baru</span>
            </a>
        </div>
    </div>

    <!-- Kotak Pencarian & Filter -->
    <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-5 sm:p-6 shadow-sm space-y-4">
        
        <form action="index.php" method="GET" class="space-y-4">
            <input type="hidden" name="page" value="journals">

            <!-- Pencarian Kata Kunci -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="q" value="<?= htmlspecialchars($activeSearch) ?>"
                       class="w-full pl-10 pr-4 py-2.5 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-2xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100 placeholder-stone-400"
                       placeholder="Cari berdasarkan judul, topik, rasa syukur, atau cerita...">
            </div>

            <!-- Baris Filter -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
                
                <!-- Filter Mood -->
                <div>
                    <label class="block font-semibold uppercase tracking-wider text-stone-500 dark:text-stone-400 mb-1">
                        Filter Mood
                    </label>
                    <select name="mood"
                            class="w-full px-3 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs text-stone-800 dark:text-stone-200 focus:ring-2 focus:ring-amber-500">
                        <option value="all">Semua Mood</option>
                        <option value="Sangat Senang" <?= $activeMood === 'Sangat Senang' ? 'selected' : '' ?>>😄 Sangat Senang</option>
                        <option value="Senang" <?= $activeMood === 'Senang' ? 'selected' : '' ?>>🙂 Senang</option>
                        <option value="Biasa" <?= $activeMood === 'Biasa' ? 'selected' : '' ?>>😐 Biasa</option>
                        <option value="Sedih" <?= $activeMood === 'Sedih' ? 'selected' : '' ?>>😔 Sedih</option>
                        <option value="Stres" <?= $activeMood === 'Stres' ? 'selected' : '' ?>>😣 Stres</option>
                    </select>
                </div>

                <!-- Filter Tag -->
                <div>
                    <label class="block font-semibold uppercase tracking-wider text-stone-500 dark:text-stone-400 mb-1">
                        Filter Tag
                    </label>
                    <select name="tag"
                            class="w-full px-3 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs text-stone-800 dark:text-stone-200 focus:ring-2 focus:ring-amber-500">
                        <option value="all">Semua Tag</option>
                        <?php foreach ($availableTags as $t): ?>
                            <option value="<?= htmlspecialchars($t) ?>" <?= $activeTag === $t ? 'selected' : '' ?>>
                                #<?= htmlspecialchars($t) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Dari Tanggal -->
                <div>
                    <label class="block font-semibold uppercase tracking-wider text-stone-500 dark:text-stone-400 mb-1">
                        Dari Tanggal
                    </label>
                    <input type="date" name="from" value="<?= htmlspecialchars($activeFrom) ?>"
                           class="w-full px-3 py-1.5 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs text-stone-800 dark:text-stone-200 focus:ring-2 focus:ring-amber-500">
                </div>

                <!-- Sampai Tanggal -->
                <div>
                    <label class="block font-semibold uppercase tracking-wider text-stone-500 dark:text-stone-400 mb-1">
                        Sampai Tanggal
                    </label>
                    <input type="date" name="to" value="<?= htmlspecialchars($activeTo) ?>"
                           class="w-full px-3 py-1.5 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs text-stone-800 dark:text-stone-200 focus:ring-2 focus:ring-amber-500">
                </div>

            </div>

            <!-- Tombol Terapkan & Reset -->
            <div class="flex items-center justify-between pt-1">
                <span class="text-xs text-stone-400 dark:text-stone-500">
                    Menampilkan <strong class="text-stone-700 dark:text-stone-300 font-semibold"><?= count($journals) ?></strong> <?= count($journals) === 1 ? 'catatan' : 'catatan' ?>
                </span>

                <div class="flex items-center gap-2">
                    <?php if ($hasActiveFilters): ?>
                        <a href="index.php?page=journals" class="px-3.5 py-1.5 rounded-xl border border-stone-300 dark:border-stone-700 text-stone-600 dark:text-stone-400 text-xs hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                            Hapus Filter
                        </a>
                    <?php endif; ?>
                    <button type="submit" class="px-4 py-1.5 bg-stone-900 hover:bg-stone-800 dark:bg-amber-600 dark:hover:bg-amber-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                        Terapkan Filter
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- Daftar Linimasa -->
    <?php if (empty($journals)): ?>
        <div class="bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-3xl p-12 text-center space-y-4">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center">
                <i data-lucide="compass" class="w-7 h-7"></i>
            </div>
            <div>
                <h3 class="font-serif text-lg font-bold text-stone-800 dark:text-stone-200">
                    Tidak ada catatan yang cocok
                </h3>
                <p class="text-xs text-stone-500 dark:text-stone-400 mt-1 max-w-sm mx-auto">
                    <?= $hasActiveFilters ? 'Tidak ada entri yang cocok dengan kriteria pencarianmu. Coba bersihkan filter.' : 'Ruang jurnalmu masih kosong. Mulailah menulis cerita pertamamu.' ?>
                </p>
            </div>
            <?php if ($hasActiveFilters): ?>
                <a href="index.php?page=journals" class="inline-flex items-center gap-1.5 px-4 py-2 bg-stone-100 dark:bg-stone-800 hover:bg-stone-200 text-stone-700 dark:text-stone-300 rounded-xl text-xs font-semibold transition">
                    Bersihkan Semua Filter
                </a>
            <?php else: ?>
                <a href="index.php?page=journal-create" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-600 to-amber-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    Tulis Jurnal Pertamamu
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($journals as $item): ?>
                <?php $mStyle = $moodConfig[$item['mood']] ?? $moodConfig['Biasa']; ?>
                <div class="group bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 sm:p-7 shadow-sm hover:border-amber-300 dark:hover:border-stone-700 hover:shadow-md transition">
                    
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold <?= $mStyle['bg'] ?> <?= $mStyle['text'] ?> border <?= $mStyle['border'] ?>">
                                    <i data-lucide="<?= $mStyle['icon'] ?>" class="w-3.5 h-3.5"></i>
                                    <?= htmlspecialchars($item['mood']) ?>
                                </span>
                                <span class="text-stone-300 dark:text-stone-700">•</span>
                                <time class="text-xs font-medium text-stone-500 dark:text-stone-400">
                                    <?= date('d M Y', strtotime($item['entry_date'])) ?>
                                </time>
                            </div>

                            <h2 class="font-serif text-xl sm:text-2xl font-bold text-stone-900 dark:text-stone-100 group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors pt-1">
                                <a href="index.php?page=journal-detail&id=<?= $item['id'] ?>">
                                    <?= htmlspecialchars($item['title']) ?>
                                </a>
                            </h2>
                        </div>

                        <!-- Aksi -->
                        <div class="flex items-center gap-1 self-start shrink-0">
                            <a href="index.php?page=journal-detail&id=<?= $item['id'] ?>" title="Lihat Jurnal"
                               class="p-2 rounded-xl text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="index.php?page=journal-edit&id=<?= $item['id'] ?>" title="Edit Jurnal"
                               class="p-2 rounded-xl text-stone-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-stone-800 transition">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>

                    <p class="text-sm text-stone-600 dark:text-stone-300 line-clamp-3 leading-relaxed mb-4">
                        <?= nl2br(htmlspecialchars($item['content'])) ?>
                    </p>

                    <!-- Foto kenangan jika ada -->
                    <?php if (!empty($item['image_path'])): ?>
                        <div class="mb-4">
                            <a href="index.php?page=journal-detail&id=<?= $item['id'] ?>" class="inline-block relative rounded-2xl overflow-hidden border border-stone-200 dark:border-stone-800 group/img">
                                <img src="public/<?= htmlspecialchars($item['image_path']) ?>" alt="Foto kenangan"
                                     class="h-28 w-44 object-cover group-hover/img:scale-105 transition-transform duration-300">
                                <div class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-black/60 text-white text-[10px] flex items-center gap-1">
                                    <i data-lucide="image" class="w-3 h-3"></i> Kenangan
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Rasa Syukur -->
                    <?php if (!empty($item['gratitudes'])): ?>
                        <div class="mb-4 p-3.5 rounded-2xl bg-amber-50/60 dark:bg-stone-850 border border-amber-100 dark:border-stone-800">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400 flex items-center gap-1 mb-1.5">
                                <i data-lucide="heart" class="w-3 h-3"></i> 3 Hal yang Disyukuri:
                            </span>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs text-stone-700 dark:text-stone-300">
                                <?php foreach ($item['gratitudes'] as $idx => $g): ?>
                                    <div class="flex items-start gap-1.5 truncate">
                                        <span class="text-amber-500 font-bold"><?= $idx + 1 ?>.</span>
                                        <span class="truncate"><?= htmlspecialchars($g) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Footer Kartu -->
                    <div class="pt-3 border-t border-stone-100 dark:border-stone-800 flex items-center justify-between text-xs">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <?php if (!empty($item['tags_array'])): ?>
                                <?php foreach ($item['tags_array'] as $tg): ?>
                                    <a href="index.php?page=journals&tag=<?= urlencode($tg) ?>"
                                       class="px-2 py-0.5 rounded-lg bg-stone-100 dark:bg-stone-800 hover:bg-amber-100 dark:hover:bg-amber-950/60 text-stone-600 dark:text-stone-400 hover:text-amber-800 dark:hover:text-amber-300 text-[11px] font-medium transition">
                                        #<?= htmlspecialchars($tg) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <a href="index.php?page=journal-detail&id=<?= $item['id'] ?>"
                           class="inline-flex items-center gap-1 font-semibold text-amber-700 dark:text-amber-400 hover:underline">
                            <span>Baca Selengkapnya</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
