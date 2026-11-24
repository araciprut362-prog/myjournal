<?php
$pageTitle = 'Ringkasan Sistem — Admin MyJournal';
require __DIR__ . '/../layout/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-stone-200/80 dark:border-stone-800/80">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                    <i data-lucide="shield" class="w-3.5 h-3.5"></i> Administrator Sistem
                </span>
            </div>
            <h1 class="font-serif text-3xl font-bold text-stone-900 dark:text-stone-100">
                Ringkasan Sistem & Metrik Agregat
            </h1>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">
                Statistik umum penggunaan platform dan kebiasaan menulis secara anonim.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="index.php?page=admin-users"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-stone-900 hover:bg-stone-50 dark:hover:bg-stone-800 text-stone-700 dark:text-stone-200 border border-stone-200 dark:border-stone-800 font-semibold text-xs shadow-sm transition">
                <i data-lucide="users" class="w-4 h-4 text-purple-600"></i>
                <span>Kelola Pengguna</span>
            </a>
            <a href="index.php?page=admin-prompts"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-purple-700 hover:bg-purple-800 text-white font-semibold text-xs shadow-sm transition">
                <i data-lucide="help-circle" class="w-4 h-4"></i>
                <span>Kelola Prompt</span>
            </a>
        </div>
    </div>

    <!-- Banner Privasi Ketat -->
    <div class="p-5 rounded-3xl bg-emerald-50/80 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 flex items-start gap-4">
        <div class="w-10 h-10 rounded-2xl bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shrink-0">
            <i data-lucide="lock" class="w-5 h-5"></i>
        </div>
        <div class="space-y-1">
            <h3 class="font-semibold text-stone-900 dark:text-stone-100 text-sm">
                Arsitektur Privasi Tanpa Akses Konten (Zero-Knowledge) Diterapkan
            </h3>
            <p class="text-xs text-stone-600 dark:text-stone-300 leading-relaxed">
                Sebagai Administrator, Anda memiliki akses untuk mengelola prompt refleksi dan melihat metrik agregat platform. Sesuai prinsip isolasi data dan privasi pengguna, <strong>administrator sama sekali tidak memiliki akses untuk membaca, mencari, atau membuka isi tulisan jurnal maupun rasa syukur pribadi milik pengguna</strong>.
            </p>
        </div>
    </div>

    <!-- Grid Kartu Statistik Agregat -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
        
        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Pengguna Terdaftar</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-serif text-3xl font-bold text-stone-900 dark:text-stone-100">
                    <?= $metrics['total_users'] ?>
                </span>
                <span class="text-xs text-stone-400">penulis</span>
            </div>
            <p class="text-xs text-stone-400 mt-1">Akun aktif</p>
        </div>

        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Total Jurnal</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-serif text-3xl font-bold text-amber-600 dark:text-amber-400">
                    <?= $metrics['total_entries'] ?>
                </span>
                <span class="text-xs text-stone-400">catatan</span>
            </div>
            <p class="text-xs text-stone-400 mt-1">Seluruh platform</p>
        </div>

        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Rasa Syukur Dicatat</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-serif text-3xl font-bold text-rose-600 dark:text-rose-400">
                    <?= $metrics['total_gratitudes'] ?>
                </span>
                <span class="text-xs text-stone-400">syukur</span>
            </div>
            <p class="text-xs text-stone-400 mt-1">Pernyataan bersyukur</p>
        </div>

        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Prompt Aktif</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-serif text-3xl font-bold text-purple-600 dark:text-purple-400">
                    <?= $metrics['total_prompts'] ?>
                </span>
                <span class="text-xs text-stone-400">pertanyaan</span>
            </div>
            <p class="text-xs text-stone-400 mt-1">Inspirasi harian</p>
        </div>

        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 shadow-sm col-span-2 lg:col-span-1">
            <span class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Rata-rata Streak</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-serif text-3xl font-bold text-orange-600 dark:text-orange-400">
                    <?= $metrics['avg_streak'] ?>
                </span>
                <span class="text-xs text-stone-400">hari</span>
            </div>
            <p class="text-xs text-stone-400 mt-1">Konsistensi penulis</p>
        </div>

    </div>

    <!-- Grafik Agregat -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Aktivitas 7 Hari -->
        <div class="lg:col-span-2 bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-stone-900 dark:text-stone-100 text-sm">
                        Aktivitas Penulisan Harian (7 Hari Terakhir)
                    </h2>
                    <p class="text-xs text-stone-400 dark:text-stone-500">
                        Volume total entri jurnal yang dibuat per hari.
                    </p>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-stone-100 dark:bg-stone-800 text-[11px] font-medium text-stone-500">
                    Entri / Hari
                </span>
            </div>

            <div class="relative h-64 sm:h-72 w-full">
                <canvas id="adminActivityChart"></canvas>
            </div>
        </div>

        <!-- Distribusi Mood Platform -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-4">
            <div>
                <h2 class="font-semibold text-stone-900 dark:text-stone-100 text-sm">
                    Distribusi Suasana Hati Platform
                </h2>
                <p class="text-xs text-stone-400 dark:text-stone-500">
                    Sebaran kondisi emosional seluruh pengguna.
                </p>
            </div>

            <div class="relative h-52 flex items-center justify-center">
                <canvas id="adminMoodChart"></canvas>
            </div>

            <div class="space-y-2 pt-2 border-t border-stone-100 dark:border-stone-800 text-xs">
                <?php
                $dist = $metrics['mood_distribution'];
                $colors = [
                    'Sangat Senang' => 'bg-emerald-500',
                    'Senang' => 'bg-sky-500',
                    'Biasa' => 'bg-amber-500',
                    'Sedih' => 'bg-indigo-500',
                    'Stres' => 'bg-rose-500',
                ];
                ?>
                <?php foreach ($dist as $m => $count): ?>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full <?= $colors[$m] ?>"></span>
                            <span class="text-stone-700 dark:text-stone-300"><?= $m ?></span>
                        </div>
                        <span class="font-semibold text-stone-900 dark:text-stone-100"><?= $count ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <!-- Baris Pengguna Terbaru & Prompt Refleksi -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Pengguna Terbaru -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-stone-900 dark:text-stone-100 text-sm">
                        Pengguna Platform Terbaru
                    </h2>
                    <p class="text-xs text-stone-400 dark:text-stone-500">
                        Daftar akun pengguna yang baru saja bergabung atau aktif.
                    </p>
                </div>
                <a href="index.php?page=admin-users" class="text-xs text-purple-600 dark:text-purple-400 font-semibold hover:underline">
                    Kelola semua pengguna →
                </a>
            </div>

            <div class="divide-y divide-stone-100 dark:divide-stone-800">
                <?php if (empty($recentUsers)): ?>
                    <p class="text-xs text-stone-400 py-3">Belum ada data pengguna.</p>
                <?php else: ?>
                    <?php foreach ($recentUsers as $ru): ?>
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 font-bold text-xs flex items-center justify-center shrink-0">
                                    <?= strtoupper(substr($ru['name'], 0, 1)) ?>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-stone-900 dark:text-stone-100 truncate">
                                        <?= htmlspecialchars($ru['name']) ?>
                                    </p>
                                    <p class="text-[11px] text-stone-400 truncate">
                                        <?= htmlspecialchars($ru['email']) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold <?= $ru['role'] === 'admin' ? 'bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300' : 'bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-300' ?>">
                                    <?= ucfirst($ru['role']) ?>
                                </span>
                                <span class="text-[11px] text-stone-400 font-medium">
                                    <?= (int)$ru['total_entries'] ?> entri
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pratinjau Prompt Aktif -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-stone-900 dark:text-stone-100 text-sm">
                        Pertanyaan Refleksi yang Sedang Aktif
                    </h2>
                    <p class="text-xs text-stone-400 dark:text-stone-500">
                        Pertanyaan yang dihadirkan kepada para penulis untuk inspirasi.
                    </p>
                </div>
                <a href="index.php?page=admin-prompts" class="text-xs text-purple-600 dark:text-purple-400 font-semibold hover:underline">
                    Kelola semua prompt →
                </a>
            </div>

            <div class="divide-y divide-stone-100 dark:divide-stone-800">
                <?php foreach ($recentPrompts as $rp): ?>
                    <div class="py-3 flex items-center justify-between gap-4">
                        <p class="font-serif text-sm font-medium text-stone-800 dark:text-stone-200 italic line-clamp-2">
                            "<?= htmlspecialchars($rp['question_text']) ?>"
                        </p>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $rp['is_active'] ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300' : 'bg-stone-100 dark:bg-stone-800 text-stone-500' ?> shrink-0">
                            <?= $rp['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</div>

<!-- Inisialisasi Grafik Admin -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.07)' : 'rgba(0, 0, 0, 0.06)';
        const textColor = isDark ? '#a8a29e' : '#78716c';

        // Grafik Bar Aktivitas
        const actCanvas = document.getElementById('adminActivityChart');
        if (actCanvas) {
            const rawAct = <?= json_encode($metrics['activity_data']) ?>;
            const labels = Object.keys(rawAct).map(d => {
                const parts = d.split('-');
                return parts[2] + '/' + parts[1];
            });
            const values = Object.values(rawAct);

            new Chart(actCanvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Entri Dibuat',
                        data: values,
                        backgroundColor: '#7e22ce',
                        borderRadius: 8,
                        barPercentage: 0.55
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: textColor },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // Grafik Donat Mood
        const moodCanvas = document.getElementById('adminMoodChart');
        if (moodCanvas) {
            const counts = <?= json_encode(array_values($metrics['mood_distribution'])) ?>;
            new Chart(moodCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Sangat Senang', 'Senang', 'Biasa', 'Sedih', 'Stres'],
                    datasets: [{
                        data: counts,
                        backgroundColor: ['#10b981', '#0ea5e9', '#f59e0b', '#6366f1', '#f43f5e'],
                        borderWidth: 2,
                        borderColor: isDark ? '#1c1917' : '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    cutout: '70%'
                }
            });
        }
    });
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
