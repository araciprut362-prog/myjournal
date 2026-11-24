<?php
$pageTitle = 'Masuk ke Jurnal — MyJournal';
require __DIR__ . '/../layout/header.php';
?>

<div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center shadow-inner mb-4">
                <i data-lucide="lock-keyhole" class="w-7 h-7"></i>
            </div>
            <h2 class="font-serif text-3xl font-bold tracking-tight text-stone-900 dark:text-stone-100">
                Selamat Datang Kembali
            </h2>
            <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">
                Buka ruang pribadimu dan tenangkan pikiran dari hiruk-pikuk hari ini.
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 sm:p-8 shadow-sm">
            <form action="index.php?page=login-process" method="POST" class="space-y-5">
                
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input id="email" name="email" type="email" required autocomplete="email"
                               class="w-full pl-10 pr-4 py-2.5 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100 transition placeholder-stone-400"
                               placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400">
                            Kata Sandi
                        </label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <i data-lucide="key" class="w-4 h-4"></i>
                        </div>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                               class="w-full pl-10 pr-4 py-2.5 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100 transition placeholder-stone-400"
                               placeholder="••••••••">
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 bg-stone-900 hover:bg-stone-800 dark:bg-amber-600 dark:hover:bg-amber-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-stone-900/10 dark:shadow-amber-900/20 transition flex items-center justify-center gap-2">
                    <span>Masuk ke Jurnal</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <!-- Akun Uji Coba Cepat -->
            <div class="mt-6 pt-5 border-t border-stone-200 dark:border-stone-800">
                <p class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500 text-center mb-3">
                    Akun Uji Coba Cepat
                </p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" onclick="fillCredentials('user@myjournal.com', 'user123')"
                            class="p-2.5 rounded-xl border border-stone-200 dark:border-stone-800 bg-stone-50 hover:bg-amber-50 dark:bg-stone-850 dark:hover:bg-amber-950/30 text-stone-700 dark:text-stone-300 hover:text-amber-800 dark:hover:text-amber-400 transition text-left">
                        <span class="font-bold block text-stone-900 dark:text-stone-100">Penulis (User)</span>
                        <span class="text-[11px] text-stone-500">user@myjournal.com</span>
                        <span class="text-[10px] text-amber-600 block mt-0.5 font-mono">PIN: 1234</span>
                    </button>
                    <button type="button" onclick="fillCredentials('admin@myjournal.com', 'admin123')"
                            class="p-2.5 rounded-xl border border-stone-200 dark:border-stone-800 bg-stone-50 hover:bg-purple-50 dark:bg-stone-850 dark:hover:bg-purple-950/30 text-stone-700 dark:text-stone-300 hover:text-purple-800 dark:hover:text-purple-400 transition text-left">
                        <span class="font-bold block text-stone-900 dark:text-stone-100">Admin Sistem</span>
                        <span class="text-[11px] text-stone-500">admin@myjournal.com</span>
                        <span class="text-[10px] text-purple-600 block mt-0.5">Kelola Prompt</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer link -->
        <p class="text-center text-sm text-stone-500 dark:text-stone-400">
            Belum memiliki akun jurnal?
            <a href="index.php?page=register" class="font-semibold text-amber-700 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300 transition">
                Daftar Sekarang
            </a>
        </p>

    </div>
</div>

<script>
    function fillCredentials(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
