<?php
$pageTitle = 'Tulis Jurnal Baru — MyJournal';
$old = Session::get('old_journal', []);
Session::remove('old_journal');
require __DIR__ . '/../layout/header.php';

$moodOptions = [
    'Sangat Senang' => ['icon' => 'smile', 'desc' => 'Bahagia & Penuh Syukur', 'color' => 'peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800'],
    'Senang' => ['icon' => 'sun', 'desc' => 'Gembira & Ceria', 'color' => 'peer-checked:bg-sky-500 peer-checked:text-white peer-checked:border-sky-500 text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-950/40 border-sky-200 dark:border-sky-800'],
    'Biasa' => ['icon' => 'meh', 'desc' => 'Tenang & Santai', 'color' => 'peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800'],
    'Sedih' => ['icon' => 'cloud-rain', 'desc' => 'Murung & Butuh Ruang', 'color' => 'peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 border-indigo-200 dark:border-indigo-800'],
    'Stres' => ['icon' => 'flame', 'desc' => 'Tertekan & Lelah', 'color' => 'peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500 text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800'],
];

$selectedMood = $old['mood'] ?? 'Senang';
$gratitudes = $old['gratitudes'] ?? ['', '', ''];
$selectedTags = $old['tags'] ?? ['Pribadi'];
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    
    <!-- Top Bar -->
    <div class="flex items-center justify-between pb-6 border-b border-stone-200/80 dark:border-stone-800/80 mb-8">
        <div>
            <a href="index.php?page=dashboard" class="text-xs font-semibold text-stone-400 hover:text-stone-700 dark:hover:text-stone-200 flex items-center gap-1 mb-1 transition">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Dashboard</span>
            </a>
            <h1 class="font-serif text-3xl font-bold text-stone-900 dark:text-stone-100">
                Tulis Jurnal Hari Ini
            </h1>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-0.5">
                Tuangkan ceritamu dengan jujur. Semua kata di sini aman, terenkripsi, dan hanya milikmu.
            </p>
        </div>

        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-900/60 text-xs font-medium">
            <i data-lucide="lock" class="w-3.5 h-3.5"></i>
            Terkunci & Aman
        </span>
    </div>

    <!-- Banner Inspirasi Prompt -->
    <?php if (!empty($promptText)): ?>
        <div class="mb-8 p-5 bg-amber-50/80 dark:bg-stone-900 border border-amber-200 dark:border-amber-900/50 rounded-2xl flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-amber-200/70 dark:bg-amber-950 text-amber-800 dark:text-amber-300 flex items-center justify-center shrink-0">
                <i data-lucide="sparkles" class="w-4 h-4"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400 block mb-0.5">
                    Prompt Refleksi Terpilih
                </span>
                <p class="font-serif text-base font-semibold text-stone-800 dark:text-stone-100 italic">
                    "<?= htmlspecialchars($promptText) ?>"
                </p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form Jurnal -->
    <form action="index.php?page=journal-store" method="POST" enctype="multipart/form-data" class="space-y-8">
        
        <!-- 1. Mood Selector -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-stone-900 dark:text-stone-100 text-sm flex items-center gap-2">
                        <i data-lucide="heart-pulse" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i>
                        <span>Bagaimana perasaanmu hari ini?</span>
                    </h2>
                    <p class="text-xs text-stone-400 dark:text-stone-500 mt-0.5">
                        Pilih suasana hati utama yang mewakili emosimu hari ini.
                    </p>
                </div>
                <span class="text-xs text-amber-600 dark:text-amber-400 font-medium">*Wajib</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                <?php foreach ($moodOptions as $moodKey => $moodData): ?>
                    <label class="cursor-pointer relative">
                        <input type="radio" name="mood" value="<?= $moodKey ?>" class="peer sr-only" <?= $selectedMood === $moodKey ? 'checked' : '' ?> required>
                        <div class="p-3.5 rounded-2xl border transition text-center flex flex-col items-center gap-1.5 <?= $moodData['color'] ?> hover:shadow-sm">
                            <i data-lucide="<?= $moodData['icon'] ?>" class="w-6 h-6"></i>
                            <span class="font-bold text-xs"><?= $moodKey ?></span>
                            <span class="text-[10px] opacity-75 hidden sm:block"><?= $moodData['desc'] ?></span>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 2. Tanggal & Judul -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1.5">
                        Judul Jurnal Hari Ini
                    </label>
                    <input id="title" name="title" type="text" required
                           value="<?= htmlspecialchars($old['title'] ?? '') ?>"
                           class="w-full px-4 py-3 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-base font-medium focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100 placeholder-stone-400"
                           placeholder="Beri judul bermakna untuk hari ini...">
                </div>

                <div>
                    <label for="entry_date" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1.5">
                        Tanggal Jurnal
                    </label>
                    <input id="entry_date" name="entry_date" type="date" required
                           value="<?= htmlspecialchars($old['entry_date'] ?? date('Y-m-d')) ?>"
                           class="w-full px-4 py-3 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100">
                </div>
            </div>

            <!-- Editor Isi Cerita -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="content" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400">
                        Cerita & Refleksi Harian
                    </label>
                    <div class="text-[11px] text-stone-400" id="word-counter">
                        0 kata • 0 karakter
                    </div>
                </div>
                <textarea id="content" name="content" rows="10" required
                          oninput="updateWordCount(this)"
                          class="w-full p-4 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-2xl text-sm leading-relaxed focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100 placeholder-stone-400"
                          placeholder="Apa saja yang kamu alami hari ini? Apa yang membuatmu tersenyum, apa yang membuatmu merenung, dan perasaan apa yang ingin kamu lepaskan saat ini? Tuangkan semuanya di sini..."><?= htmlspecialchars($old['content'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- 3. Bagian Rasa Syukur (3 Hal yang Disyukuri) -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-stone-900 dark:text-stone-100 text-sm flex items-center gap-2">
                        <i data-lucide="sparkle" class="w-4 h-4 text-amber-500"></i>
                        <span>3 Hal yang Saya Syukuri Hari Ini</span>
                    </h2>
                    <p class="text-xs text-stone-400 dark:text-stone-500 mt-0.5">
                        Mencatat 3 rasa syukur setiap hari secara ilmiah terbukti melatih pikiran untuk lebih tenang dan bahagia.
                    </p>
                </div>
            </div>

            <div class="space-y-3">
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 font-bold text-xs flex items-center justify-center shrink-0">
                            <?= $i + 1 ?>
                        </div>
                        <input type="text" name="gratitudes[]"
                               value="<?= htmlspecialchars($gratitudes[$i] ?? '') ?>"
                               class="flex-1 px-4 py-2.5 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100 placeholder-stone-400"
                               placeholder="Contoh: <?= ['Secangkir kopi hangat di pagi hari', 'Pesan penyemangat dari sahabat baik', 'Menikmati jalan sore santai tanpa tergesa'][$i] ?>">
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- 4. Tag / Kategori -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-4">
            <div>
                <h2 class="font-semibold text-stone-900 dark:text-stone-100 text-sm flex items-center gap-2">
                    <i data-lucide="tags" class="w-4 h-4 text-stone-500"></i>
                    <span>Tag & Kategori</span>
                </h2>
                <p class="text-xs text-stone-400 dark:text-stone-500 mt-0.5">
                    Klik tag yang tersedia atau tambahkan tag kustom baru di bawah.
                </p>
            </div>

            <!-- Pilihan Tag Cepat -->
            <div class="flex flex-wrap gap-2" id="preset-tags-container">
                <?php foreach ($allTags as $tg): ?>
                    <?php $isActive = in_array($tg, $selectedTags, true); ?>
                    <button type="button" onclick="toggleTagPill(this, '<?= htmlspecialchars($tg) ?>')"
                            class="tag-pill px-3 py-1.5 rounded-full text-xs font-medium border transition <?= $isActive ? 'bg-amber-600 text-white border-amber-600' : 'bg-stone-100 dark:bg-stone-850 text-stone-700 dark:text-stone-300 border-stone-200 dark:border-stone-700 hover:bg-stone-200' ?>">
                        #<?= htmlspecialchars($tg) ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div id="hidden-tags-inputs">
                <?php foreach ($selectedTags as $st): ?>
                    <input type="hidden" name="tags[]" value="<?= htmlspecialchars($st) ?>" id="input-tag-<?= md5($st) ?>">
                <?php endforeach; ?>
            </div>

            <!-- Tambah tag kustom -->
            <div class="flex items-center gap-2 max-w-sm pt-2">
                <input type="text" id="custom-tag-input"
                       class="flex-1 px-3.5 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100"
                       placeholder="Tambah tag baru (contoh: Liburan)...">
                <button type="button" onclick="addCustomTag()"
                        class="px-3.5 py-2 rounded-xl bg-stone-200 hover:bg-stone-300 dark:bg-stone-800 dark:hover:bg-stone-700 text-stone-800 dark:text-stone-200 text-xs font-semibold transition">
                    + Tambah
                </button>
            </div>
        </div>

        <!-- 5. Foto Kenangan (Memory Lane) -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-4">
            <div>
                <h2 class="font-semibold text-stone-900 dark:text-stone-100 text-sm flex items-center gap-2">
                    <i data-lucide="image" class="w-4 h-4 text-purple-500"></i>
                    <span>Foto Kenangan (Opsional)</span>
                </h2>
                <p class="text-xs text-stone-400 dark:text-stone-500 mt-0.5">
                    Lampirkan foto kenangan hari ini untuk disimpan di galeri Memory Lane.
                </p>
            </div>

            <div>
                <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp,image/gif"
                       onchange="previewImage(this)"
                       class="block w-full text-xs text-stone-500 dark:text-stone-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-stone-100 dark:file:bg-stone-800 file:text-stone-800 dark:file:text-stone-200 hover:file:bg-amber-100 cursor-pointer">
            </div>

            <!-- Pratinjau Foto -->
            <div id="image-preview-container" class="hidden mt-3">
                <div class="relative inline-block rounded-2xl overflow-hidden border border-stone-200 dark:border-stone-800 shadow-sm max-w-xs">
                    <img id="image-preview" src="#" alt="Pratinjau" class="h-48 w-auto object-cover rounded-2xl">
                    <button type="button" onclick="removeImagePreview()" class="absolute top-2 right-2 p-1.5 bg-black/60 hover:bg-black/80 text-white rounded-full transition">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="index.php?page=dashboard" class="px-5 py-3 rounded-2xl border border-stone-300 dark:border-stone-700 text-stone-700 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 text-sm font-medium transition">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-3 rounded-2xl bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white text-sm font-semibold shadow-md shadow-amber-700/20 hover:shadow-lg transition flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Simpan Catatan Jurnal</span>
            </button>
        </div>

    </form>
</div>

<script>
    function updateWordCount(textarea) {
        const text = textarea.value.trim();
        const words = text ? text.split(/\s+/).length : 0;
        const chars = textarea.value.length;
        document.getElementById('word-counter').innerText = `${words} kata • ${chars} karakter`;
    }

    function toggleTagPill(button, tag) {
        const hiddenContainer = document.getElementById('hidden-tags-inputs');
        const tagId = 'input-tag-' + btoa(unescape(encodeURIComponent(tag))).replace(/=/g, '');
        const existingInput = document.getElementById(tagId);

        if (existingInput) {
            existingInput.remove();
            button.className = "tag-pill px-3 py-1.5 rounded-full text-xs font-medium border transition bg-stone-100 dark:bg-stone-850 text-stone-700 dark:text-stone-300 border-stone-200 dark:border-stone-700 hover:bg-stone-200";
        } else {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'tags[]';
            input.value = tag;
            input.id = tagId;
            hiddenContainer.appendChild(input);
            button.className = "tag-pill px-3 py-1.5 rounded-full text-xs font-medium border transition bg-amber-600 text-white border-amber-600";
        }
    }

    function addCustomTag() {
        const input = document.getElementById('custom-tag-input');
        const val = input.value.trim().replace(/^#/, '');
        if (!val) return;

        const container = document.getElementById('preset-tags-container');
        const hiddenContainer = document.getElementById('hidden-tags-inputs');
        const tagId = 'input-tag-' + btoa(unescape(encodeURIComponent(val))).replace(/=/g, '');

        if (!document.getElementById(tagId)) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'tags[]';
            hidden.value = val;
            hidden.id = tagId;
            hiddenContainer.appendChild(hidden);

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = "tag-pill px-3 py-1.5 rounded-full text-xs font-medium border transition bg-amber-600 text-white border-amber-600";
            btn.innerText = '#' + val;
            btn.onclick = function() { toggleTagPill(btn, val); };
            container.appendChild(btn);
        }

        input.value = '';
    }

    function previewImage(input) {
        const container = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImagePreview() {
        const input = document.getElementById('photo');
        const container = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        input.value = '';
        preview.src = '#';
        container.classList.add('hidden');
    }
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
