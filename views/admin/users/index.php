<?php
$pageTitle = 'Kelola Pengguna — Admin MyJournal';
require __DIR__ . '/../../layout/header.php';

$activeSearch = $_GET['q'] ?? '';
$activeRole = $_GET['role'] ?? '';
$currentAdminId = Auth::id();
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-stone-200/80 dark:border-stone-800/80">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="index.php?page=admin-dashboard" class="text-xs font-semibold text-stone-400 hover:text-stone-700 dark:hover:text-stone-200 flex items-center gap-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Ringkasan</span>
                </a>
            </div>
            <h1 class="font-serif text-3xl font-bold text-stone-900 dark:text-stone-100 flex items-center gap-2.5">
                <i data-lucide="users" class="w-7 h-7 text-purple-600 dark:text-purple-400"></i>
                <span>Kelola Data Pengguna</span>
            </h1>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">
                Manajemen akun penulis dan administrator sistem MyJournal.
            </p>
        </div>

        <button type="button" onclick="openCreateUserModal()"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-purple-700 hover:bg-purple-800 text-white font-semibold text-xs shadow-sm transition">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>+ Tambah Pengguna Baru</span>
        </button>
    </div>

    <!-- Kotak Pencarian & Filter -->
    <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-5 sm:p-6 shadow-sm">
        <form action="index.php" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            <input type="hidden" name="page" value="admin-users">

            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="q" value="<?= htmlspecialchars($activeSearch) ?>"
                       class="w-full pl-10 pr-4 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs focus:ring-2 focus:ring-purple-500 text-stone-900 dark:text-stone-100 placeholder-stone-400"
                       placeholder="Cari berdasarkan nama atau email pengguna...">
            </div>

            <div class="w-full sm:w-48">
                <select name="role" class="w-full px-3 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs text-stone-800 dark:text-stone-200 focus:ring-2 focus:ring-purple-500">
                    <option value="all">Semua Peran</option>
                    <option value="user" <?= $activeRole === 'user' ? 'selected' : '' ?>>Penulis (User)</option>
                    <option value="admin" <?= $activeRole === 'admin' ? 'selected' : '' ?>>Administrator</option>
                </select>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-stone-900 hover:bg-stone-800 dark:bg-purple-700 dark:hover:bg-purple-800 text-white rounded-xl text-xs font-semibold transition">
                    Cari
                </button>
                <?php if (!empty($activeSearch) || (!empty($activeRole) && $activeRole !== 'all')): ?>
                    <a href="index.php?page=admin-users" class="px-3 py-2 border border-stone-300 dark:border-stone-700 text-stone-600 dark:text-stone-400 rounded-xl text-xs font-semibold hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabel Pengguna -->
    <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl overflow-hidden shadow-sm">
        
        <?php if (empty($users)): ?>
            <div class="p-12 text-center space-y-3">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-400 flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <h3 class="font-semibold text-stone-800 dark:text-stone-200 text-sm">Tidak ada pengguna ditemukan</h3>
                <p class="text-xs text-stone-500 dark:text-stone-400">Silakan sesuaikan filter atau tambahkan pengguna baru.</p>
                <button type="button" onclick="openCreateUserModal()" class="px-4 py-2 bg-purple-700 text-white rounded-xl text-xs font-semibold">
                    + Tambah Pengguna
                </button>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-stone-50 dark:bg-stone-850/60 text-stone-500 dark:text-stone-400 font-semibold uppercase tracking-wider border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="py-3.5 px-6">Pengguna</th>
                            <th class="py-3.5 px-6">Peran</th>
                            <th class="py-3.5 px-6 text-center">Total Jurnal</th>
                            <th class="py-3.5 px-6 text-center">Streak</th>
                            <th class="py-3.5 px-6">Terdaftar Pada</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800 text-stone-800 dark:text-stone-200">
                        <?php foreach ($users as $u): ?>
                            <tr class="hover:bg-stone-50/60 dark:hover:bg-stone-850/40 transition">
                                
                                <!-- Kolom Info Pengguna -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full <?= $u['role'] === 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' ?> flex items-center justify-center font-bold text-xs shrink-0">
                                            <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-stone-900 dark:text-stone-100 text-sm flex items-center gap-1.5">
                                                <span><?= htmlspecialchars($u['name']) ?></span>
                                                <?php if ($u['id'] == $currentAdminId): ?>
                                                    <span class="text-[10px] bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 font-semibold px-2 py-0.5 rounded-full">
                                                        Anda
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="text-stone-400 dark:text-stone-500 text-[11px] block mt-0.5">
                                                <?= htmlspecialchars($u['email']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom Peran -->
                                <td class="py-4 px-6">
                                    <?php if ($u['role'] === 'admin'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                            <i data-lucide="shield" class="w-3 h-3"></i> Administrator
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            <i data-lucide="feather" class="w-3 h-3"></i> Penulis
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Kolom Total Jurnal -->
                                <td class="py-4 px-6 text-center">
                                    <span class="font-semibold text-stone-800 dark:text-stone-200">
                                        <?= (int) ($u['total_entries'] ?? 0) ?>
                                    </span>
                                    <span class="text-[10px] text-stone-400 block">entri</span>
                                </td>

                                <!-- Kolom Streak -->
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-orange-600 dark:text-orange-400">
                                        <i data-lucide="flame" class="w-3.5 h-3.5"></i>
                                        <?= (int) $u['current_streak'] ?> hari
                                    </span>
                                </td>

                                <!-- Tanggal Daftar -->
                                <td class="py-4 px-6 text-stone-400 dark:text-stone-500 text-[11px]">
                                    <?= date('d M Y', strtotime($u['created_at'])) ?>
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <button type="button"
                                                onclick="openEditUserModal(<?= $u['id'] ?>, '<?= htmlspecialchars(addslashes($u['name'])) ?>', '<?= htmlspecialchars(addslashes($u['email'])) ?>', '<?= $u['role'] ?>')"
                                                title="Edit Pengguna"
                                                class="p-1.5 rounded-lg text-stone-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-950/40 transition">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>

                                        <?php if ($u['id'] != $currentAdminId): ?>
                                            <button type="button"
                                                    onclick="confirmDeleteUser(<?= $u['id'] ?>, '<?= htmlspecialchars(addslashes($u['name'])) ?>')"
                                                    title="Hapus Pengguna"
                                                    class="p-1.5 rounded-lg text-stone-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        <?php else: ?>
                                            <span class="p-1.5 text-stone-300 dark:text-stone-700 cursor-not-allowed" title="Anda tidak dapat menghapus akun Anda sendiri">
                                                <i data-lucide="shield-alert" class="w-4 h-4"></i>
                                            </span>
                                        <?php endif; ?>
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

<!-- Modal Tambah Pengguna Baru -->
<div id="create-user-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-stone-100 dark:border-stone-800">
            <h3 class="font-serif font-bold text-lg text-stone-900 dark:text-stone-100">Tambah Pengguna Baru</h3>
            <button type="button" onclick="closeCreateUserModal()" class="text-stone-400 hover:text-stone-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="index.php?page=admin-user-store" method="POST" class="space-y-3.5">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1">
                    Nama Lengkap
                </label>
                <input type="text" name="name" required
                       class="w-full px-3.5 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs focus:ring-2 focus:ring-purple-500 text-stone-900 dark:text-stone-100"
                       placeholder="Contoh: Dimas Prakoso">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1">
                    Alamat Email
                </label>
                <input type="email" name="email" required
                       class="w-full px-3.5 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs focus:ring-2 focus:ring-purple-500 text-stone-900 dark:text-stone-100"
                       placeholder="dimas@email.com">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1">
                        Kata Sandi
                    </label>
                    <input type="password" name="password" required minlength="6"
                           class="w-full px-3.5 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs focus:ring-2 focus:ring-purple-500 text-stone-900 dark:text-stone-100"
                           placeholder="Min. 6 karakter">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1">
                        PIN (4–6 Angka)
                    </label>
                    <input type="password" name="pin_code" required pattern="[0-9]{4,6}" maxlength="6" inputmode="numeric"
                           class="w-full px-3.5 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs tracking-widest font-mono focus:ring-2 focus:ring-purple-500 text-stone-900 dark:text-stone-100"
                           placeholder="••••">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1">
                    Peran Pengguna
                </label>
                <select name="role" class="w-full px-3.5 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs text-stone-900 dark:text-stone-100 focus:ring-2 focus:ring-purple-500">
                    <option value="user">Penulis (User Biasa)</option>
                    <option value="admin">Administrator Sistem</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3">
                <button type="button" onclick="closeCreateUserModal()" class="px-4 py-2 rounded-xl border border-stone-300 dark:border-stone-700 text-stone-600 dark:text-stone-300 text-xs font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-semibold shadow-sm transition">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Pengguna -->
<div id="edit-user-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-stone-100 dark:border-stone-800">
            <h3 class="font-serif font-bold text-lg text-stone-900 dark:text-stone-100">Edit Data Pengguna</h3>
            <button type="button" onclick="closeEditUserModal()" class="text-stone-400 hover:text-stone-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="index.php?page=admin-user-update" method="POST" class="space-y-3.5">
            <input type="hidden" name="id" id="edit-user-id" value="">

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1">
                    Nama Lengkap
                </label>
                <input type="text" name="name" id="edit-user-name" required
                       class="w-full px-3.5 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs focus:ring-2 focus:ring-purple-500 text-stone-900 dark:text-stone-100">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1">
                    Alamat Email
                </label>
                <input type="email" name="email" id="edit-user-email" required
                       class="w-full px-3.5 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs focus:ring-2 focus:ring-purple-500 text-stone-900 dark:text-stone-100">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1">
                    Peran Pengguna
                </label>
                <select name="role" id="edit-user-role" class="w-full px-3.5 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs text-stone-900 dark:text-stone-100 focus:ring-2 focus:ring-purple-500">
                    <option value="user">Penulis (User Biasa)</option>
                    <option value="admin">Administrator Sistem</option>
                </select>
            </div>

            <div class="pt-2 border-t border-stone-100 dark:border-stone-800">
                <p class="text-[11px] text-stone-400 dark:text-stone-500 mb-2">
                    Kosongkan kolom di bawah jika tidak ingin mengubah sandi atau PIN:
                </p>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-stone-600 dark:text-stone-400 mb-1">
                            Sandi Baru (Opsional)
                        </label>
                        <input type="password" name="password" minlength="6"
                               class="w-full px-3.5 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs focus:ring-2 focus:ring-purple-500 text-stone-900 dark:text-stone-100"
                               placeholder="Biarkan kosong">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone-600 dark:text-stone-400 mb-1">
                            PIN Baru (Opsional)
                        </label>
                        <input type="password" name="pin_code" pattern="[0-9]{4,6}" maxlength="6" inputmode="numeric"
                               class="w-full px-3.5 py-2 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-xs tracking-widest font-mono focus:ring-2 focus:ring-purple-500 text-stone-900 dark:text-stone-100"
                               placeholder="Biarkan kosong">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3">
                <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 rounded-xl border border-stone-300 dark:border-stone-700 text-stone-600 dark:text-stone-300 text-xs font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-semibold shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus Pengguna -->
<div id="delete-user-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-3xl p-6 max-w-sm w-full shadow-2xl space-y-4">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 flex items-center justify-center mx-auto">
            <i data-lucide="user-x" class="w-6 h-6"></i>
        </div>
        <div class="text-center space-y-1">
            <h3 class="font-bold text-stone-900 dark:text-stone-100 text-base">Hapus pengguna ini?</h3>
            <p class="text-xs text-stone-500 dark:text-stone-400">
                Akun <strong id="delete-user-name-label" class="text-stone-800 dark:text-stone-200"></strong> beserta seluruh catatan jurnal dan fotonya akan dihapus secara permanen.
            </p>
        </div>
        <form action="index.php?page=admin-user-delete" method="POST" class="flex items-center gap-2 pt-2">
            <input type="hidden" name="id" id="delete-user-id" value="">
            <button type="button" onclick="closeDeleteUserModal()"
                    class="flex-1 py-2 rounded-xl border border-stone-300 dark:border-stone-700 text-stone-700 dark:text-stone-300 text-xs font-semibold">
                Batal
            </button>
            <button type="submit"
                    class="flex-1 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold shadow-sm">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

<script>
    function openCreateUserModal() {
        document.getElementById('create-user-modal').classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
    function closeCreateUserModal() {
        document.getElementById('create-user-modal').classList.add('hidden');
    }

    function openEditUserModal(id, name, email, role) {
        document.getElementById('edit-user-id').value = id;
        document.getElementById('edit-user-name').value = name;
        document.getElementById('edit-user-email').value = email;
        document.getElementById('edit-user-role').value = role;
        document.getElementById('edit-user-modal').classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
    function closeEditUserModal() {
        document.getElementById('edit-user-modal').classList.add('hidden');
    }

    function confirmDeleteUser(id, name) {
        document.getElementById('delete-user-id').value = id;
        document.getElementById('delete-user-name-label').innerText = name;
        document.getElementById('delete-user-modal').classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
    function closeDeleteUserModal() {
        document.getElementById('delete-user-modal').classList.add('hidden');
    }
</script>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
