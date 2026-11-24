<?php
$pageTitle = 'MyJournal — Ruang Jurnal Digital Pribadi, Pelacak Mood & Refleksi Diri';
require __DIR__ . '/../layout/header.php';
?>

<!-- Hero Section -->
<section class="relative overflow-hidden pt-12 pb-20 sm:pt-20 sm:pb-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-100/80 dark:bg-amber-950/50 border border-amber-200/80 dark:border-amber-900/60 text-amber-800 dark:text-amber-300 text-xs font-semibold">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    <span>Ruang Refleksi Mengutamakan Privasi</span>
                </div>

                <h1 class="font-serif text-4xl sm:text-6xl font-bold tracking-tight text-stone-900 dark:text-stone-50 leading-[1.15]">
                    Tuliskan pikiranmu.
                    <span class="block text-amber-700 dark:text-amber-400 italic font-serif">
                        Pahami dirimu lebih dalam.
                    </span>
                </h1>

                <p class="text-base sm:text-lg text-stone-600 dark:text-stone-300 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Buku harian digital yang tenang dan bebas gangguan. Pantau suasana hati harianmu, rawat rasa syukur, jaga konsistensi menulis, dan lindungi ceritamu dengan PIN Keamanan pribadi.
                </p>

                <!-- Tombol CTA -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 pt-2">
                    <?php if (Auth::check()): ?>
                        <a href="index.php?page=dashboard"
                           class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-semibold text-sm shadow-lg shadow-amber-700/25 transition flex items-center justify-center gap-2">
                            <span>Buka Jurnal Pribadiku</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=register"
                           class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-stone-900 hover:bg-stone-800 dark:bg-amber-600 dark:hover:bg-amber-700 text-white font-semibold text-sm shadow-lg shadow-stone-900/15 dark:shadow-amber-950/40 transition flex items-center justify-center gap-2">
                            <span>Mulai Menulis Jurnal</span>
                            <i data-lucide="sparkles" class="w-4 h-4 text-amber-300"></i>
                        </a>
                        <a href="index.php?page=login"
                           class="w-full sm:w-auto px-6 py-3.5 rounded-2xl border border-stone-300 dark:border-stone-700 text-stone-700 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-850 font-medium text-sm transition text-center">
                            Masuk ke Akun
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Indikator Kepercayaan -->
                <div class="pt-4 flex flex-wrap items-center justify-center lg:justify-start gap-6 text-xs text-stone-500 dark:text-stone-400">
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                        <span>Isolasi Data Pribadi Ketat</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                        <span>Admin Tidak Bisa Membaca Isi Jurnal</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                        <span>Kunci Cepat PIN 4-6 Angka</span>
                    </div>
                </div>

            </div>

            <!-- Right Hero Card: Pratinjau Visual Jurnal -->
            <div class="lg:col-span-5 relative">
                
                <div class="absolute -inset-4 bg-gradient-to-tr from-amber-200/50 via-orange-100/30 to-purple-200/40 dark:from-amber-900/20 dark:via-orange-950/10 dark:to-purple-900/20 rounded-3xl blur-2xl -z-10"></div>

                <div class="bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-3xl p-6 sm:p-7 shadow-xl space-y-5">
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-400"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                        </div>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800">
                            <i data-lucide="lock" class="w-3 h-3"></i> Dilindungi PIN
                        </span>
                    </div>

                    <div class="space-y-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-amber-700 dark:text-amber-400">
                            Hari Ini
                        </span>
                        <h3 class="font-serif text-xl font-bold text-stone-900 dark:text-stone-100">
                            Pagi yang Hening dan Secangkir Kopi
                        </h3>
                    </div>

                    <div class="p-3 bg-stone-50 dark:bg-stone-850 rounded-2xl flex items-center justify-between">
                        <span class="text-xs text-stone-500 font-medium">Suasana Hati</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                            <i data-lucide="smile" class="w-3.5 h-3.5"></i> Sangat Senang
                        </span>
                    </div>

                    <p class="text-xs text-stone-600 dark:text-stone-400 leading-relaxed font-sans italic border-l-2 border-amber-400 pl-3">
                        "Udara pagi ini begitu sejuk setelah hujan semalam. Duduk di teras sambil menikmati seduhan kopi hangat membawa rasa damai yang tak ternilai..."
                    </p>

                    <div class="space-y-1.5 text-xs">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 flex items-center gap-1">
                            <i data-lucide="heart" class="w-3 h-3 text-rose-500"></i> 3 Rasa Syukur Hari Ini:
                        </span>
                        <div class="space-y-1 text-stone-700 dark:text-stone-300">
                            <div class="flex items-center gap-2">
                                <span class="text-amber-500 font-bold">1.</span>
                                <span>Pagi yang tenang tanpa suara alarm terburu-buru</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-amber-500 font-bold">2.</span>
                                <span>Kabar baik dari keluarga tercinta</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-amber-500 font-bold">3.</span>
                                <span>Kesehatan dan energi untuk berkarya hari ini</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-stone-100 dark:border-stone-800">
                        <span class="text-[10px] font-medium bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400 px-2 py-0.5 rounded-md">#Pagi</span>
                        <span class="text-[10px] font-medium bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400 px-2 py-0.5 rounded-md">#Mindfulness</span>
                        <span class="text-[10px] font-medium bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400 px-2 py-0.5 rounded-md">#Kopi</span>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

