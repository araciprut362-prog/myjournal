<?php
$pageTitle = 'Kelola Pertanyaan Refleksi — Admin MyJournal';
require __DIR__ . '/../../layout/header.php';
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-stone-200/80 dark:border-stone-800/80">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="index.php?page=admin-dashboard" class="text-xs font-semibold text-stone-400 hover:text-stone-700 dark:hover:text-stone-200 flex items-center gap-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Ringkasan</span>
                </a>
            </div>
            <h1 class="font-serif text-3xl font-bold text-stone-900 dark:text-stone-100">
                Pertanyaan Refleksi Harian
            </h1>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">
                Kelola pertanyaan reflektif untuk memantik renungan dan kesadaran diri para penulis jurnal.
            </p>
        </div>

        <button type="button" onclick="openCreateModal()"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-purple-700 hover:bg-purple-800 text-white font-semibold text-xs shadow-sm transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>+ Tambah Prompt Baru</span>
        </button>
    </div>

    <!-- Tabel Prompts -->
    <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl overflow-hidden shadow-sm">
        
        <?php if (empty($prompts)): ?>
            <div class="p-12 text-center space-y-3">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-400 flex items-center justify-center">
                    <i data-lucide="help-circle" class="w-6 h-6"></i>
                </div>
                <h3 class="font-semibold text-stone-800 dark:text-stone-200 text-sm">Belum ada pertanyaan refleksi</h3>
                <p class="text-xs text-stone-500 dark:text-stone-400">Tambahkan prompt untuk menginspirasi penulis setiap harinya.</p>
                <button type="button" onclick="openCreateModal()" class="px-4 py-2 bg-purple-700 text-white rounded-xl text-xs font-semibold">
                    + Tambah Prompt Pertama
                </button>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-stone-50 dark:bg-stone-850/60 text-stone-500 dark:text-stone-400 font-semibold uppercase tracking-wider border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="py-3.5 px-6 w-16">#</th>
                            <th class="py-3.5 px-6">Pertanyaan Refleksi</th>
                            <th class="py-3.5 px-6 w-28 text-center">Status</th>
                            <th class="py-3.5 px-6 w-36">Tanggal Dibuat</th>
                            <th class="py-3.5 px-6 w-36 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800 text-stone-800 dark:text-stone-200">
                        <?php foreach ($prompts as $p): ?>
                            <tr class="hover:bg-stone-50/60 dark:hover:bg-stone-850/40 transition">
                                <td class="py-4 px-6 font-mono text-stone-400">
                                    <?= $p['id'] ?>
                                </td>
                                <td class="py-4 px-6 font-serif text-sm font-medium text-stone-900 dark:text-stone-100">
                                    "<?= htmlspecialchars($p['question_text']) ?>"
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <form action="index.php?page=admin-prompt-toggle" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <button type="submit" title="Klik untuk mengubah status"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold transition <?= $p['is_active'] ? 'bg-emerald-100 hover:bg-emerald-200 dark:bg-emerald-950/60 dark:hover:bg-emerald-900 text-emerald-800 dark:text-emerald-300' : 'bg-stone-200 hover:bg-stone-300 dark:bg-stone-800 text-stone-600 dark:text-stone-400' ?>">
                                            <span class="w-1.5 h-1.5 rounded-full <?= $p['is_active'] ? 'bg-emerald-500' : 'bg-stone-400' ?>"></span>
                                            <span><?= $p['is_active'] ? 'Aktif' : 'Nonaktif' ?></span>
                                        </button>
                                    </form>
                                </td>
                                <td class="py-4 px-6 text-stone-400 dark:text-stone-500 text-[11px]">
                                    <?= date('d M Y', strtotime($p['created_at'])) ?>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <button type="button" onclick="openEditModal(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['question_text'])) ?>', <?= $p['is_active'] ? 'true' : 'false' ?>)"
                                                class="p-1.5 rounded-lg text-stone-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-950/40 transition">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <button type="button" onclick="confirmDelete(<?= $p['id'] ?>)"
                                                class="p-1.5 rounded-lg text-stone-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>

