<?php
$pageTitle = 'Analisis Mood & Emosi — MyJournal';
require __DIR__ . '/../layout/header.php';

$positiveCount = ($stats['counts']['Sangat Senang'] ?? 0) + ($stats['counts']['Senang'] ?? 0);
$totalMoods = array_sum($stats['counts']);
$positiveRatio = $totalMoods > 0 ? round(($positiveCount / $totalMoods) * 100) : 0;
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8">
    
    <!-- Top Header & Timeframe Switcher -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-stone-200/80 dark:border-stone-800/80">
        <div>
            <h1 class="font-serif text-3xl font-bold text-stone-900 dark:text-stone-100 flex items-center gap-2.5">
                <i data-lucide="sparkles" class="w-7 h-7 text-amber-500"></i>
                <span>Analisis Suasana Hati (Mood)</span>
            </h1>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">
                Visualisasi ritme emosional, tren suasana hati, dan perkembangan kesehatan mentalmu.
            </p>
        </div>

        <!-- Pemilih Periode -->
        <div class="inline-flex rounded-2xl bg-stone-200/80 dark:bg-stone-850 p-1 text-xs font-semibold">
            <a href="index.php?page=mood-analytics&days=7"
               class="px-3.5 py-1.5 rounded-xl transition <?= $days === 7 ? 'bg-white dark:bg-stone-700 text-stone-900 dark:text-white shadow-sm' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900' ?>">
                7 Hari Terakhir
            </a>
            <a href="index.php?page=mood-analytics&days=30"
               class="px-3.5 py-1.5 rounded-xl transition <?= $days === 30 ? 'bg-white dark:bg-stone-700 text-stone-900 dark:text-white shadow-sm' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900' ?>">
                30 Hari Terakhir
            </a>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Mood Dominan</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-serif text-2xl font-bold text-stone-900 dark:text-stone-100">
                    <?= htmlspecialchars($stats['most_frequent_mood']) ?>
                </span>
            </div>
            <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">Dalam <?= $days ?> hari terakhir</p>
        </div>

        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Keseimbangan Positif</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-serif text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                    <?= $positiveRatio ?>%
                </span>
            </div>
            <p class="text-xs text-stone-400 dark:text-stone-500 mt-1">Hari Senang / Sangat Senang</p>
        </div>

        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Streak Menulis</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-serif text-2xl font-bold text-orange-600 dark:text-orange-400">
                    <?= $streak ?>
                </span>
                <span class="text-xs text-stone-400">hari</span>
            </div>
            <p class="text-xs text-stone-400 dark:text-stone-500 mt-1">Konsistensi refleksi diri</p>
        </div>

        <div class="bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 rounded-3xl p-5 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-stone-400 dark:text-stone-500">Entri Pada Periode Ini</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-serif text-2xl font-bold text-stone-900 dark:text-stone-100">
                    <?= $stats['total_in_period'] ?>
                </span>
            </div>
            <p class="text-xs text-stone-400 dark:text-stone-500 mt-1">Momen tercatat</p>
        </div>

    </div>

    <!-- Grafik Baris -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Grafik Tren Garis -->
        <div class="lg:col-span-2 bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-stone-900 dark:text-stone-100 text-sm">
                        Tren Perkembangan Mood
                    </h2>
                    <p class="text-xs text-stone-400 dark:text-stone-500">
                        Memantau fluktuasi suasana hati (Skala 5 = Sangat Senang, 1 = Stres).
                    </p>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-stone-100 dark:bg-stone-800 text-[11px] font-medium text-stone-500">
                    Skala: 1 – 5
                </span>
            </div>

            <div class="relative h-64 sm:h-72 w-full">
                <?php if (empty($stats['scores'])): ?>
                    <div class="h-full flex items-center justify-center text-xs text-stone-400">
                        Belum ada data suasana hati pada periode yang dipilih.
                    </div>
                <?php else: ?>
                    <canvas id="moodTrendChart"></canvas>
                <?php endif; ?>
            </div>
        </div>

        <!-- Distribusi Mood Donat -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-4">
            <div>
                <h2 class="font-semibold text-stone-900 dark:text-stone-100 text-sm">
                    Distribusi Suasana Hati
                </h2>
                <p class="text-xs text-stone-400 dark:text-stone-500">
                    Proporsi setiap kondisi emosionalmu.
                </p>
            </div>

            <div class="relative h-56 flex items-center justify-center">
                <?php if ($totalMoods === 0): ?>
                    <div class="text-xs text-stone-400">Belum ada mood yang dicatat.</div>
                <?php else: ?>
                    <canvas id="moodDonutChart"></canvas>
                <?php endif; ?>
            </div>

            <div class="space-y-2 pt-2 border-t border-stone-100 dark:border-stone-800 text-xs">
                <?php
                $legendData = [
                    'Sangat Senang' => ['color' => 'bg-emerald-500', 'count' => $stats['counts']['Sangat Senang'] ?? 0],
                    'Senang' => ['color' => 'bg-sky-500', 'count' => $stats['counts']['Senang'] ?? 0],
                    'Biasa' => ['color' => 'bg-amber-500', 'count' => $stats['counts']['Biasa'] ?? 0],
                    'Sedih' => ['color' => 'bg-indigo-500', 'count' => $stats['counts']['Sedih'] ?? 0],
                    'Stres' => ['color' => 'bg-rose-500', 'count' => $stats['counts']['Stres'] ?? 0],
                ];
                ?>
                <?php foreach ($legendData as $label => $lData): ?>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full <?= $lData['color'] ?>"></span>
                            <span class="text-stone-700 dark:text-stone-300"><?= $label ?></span>
                        </div>
                        <span class="font-semibold text-stone-900 dark:text-stone-100"><?= $lData['count'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

    </div>

</div>

<!-- Inisialisasi Chart.js -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.07)' : 'rgba(0, 0, 0, 0.06)';
        const textColor = isDark ? '#a8a29e' : '#78716c';

        // 1. Grafik Garis
        const trendCanvas = document.getElementById('moodTrendChart');
        if (trendCanvas) {
            const labels = <?= json_encode($stats['labels']) ?>;
            const data = <?= json_encode($stats['scores']) ?>;

            new Chart(trendCanvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Tingkat Mood',
                        data: data,
                        borderColor: '#d97706',
                        backgroundColor: isDark ? 'rgba(217, 119, 6, 0.15)' : 'rgba(217, 119, 6, 0.1)',
                        borderWidth: 2.5,
                        tension: 0.35,
                        fill: true,
                        pointBackgroundColor: '#d97706',
                        pointBorderColor: isDark ? '#1c1917' : '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const val = context.parsed.y;
                                    const map = { 5: 'Sangat Senang', 4: 'Senang', 3: 'Biasa', 2: 'Sedih', 1: 'Stres' };
                                    return `Mood: ${map[Math.round(val)] || val} (${val})`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            min: 1,
                            max: 5,
                            ticks: {
                                stepSize: 1,
                                color: textColor,
                                callback: function(val) {
                                    const map = { 5: '😄 Sangat Senang', 4: '🙂 Senang', 3: '😐 Biasa', 2: '😔 Sedih', 1: '😣 Stres' };
                                    return map[val] || val;
                                }
                            },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        }
                    }
                }
            });
        }

        // 2. Grafik Donat
        const donutCanvas = document.getElementById('moodDonutChart');
        if (donutCanvas) {
            const counts = <?= json_encode([
                $stats['counts']['Sangat Senang'] ?? 0,
                $stats['counts']['Senang'] ?? 0,
                $stats['counts']['Biasa'] ?? 0,
                $stats['counts']['Sedih'] ?? 0,
                $stats['counts']['Stres'] ?? 0,
            ]) ?>;

            new Chart(donutCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Sangat Senang', 'Senang', 'Biasa', 'Sedih', 'Stres'],
                    datasets: [{
                        data: counts,
                        backgroundColor: [
                            '#10b981', // Sangat Senang
                            '#0ea5e9', // Senang
                            '#f59e0b', // Biasa
                            '#6366f1', // Sedih
                            '#f43f5e'  // Stres
                        ],
                        borderWidth: 2,
                        borderColor: isDark ? '#1c1917' : '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    cutout: '72%'
                }
            });
        }
    });
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
