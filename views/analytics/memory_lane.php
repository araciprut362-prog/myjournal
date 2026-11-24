<?php
$pageTitle = 'Galeri Memory Lane — MyJournal';
require __DIR__ . '/../layout/header.php';

$moodConfig = [
    'Sangat Senang' => ['icon' => 'smile', 'bg' => 'bg-emerald-500/90 text-white'],
    'Senang' => ['icon' => 'sun', 'bg' => 'bg-sky-500/90 text-white'],
    'Biasa' => ['icon' => 'meh', 'bg' => 'bg-amber-500/90 text-white'],
    'Sedih' => ['icon' => 'cloud-rain', 'bg' => 'bg-indigo-500/90 text-white'],
    'Stres' => ['icon' => 'flame', 'bg' => 'bg-rose-500/90 text-white'],
    'Very Happy' => ['icon' => 'smile', 'bg' => 'bg-emerald-500/90 text-white'],
    'Happy' => ['icon' => 'sun', 'bg' => 'bg-sky-500/90 text-white'],
    'Neutral' => ['icon' => 'meh', 'bg' => 'bg-amber-500/90 text-white'],
    'Sad' => ['icon' => 'cloud-rain', 'bg' => 'bg-indigo-500/90 text-white'],
    'Stressed' => ['icon' => 'flame', 'bg' => 'bg-rose-500/90 text-white'],
];
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-stone-200/80 dark:border-stone-800/80">
        <div>
            <h1 class="font-serif text-3xl font-bold text-stone-900 dark:text-stone-100 flex items-center gap-2.5">
                <i data-lucide="image" class="w-7 h-7 text-purple-500"></i>
                <span>Galeri Kenangan (Memory Lane)</span>
            </h1>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">
                Koleksi visual momen-momen berhargamu, tersimpan rapi berurutan berdasarkan tanggal.
            </p>
        </div>

        <span class="text-xs text-stone-400 dark:text-stone-500">
            <?= count($photos) ?> <?= count($photos) === 1 ? 'foto kenangan' : 'foto kenangan' ?> tersimpan
        </span>
    </div>

    <!-- Grid Galeri -->
    <?php if (empty($photos)): ?>
        <div class="bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-3xl p-12 text-center space-y-4">
            <div class="w-16 h-16 mx-auto rounded-3xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                <i data-lucide="camera" class="w-8 h-8"></i>
            </div>
            <div>
                <h3 class="font-serif text-xl font-bold text-stone-800 dark:text-stone-200">
                    Belum ada foto kenangan yang diunggah
                </h3>
                <p class="text-xs text-stone-500 dark:text-stone-400 mt-1 max-w-sm mx-auto">
                    Saat menulis jurnal, lampirkan foto cangkir kopi pagimu, suasana jalan sore, atau momen bersama orang terkasih. Foto akan berkumpul di sini.
                </p>
            </div>
            <a href="index.php?page=journal-create" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-600 to-amber-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tulis Jurnal & Unggah Foto</span>
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($photos as $p): ?>
                <?php $mStyle = $moodConfig[$p['mood']] ?? $moodConfig['Biasa']; ?>
                <div class="group relative rounded-3xl overflow-hidden border border-stone-200/90 dark:border-stone-800 bg-stone-100 dark:bg-stone-850 shadow-sm hover:shadow-lg transition cursor-pointer"
                     onclick="openLightbox('public/<?= htmlspecialchars($p['image_path']) ?>', '<?= htmlspecialchars(addslashes($p['title'])) ?>', '<?= date('d M Y', strtotime($p['entry_date'])) ?>', '<?= $p['id'] ?>')">
                    
                    <div class="aspect-[4/3] w-full overflow-hidden">
                        <img src="public/<?= htmlspecialchars($p['image_path']) ?>"
                             alt="<?= htmlspecialchars($p['title']) ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>

                    <!-- Overlay Gradient & Detail -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-5 text-white">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold backdrop-blur-md <?= $mStyle['bg'] ?>">
                                <i data-lucide="<?= $mStyle['icon'] ?>" class="w-3 h-3"></i>
                                <?= htmlspecialchars($p['mood']) ?>
                            </span>
                            <span class="text-xs font-medium text-stone-200 backdrop-blur-md bg-black/30 px-2 py-0.5 rounded-lg">
                                <?= date('d M Y', strtotime($p['entry_date'])) ?>
                            </span>
                        </div>

                        <div class="space-y-1">
                            <h3 class="font-serif font-bold text-base leading-snug line-clamp-2">
                                <?= htmlspecialchars($p['title']) ?>
                            </h3>
                            <span class="text-[11px] text-amber-300 flex items-center gap-1 group-hover:underline">
                                <span>Klik untuk membaca cerita</span>
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </span>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<!-- Lightbox Modal -->
<div id="lightbox-modal" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center p-4" onclick="closeLightbox(event)">
    <div class="relative max-w-3xl w-full bg-stone-900 rounded-3xl overflow-hidden shadow-2xl border border-stone-800" onclick="event.stopPropagation()">
        
        <button type="button" onclick="closeLightbox(null)"
                class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-black/60 hover:bg-black/80 text-white flex items-center justify-center transition">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="max-h-[65vh] w-full bg-black/40 flex items-center justify-center overflow-hidden">
            <img id="lightbox-img" src="#" alt="Kenangan" class="max-h-[65vh] w-auto object-contain">
        </div>

        <div class="p-6 bg-stone-900 border-t border-stone-800 flex items-center justify-between gap-4">
            <div>
                <span id="lightbox-date" class="text-xs text-stone-400 font-medium block mb-1"></span>
                <h3 id="lightbox-title" class="font-serif text-lg font-bold text-white"></h3>
            </div>
            <a id="lightbox-link" href="#" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold shadow-sm transition shrink-0">
                <span>Buka Catatan Lengkap</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    </div>
</div>

<script>
    function openLightbox(imgSrc, title, date, id) {
        document.getElementById('lightbox-img').src = imgSrc;
        document.getElementById('lightbox-title').innerText = title;
        document.getElementById('lightbox-date').innerText = date;
        document.getElementById('lightbox-link').href = 'index.php?page=journal-detail&id=' + id;
        document.getElementById('lightbox-modal').classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeLightbox(e) {
        document.getElementById('lightbox-modal').classList.add('hidden');
    }
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
