<?php
$pageTitle = htmlspecialchars($entry['title']) . ' — MyJournal';
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

$mStyle = $moodConfig[$entry['mood']] ?? $moodConfig['Biasa'];

// Format hari dan bulan Indonesia
$namaHari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
$namaBulan = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];

$time = strtotime($entry['entry_date']);
$tanggalFormat = $namaHari[date('l', $time)] . ', ' . date('j', $time) . ' ' . $namaBulan[date('F', $time)] . ' ' . date('Y', $time);
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <!-- Top Action Navigation -->
    <div class="flex items-center justify-between pb-6 border-b border-stone-200/80 dark:border-stone-800/80 mb-8">
        <a href="index.php?page=journals" class="inline-flex items-center gap-1.5 text-xs font-semibold text-stone-500 hover:text-stone-900 dark:text-stone-400 dark:hover:text-stone-100 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Linimasa</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="index.php?page=journal-edit&id=<?= $entry['id'] ?>"
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border border-stone-300 dark:border-stone-700 hover:bg-stone-100 dark:hover:bg-stone-800 text-stone-700 dark:text-stone-300 text-xs font-semibold transition">
                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                <span>Edit</span>
            </a>
            <button type="button" onclick="confirmDelete(<?= $entry['id'] ?>)"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border border-rose-200 dark:border-rose-900/60 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 text-xs font-semibold transition">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                <span>Hapus</span>
            </button>
        </div>
    </div>

    <!-- Konten Artikel -->
    <article class="space-y-8">
        
        <!-- Header Meta -->
        <header class="space-y-4">
            <div class="flex flex-wrap items-center gap-2.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold <?= $mStyle['bg'] ?> <?= $mStyle['text'] ?> border <?= $mStyle['border'] ?>">
                    <i data-lucide="<?= $mStyle['icon'] ?>" class="w-3.5 h-3.5"></i>
                    <?= htmlspecialchars($entry['mood']) ?>
                </span>
                <span class="text-stone-400 dark:text-stone-600 text-xs">•</span>
                <time datetime="<?= $entry['entry_date'] ?>" class="text-xs font-medium text-stone-500 dark:text-stone-400">
                    <?= $tanggalFormat ?>
                </time>
            </div>

            <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-stone-900 dark:text-stone-50 leading-tight">
                <?= htmlspecialchars($entry['title']) ?>
            </h1>

            <?php if (!empty($entry['tags_array'])): ?>
                <div class="flex flex-wrap gap-1.5 pt-1">
                    <?php foreach ($entry['tags_array'] as $tag): ?>
                        <span class="px-2.5 py-1 rounded-lg bg-stone-100 dark:bg-stone-850 text-stone-600 dark:text-stone-400 text-xs font-medium">
                            #<?= htmlspecialchars($tag) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </header>

        <!-- Foto Kenangan -->
        <?php if (!empty($entry['image_path'])): ?>
            <div class="rounded-3xl overflow-hidden border border-stone-200/90 dark:border-stone-800 shadow-sm max-h-[500px]">
                <img src="public/<?= htmlspecialchars($entry['image_path']) ?>"
                     alt="<?= htmlspecialchars($entry['title']) ?>"
                     class="w-full h-full object-cover">
            </div>
        <?php endif; ?>

        <!-- Box 3 Rasa Syukur -->
        <?php if (!empty($entry['gratitudes'])): ?>
            <div class="p-6 rounded-3xl bg-amber-50/80 dark:bg-stone-900 border border-amber-200/90 dark:border-stone-800 shadow-sm space-y-3">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">
                    <i data-lucide="sparkle" class="w-4 h-4"></i>
                    <span>3 Hal yang Disyukuri Hari Ini</span>
                </div>
                <div class="space-y-2">
                    <?php foreach ($entry['gratitudes'] as $idx => $g): ?>
                        <div class="flex items-start gap-3 text-sm text-stone-800 dark:text-stone-200">
                            <span class="w-5 h-5 rounded-full bg-amber-200/80 dark:bg-amber-950 text-amber-800 dark:text-amber-300 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                <?= $idx + 1 ?>
                            </span>
                            <span class="leading-relaxed"><?= htmlspecialchars($g) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Isi Cerita Jurnal -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-6 sm:p-8 shadow-sm">
            <div class="prose prose-stone dark:prose-invert max-w-none text-base leading-relaxed text-stone-800 dark:text-stone-200 whitespace-pre-line font-normal">
                <?= nl2br(htmlspecialchars($entry['content'])) ?>
            </div>
        </div>

    </article>

</div>

<!-- Modal Konfirmasi Hapus -->
<div id="delete-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-3xl p-6 max-w-sm w-full shadow-2xl space-y-4">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto">
            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
        </div>
        <div class="text-center space-y-1">
            <h3 class="font-bold text-stone-900 dark:text-stone-100 text-lg">Hapus catatan jurnal ini?</h3>
            <p class="text-xs text-stone-500 dark:text-stone-400">
                Tindakan ini permanen. Catatan ini akan dihapus dari ruang pribadimu dan tidak dapat dikembalikan.
            </p>
        </div>
        <form id="delete-form" action="index.php?page=journal-delete" method="POST" class="flex items-center gap-2 pt-2">
            <input type="hidden" name="id" id="delete-id" value="">
            <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 py-2.5 rounded-xl border border-stone-300 dark:border-stone-700 text-stone-700 dark:text-stone-300 text-xs font-semibold hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                Simpan Entri
            </button>
            <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold shadow-md transition">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

<script>
    function confirmDelete(id) {
        document.getElementById('delete-id').value = id;
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