</div>

<!-- Modal Tambah Prompt -->
<div id="create-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-stone-100 dark:border-stone-800">
            <h3 class="font-serif font-bold text-lg text-stone-900 dark:text-stone-100">Tambah Prompt Refleksi</h3>
            <button type="button" onclick="closeCreateModal()" class="text-stone-400 hover:text-stone-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="index.php?page=admin-prompt-store" method="POST" class="space-y-4">
            <div>
                <label for="question_text" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1.5">
                    Teks Pertanyaan Refleksi
                </label>
                <textarea id="question_text" name="question_text" rows="4" required
                          class="w-full p-3 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 text-stone-900 dark:text-stone-100 placeholder-stone-400"
                          placeholder="Contoh: Hal baik apa yang kamu pelajari dari tantangan hari ini?"></textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" checked
                       class="rounded text-purple-600 focus:ring-purple-500">
                <label for="is_active" class="text-xs font-medium text-stone-700 dark:text-stone-300">
                    Aktifkan langsung untuk penulis
                </label>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 rounded-xl border border-stone-300 dark:border-stone-700 text-stone-600 dark:text-stone-300 text-xs font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-semibold shadow-sm transition">
                    Simpan Prompt
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Prompt -->
<div id="edit-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-stone-100 dark:border-stone-800">
            <h3 class="font-serif font-bold text-lg text-stone-900 dark:text-stone-100">Edit Pertanyaan Refleksi</h3>
            <button type="button" onclick="closeEditModal()" class="text-stone-400 hover:text-stone-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="index.php?page=admin-prompt-update" method="POST" class="space-y-4">
            <input type="hidden" name="id" id="edit-id" value="">
            <div>
                <label for="edit-question_text" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1.5">
                    Teks Pertanyaan Refleksi
                </label>
                <textarea id="edit-question_text" name="question_text" rows="4" required
                          class="w-full p-3 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 text-stone-900 dark:text-stone-100"></textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="edit-is_active" name="is_active" value="1"
                       class="rounded text-purple-600 focus:ring-purple-500">
                <label for="edit-is_active" class="text-xs font-medium text-stone-700 dark:text-stone-300">
                    Status aktif
                </label>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border border-stone-300 dark:border-stone-700 text-stone-600 dark:text-stone-300 text-xs font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-semibold shadow-sm transition">
                    Perbarui Prompt
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus Prompt -->
<div id="delete-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-3xl p-6 max-w-sm w-full shadow-2xl space-y-4">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 flex items-center justify-center mx-auto">
            <i data-lucide="trash-2" class="w-6 h-6"></i>
        </div>
        <div class="text-center space-y-1">
            <h3 class="font-bold text-stone-900 dark:text-stone-100 text-base">Hapus pertanyaan ini?</h3>
            <p class="text-xs text-stone-500 dark:text-stone-400">
                Penulis tidak akan lagi menerima pertanyaan refleksi ini di dashboard mereka.
            </p>
        </div>
        <form action="index.php?page=admin-prompt-delete" method="POST" class="flex items-center gap-2 pt-2">
            <input type="hidden" name="id" id="delete-id" value="">
            <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 py-2 rounded-xl border border-stone-300 dark:border-stone-700 text-stone-700 dark:text-stone-300 text-xs font-semibold">
                Batal
            </button>
            <button type="submit"
                    class="flex-1 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold shadow-sm">
                Hapus
            </button>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('create-modal').classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
    function closeCreateModal() {
        document.getElementById('create-modal').classList.add('hidden');
    }

    function openEditModal(id, text, isActive) {
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-question_text').value = text;
        document.getElementById('edit-is_active').checked = isActive;
        document.getElementById('edit-modal').classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    function confirmDelete(id) {
        document.getElementById('delete-id').value = id;
        document.getElementById('delete-modal').classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
</script>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
