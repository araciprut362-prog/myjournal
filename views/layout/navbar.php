<?php
$isAuth = Auth::check();
$isAdmin = Auth::isAdmin();
$isPinLocked = $isAuth && !$isAdmin && !Auth::isPinVerified();
$currentPage = $_GET['page'] ?? 'home';
?>
<header class="sticky top-0 z-40 w-full backdrop-blur-md bg-stone-50/90 dark:bg-[#121110]/90 border-b border-stone-200/80 dark:border-stone-800/80 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">
            
            <!-- Logo & Brand -->
            <div class="flex items-center gap-3">
                <a href="<?= $isAuth ? ($isAdmin ? 'index.php?page=admin-dashboard' : 'index.php?page=dashboard') : 'index.php' ?>" class="group flex items-center gap-2.5 transition">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-amber-600 to-amber-500 text-white flex items-center justify-center shadow-md shadow-amber-600/20 group-hover:scale-105 transition-transform">
                        <i data-lucide="book-heart" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="font-serif text-xl sm:text-2xl font-bold tracking-tight text-stone-900 dark:text-stone-100 group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors">
                            MyJournal
                        </span>
                        <span class="hidden sm:inline-block text-[11px] font-medium text-stone-400 dark:text-stone-500 uppercase tracking-widest pl-1.5 border-l border-stone-300 dark:border-stone-700 ml-1.5">
                            Ruang Refleksi
                        </span>
                    </div>
                </a>

                <?php if ($isAdmin): ?>
                    <span class="ml-2 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                        <i data-lucide="shield" class="w-3 h-3"></i> Admin Sistem
                    </span>
                <?php endif; ?>
            </div>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-1.5 lg:gap-2">
                <?php if ($isAuth): ?>
                    <?php if ($isAdmin): ?>
                        <a href="index.php?page=admin-dashboard" class="px-3.5 py-2 rounded-xl text-sm font-medium transition <?= $currentPage === 'admin-dashboard' ? 'bg-stone-200/70 dark:bg-stone-800 text-stone-900 dark:text-white font-semibold' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-850' ?>">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i>
                            Ringkasan
                        </a>
                        <a href="index.php?page=admin-users" class="px-3.5 py-2 rounded-xl text-sm font-medium transition <?= str_starts_with($currentPage, 'admin-user') ? 'bg-stone-200/70 dark:bg-stone-800 text-stone-900 dark:text-white font-semibold' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-850' ?>">
                            <i data-lucide="users" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i>
                            Kelola Pengguna
                        </a>
                        <a href="index.php?page=admin-prompts" class="px-3.5 py-2 rounded-xl text-sm font-medium transition <?= str_starts_with($currentPage, 'admin-prompt') ? 'bg-stone-200/70 dark:bg-stone-800 text-stone-900 dark:text-white font-semibold' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-850' ?>">
                            <i data-lucide="help-circle" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i>
                            Kelola Prompt
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=dashboard" class="px-3.5 py-2 rounded-xl text-sm font-medium transition <?= $currentPage === 'dashboard' ? 'bg-stone-200/80 dark:bg-stone-800 text-stone-900 dark:text-white font-semibold' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-850' ?>">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i>
                            Dashboard
                        </a>
                        <a href="index.php?page=journals" class="px-3.5 py-2 rounded-xl text-sm font-medium transition <?= ($currentPage === 'journals' || $currentPage === 'journal-detail') ? 'bg-stone-200/80 dark:bg-stone-800 text-stone-900 dark:text-white font-semibold' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-850' ?>">
                            <i data-lucide="book-open" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i>
                            Linimasa
                        </a>
                        <a href="index.php?page=mood-analytics" class="px-3.5 py-2 rounded-xl text-sm font-medium transition <?= $currentPage === 'mood-analytics' ? 'bg-stone-200/80 dark:bg-stone-800 text-stone-900 dark:text-white font-semibold' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-850' ?>">
                            <i data-lucide="sparkles" class="w-4 h-4 inline-block mr-1.5 -mt-0.5 text-amber-500"></i>
                            Analisis Mood
                        </a>
                        <a href="index.php?page=memory-lane" class="px-3.5 py-2 rounded-xl text-sm font-medium transition <?= $currentPage === 'memory-lane' ? 'bg-stone-200/80 dark:bg-stone-800 text-stone-900 dark:text-white font-semibold' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-850' ?>">
                            <i data-lucide="image" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i>
                            Memory Lane
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="index.php" class="px-3.5 py-2 rounded-xl text-sm font-medium text-stone-600 dark:text-stone-300 hover:text-stone-900 dark:hover:text-white transition">
                        Beranda
                    </a>
                    <a href="index.php?page=login" class="px-3.5 py-2 rounded-xl text-sm font-medium text-stone-600 dark:text-stone-300 hover:text-stone-900 dark:hover:text-white transition">
                        Masuk
                    </a>
                <?php endif; ?>
            </nav>

            <!-- Action Buttons & User Controls -->
            <div class="flex items-center gap-2 sm:gap-3">
                
                <!-- Dark Mode Toggle Button -->
                <button type="button" id="theme-toggle" aria-label="Ganti mode gelap/terang" class="w-9 h-9 rounded-xl flex items-center justify-center text-stone-500 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-200/60 dark:hover:bg-stone-800 transition">
                    <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
                    <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
                </button>

                <?php if ($isAuth): ?>
                    <?php if (!$isAdmin): ?>
                        <a href="index.php?page=journal-create" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white text-sm font-medium rounded-xl shadow-sm shadow-amber-700/20 hover:shadow-md transition">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Tulis Jurnal</span>
                        </a>

                        <a href="index.php?page=pin-lock-action" title="Kunci Jurnal Demi Privasi" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-xl bg-stone-100 dark:bg-stone-850 hover:bg-amber-50 dark:hover:bg-amber-950/40 text-stone-700 dark:text-stone-300 hover:text-amber-700 dark:hover:text-amber-400 border border-stone-200 dark:border-stone-800 transition">
                            <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                            <span class="hidden md:inline">Kunci</span>
                        </a>
                    <?php endif; ?>

                    <!-- User Name & Sign Out -->
                    <div class="relative group">
                        <div class="flex items-center gap-2 pl-2 border-l border-stone-200 dark:border-stone-800">
                            <div class="w-8 h-8 rounded-full bg-stone-200 dark:bg-stone-800 text-stone-700 dark:text-stone-300 flex items-center justify-center text-xs font-bold ring-2 ring-stone-100 dark:ring-stone-900">
                                <?= strtoupper(substr(Auth::name(), 0, 1)) ?>
                            </div>
                            <span class="hidden lg:block text-xs font-medium text-stone-700 dark:text-stone-300 max-w-[100px] truncate">
                                <?= htmlspecialchars(Auth::name()) ?>
                            </span>
                            <a href="index.php?page=logout" title="Keluar / Logout" class="p-1.5 rounded-lg text-stone-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="index.php?page=login" class="text-sm font-medium text-stone-700 dark:text-stone-300 hover:text-stone-900 dark:hover:text-white px-3 py-2 transition">
                        Masuk
                    </a>
                    <a href="index.php?page=register" class="inline-flex items-center gap-1.5 px-4 py-2 bg-stone-900 hover:bg-stone-800 dark:bg-amber-600 dark:hover:bg-amber-700 text-white text-sm font-medium rounded-xl shadow-sm transition">
                        Daftar Akun
                    </a>
                <?php endif; ?>

                <!-- Mobile Menu Button -->
                <button type="button" id="mobile-menu-btn" class="md:hidden p-2 rounded-xl text-stone-500 hover:text-stone-900 dark:text-stone-400 dark:hover:text-stone-100 hover:bg-stone-200/60 dark:hover:bg-stone-800 transition">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown Navigation -->
    <div id="mobile-menu" class="hidden md:hidden border-t border-stone-200 dark:border-stone-800 bg-stone-50/98 dark:bg-[#121110]/98 px-4 pt-3 pb-4 space-y-1">
        <?php if ($isAuth): ?>
            <?php if ($isAdmin): ?>
                <a href="index.php?page=admin-dashboard" class="block px-3 py-2 rounded-lg text-sm font-medium <?= $currentPage === 'admin-dashboard' ? 'bg-stone-200 dark:bg-stone-800 text-stone-900 dark:text-white' : 'text-stone-600 dark:text-stone-400' ?>">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 inline mr-2"></i> Ringkasan Sistem
                </a>
                <a href="index.php?page=admin-users" class="block px-3 py-2 rounded-lg text-sm font-medium <?= str_starts_with($currentPage, 'admin-user') ? 'bg-stone-200 dark:bg-stone-800 text-stone-900 dark:text-white' : 'text-stone-600 dark:text-stone-400' ?>">
                    <i data-lucide="users" class="w-4 h-4 inline mr-2"></i> Kelola Pengguna
                </a>
                <a href="index.php?page=admin-prompts" class="block px-3 py-2 rounded-lg text-sm font-medium <?= str_starts_with($currentPage, 'admin-prompt') ? 'bg-stone-200 dark:bg-stone-800 text-stone-900 dark:text-white' : 'text-stone-600 dark:text-stone-400' ?>">
                    <i data-lucide="help-circle" class="w-4 h-4 inline mr-2"></i> Kelola Prompt
                </a>
            <?php else: ?>
                <a href="index.php?page=dashboard" class="block px-3 py-2 rounded-lg text-sm font-medium <?= $currentPage === 'dashboard' ? 'bg-stone-200 dark:bg-stone-800 text-stone-900 dark:text-white' : 'text-stone-600 dark:text-stone-400' ?>">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 inline mr-2"></i> Dashboard
                </a>
                <a href="index.php?page=journals" class="block px-3 py-2 rounded-lg text-sm font-medium <?= $currentPage === 'journals' ? 'bg-stone-200 dark:bg-stone-800 text-stone-900 dark:text-white' : 'text-stone-600 dark:text-stone-400' ?>">
                    <i data-lucide="book-open" class="w-4 h-4 inline mr-2"></i> Linimasa
                </a>
                <a href="index.php?page=journal-create" class="block px-3 py-2 rounded-lg text-sm font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/30">
                    <i data-lucide="plus-circle" class="w-4 h-4 inline mr-2"></i> Tulis Jurnal Hari Ini
                </a>
                <a href="index.php?page=mood-analytics" class="block px-3 py-2 rounded-lg text-sm font-medium <?= $currentPage === 'mood-analytics' ? 'bg-stone-200 dark:bg-stone-800 text-stone-900 dark:text-white' : 'text-stone-600 dark:text-stone-400' ?>">
                    <i data-lucide="sparkles" class="w-4 h-4 inline mr-2"></i> Analisis Mood
                </a>
                <a href="index.php?page=memory-lane" class="block px-3 py-2 rounded-lg text-sm font-medium <?= $currentPage === 'memory-lane' ? 'bg-stone-200 dark:bg-stone-800 text-stone-900 dark:text-white' : 'text-stone-600 dark:text-stone-400' ?>">
                    <i data-lucide="image" class="w-4 h-4 inline mr-2"></i> Memory Lane
                </a>
                <a href="index.php?page=pin-lock-action" class="block px-3 py-2 rounded-lg text-sm font-medium text-stone-600 dark:text-stone-400 hover:text-amber-600">
                    <i data-lucide="lock" class="w-4 h-4 inline mr-2"></i> Kunci Jurnal Sekarang
                </a>
            <?php endif; ?>
            <a href="index.php?page=logout" class="block px-3 py-2 rounded-lg text-sm font-medium text-rose-600 dark:text-rose-400">
                <i data-lucide="log-out" class="w-4 h-4 inline mr-2"></i> Keluar
            </a>
        <?php else: ?>
            <a href="index.php" class="block px-3 py-2 rounded-lg text-sm font-medium text-stone-600 dark:text-stone-400">
                Beranda
            </a>
            <a href="index.php?page=login" class="block px-3 py-2 rounded-lg text-sm font-medium text-stone-600 dark:text-stone-400">
                Masuk
            </a>
            <a href="index.php?page=register" class="block px-3 py-2 rounded-lg text-sm font-medium text-amber-700 dark:text-amber-400 font-semibold">
                Daftar Akun Baru
            </a>
        <?php endif; ?>
    </div>
</header>
