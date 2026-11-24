<?php
$pageTitle = 'Daftar Akun Baru — MyJournal';
$old = Session::get('old_register', []);
Session::remove('old_register');
require __DIR__ . '/../layout/header.php';
?>

<div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-600 to-amber-500 text-white flex items-center justify-center shadow-lg shadow-amber-600/25 mb-4">
                <i data-lucide="feather" class="w-7 h-7"></i>
            </div>
            <h2 class="font-serif text-3xl font-bold tracking-tight text-stone-900 dark:text-stone-100">
                Mulai Ruang Jurnalmu
            </h2>
            <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">
                Tempat yang tenang, aman, dan bebas gangguan untuk merangkai pikiran serta menjaga kesehatan mental.
            </p>
        </div>

        <!-- Registration Card -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 sm:p-8 shadow-sm">
            <form action="index.php?page=register-process" method="POST" class="space-y-4">
                
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1.5">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <input id="name" name="name" type="text" required
                               value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                               class="w-full pl-10 pr-4 py-2.5 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100 transition placeholder-stone-400"
                               placeholder="Contoh: Sarah Pradipta">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input id="email" name="email" type="email" required autocomplete="email"
                               value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                               class="w-full pl-10 pr-4 py-2.5 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100 transition placeholder-stone-400"
                               placeholder="kamu@email.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1.5">
                            Kata Sandi
                        </label>
                        <input id="password" name="password" type="password" required minlength="6"
                               class="w-full px-3.5 py-2.5 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100 transition placeholder-stone-400"
                               placeholder="Min. 6 karakter">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 dark:text-stone-400 mb-1.5">
                            Ulangi Kata Sandi
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="6"
                               class="w-full px-3.5 py-2.5 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100 transition placeholder-stone-400"
                               placeholder="Ulangi sandi">
                    </div>
                </div>

                <!-- Bagian PIN Keamanan -->
                <div class="pt-3 border-t border-stone-200 dark:border-stone-800">
                    <div class="flex items-center gap-1.5 mb-1">
                        <i data-lucide="shield-lock" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i>
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-800 dark:text-stone-200">
                            Pengaturan PIN Keamanan Privasi
                        </span>
                    </div>
                    <p class="text-xs text-stone-500 dark:text-stone-400 mb-3">
                        Buat 4 hingga 6 digit PIN untuk mengunci dan membuka dashboard jurnalmu saat kamu menjauh dari perangkat.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="pin_code" class="block text-xs font-medium text-stone-600 dark:text-stone-400 mb-1">
                                PIN (4–6 Angka)
                            </label>
                            <input id="pin_code" name="pin_code" type="password" required pattern="[0-9]{4,6}" maxlength="6" inputmode="numeric"
                                   class="w-full px-3.5 py-2.5 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-sm tracking-widest font-mono focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100 transition placeholder-stone-400"
                                   placeholder="••••">
                        </div>
                        <div>
                            <label for="pin_code_confirmation" class="block text-xs font-medium text-stone-600 dark:text-stone-400 mb-1">
                                Ulangi PIN
                            </label>
                            <input id="pin_code_confirmation" name="pin_code_confirmation" type="password" required pattern="[0-9]{4,6}" maxlength="6" inputmode="numeric"
                                   class="w-full px-3.5 py-2.5 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-xl text-sm tracking-widest font-mono focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100 transition placeholder-stone-400"
                                   placeholder="••••">
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 px-4 bg-stone-900 hover:bg-stone-800 dark:bg-amber-600 dark:hover:bg-amber-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-stone-900/10 dark:shadow-amber-900/20 transition flex items-center justify-center gap-2">
                        <i data-lucide="sparkles" class="w-4 h-4 text-amber-300"></i>
                        <span>Daftarkan Jurnal Pribadi</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer link -->
        <p class="text-center text-sm text-stone-500 dark:text-stone-400">
            Sudah memiliki akun?
            <a href="index.php?page=login" class="font-semibold text-amber-700 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300 transition">
                Masuk di sini
            </a>
        </p>

    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