<!-- Fitur Unggulan -->
<section class="py-16 bg-white dark:bg-stone-900 border-y border-stone-200/80 dark:border-stone-800/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-2">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">
                Dirancang untuk Ketenangan Pikiran
            </span>
            <h2 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 dark:text-stone-100">
                Semua yang kamu butuhkan untuk refleksi diri
            </h2>
            <p class="text-sm text-stone-500 dark:text-stone-400">
                Fitur lengkap tanpa kerumitan untuk mendukung kesehatan emosionalmu setiap hari.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Fitur 1 -->
            <div class="p-6 rounded-3xl bg-stone-50 dark:bg-stone-850 border border-stone-200/80 dark:border-stone-800 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <h3 class="font-serif text-xl font-bold text-stone-900 dark:text-stone-100">
                    PIN Keamanan Privasi
                </h3>
                <p class="text-xs text-stone-600 dark:text-stone-400 leading-relaxed">
                    Atur 4–6 digit PIN untuk mengunci ruang jurnalmu setiap saat. Bahkan admin sistem tidak memiliki akses untuk membaca isi jurnal pribadimu.
                </p>
            </div>

            <!-- Fitur 2 -->
            <div class="p-6 rounded-3xl bg-stone-50 dark:bg-stone-850 border border-stone-200/80 dark:border-stone-800 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center">
                    <i data-lucide="smile" class="w-6 h-6"></i>
                </div>
                <h3 class="font-serif text-xl font-bold text-stone-900 dark:text-stone-100">
                    Pelacak & Analisis Mood
                </h3>
                <p class="text-xs text-stone-600 dark:text-stone-400 leading-relaxed">
                    Pilih 1 dari 5 kondisi suasana hati harian. Lihat grafik perkembangan emosi mingguan dan bulanan melalui diagram interaktif Chart.js.
                </p>
            </div>

            <!-- Fitur 3 -->
            <div class="p-6 rounded-3xl bg-stone-50 dark:bg-stone-850 border border-stone-200/80 dark:border-stone-800 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-100 dark:bg-orange-950/60 text-orange-600 dark:text-orange-400 flex items-center justify-center">
                    <i data-lucide="flame" class="w-6 h-6"></i>
                </div>
                <h3 class="font-serif text-xl font-bold text-stone-900 dark:text-stone-100">
                    Streak & Kebiasaan Menulis
                </h3>
                <p class="text-xs text-stone-600 dark:text-stone-400 leading-relaxed">
                    Bangun kebiasaan journaling yang konsisten. Sistem kami otomatis menghitung hari berturut-turut tanpa membuatmu merasa terbebani.
                </p>
            </div>

            <!-- Fitur 4 -->
            <div class="p-6 rounded-3xl bg-stone-50 dark:bg-stone-850 border border-stone-200/80 dark:border-stone-800 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <i data-lucide="heart" class="w-6 h-6"></i>
                </div>
                <h3 class="font-serif text-xl font-bold text-stone-900 dark:text-stone-100">
                    3 Rasa Syukur Harian
                </h3>
                <p class="text-xs text-stone-600 dark:text-stone-400 leading-relaxed">
                    Kolom khusus untuk mencatat tiga hal yang kamu syukuri setiap hari, melatih cara pandang yang lebih positif dan tenang.
                </p>
            </div>

            <!-- Fitur 5 -->
            <div class="p-6 rounded-3xl bg-stone-50 dark:bg-stone-850 border border-stone-200/80 dark:border-stone-800 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                    <i data-lucide="image" class="w-6 h-6"></i>
                </div>
                <h3 class="font-serif text-xl font-bold text-stone-900 dark:text-stone-100">
                    Galeri Memory Lane
                </h3>
                <p class="text-xs text-stone-600 dark:text-stone-400 leading-relaxed">
                    Lampirkan foto kenangan pada setiap catatan jurnal dan nikmati galeri kenangan visual yang tersusun rapi secara kronologis.
                </p>
            </div>

            <!-- Fitur 6 -->
            <div class="p-6 rounded-3xl bg-stone-50 dark:bg-stone-850 border border-stone-200/80 dark:border-stone-800 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-sky-100 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                    <i data-lucide="compass" class="w-6 h-6"></i>
                </div>
                <h3 class="font-serif text-xl font-bold text-stone-900 dark:text-stone-100">
                    Prompt Refleksi Harian
                </h3>
                <p class="text-xs text-stone-600 dark:text-stone-400 leading-relaxed">
                    Tidak lagi bingung ingin menulis apa. Dapatkan pertanyaan refleksi harian yang dipilih secara acak untuk memantik perenungan diri yang mendalam.
                </p>
            </div>

        </div>

    </div>
</section>

<!-- Call to action -->
<section class="py-16 text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="font-serif text-3xl font-bold text-stone-900 dark:text-stone-100">
            Mulailah perjalanan refleksi dirimu hari ini.
        </h2>
        <p class="text-sm text-stone-500 dark:text-stone-400 mt-2 mb-6">
            Bergabunglah dan rasakan kenyamanan menulis di MyJournal.
        </p>
        <div class="flex items-center justify-center gap-3">
            <a href="index.php?page=register"
               class="px-6 py-3 bg-stone-900 hover:bg-stone-800 dark:bg-amber-600 dark:hover:bg-amber-700 text-white rounded-2xl text-sm font-semibold shadow-md transition">
                Daftar Akun Gratis
            </a>
            <a href="index.php?page=login"
               class="px-6 py-3 border border-stone-300 dark:border-stone-700 text-stone-700 dark:text-stone-300 rounded-2xl text-sm font-medium hover:bg-stone-100 dark:hover:bg-stone-850 transition">
                Masuk
            </a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
