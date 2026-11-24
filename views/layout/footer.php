    </main>

    <!-- Footer Tenang dan Minimalis -->
    <footer class="mt-auto border-t border-stone-200/80 dark:border-stone-800/80 bg-stone-100/50 dark:bg-[#0e0d0c] py-8 text-xs text-stone-500 dark:text-stone-400 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-amber-600/20 text-amber-700 dark:text-amber-400 flex items-center justify-center">
                    <i data-lucide="feather" class="w-3.5 h-3.5"></i>
                </div>
                <span class="font-serif font-semibold text-stone-700 dark:text-stone-300">MyJournal</span>
                <span class="text-stone-400">— Ruang pribadi digital yang tenang untuk refleksi, rasa syukur, dan kedamaian pikiran.</span>
            </div>
            <div class="flex items-center gap-4 text-stone-400 dark:text-stone-500">
                <span>Arsitektur Privasi Terisolasi</span>
                <span>•</span>
                <span>Perlindungan PIN Keamanan</span>
            </div>
        </div>
    </footer>

    <!-- Script: Lucide Icons, Mobile Menu, Dark Mode -->
    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const themeToggleBtn = document.getElementById('theme-toggle');
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', () => {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
            });
        }

        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileBtn && mobileMenu) {
            mobileBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
